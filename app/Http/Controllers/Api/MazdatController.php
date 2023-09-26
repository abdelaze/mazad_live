<?php

namespace App\Http\Controllers\Api;


use File;
use Carbon\Carbon;
use App\Models\Rate;
use App\Models\User;
use App\Models\Mazdat;
use App\Models\Product;
use App\Models\UserViews;
use App\Models\MazdatImage;
use App\Models\MazdatVideo;
use App\Models\Notification;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use App\Models\MazdatFavorite;
use Illuminate\Validation\Rule;
use App\Models\MazadSelectedUser;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\filters\mazdats\NameFilter;
use App\Jobs\SendMazadNotification;
use App\Models\ProductSelectedUser;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Auth;
use App\filters\mazdats\EndDateFilter;
use App\filters\mazdats\CategoryFilter;
use App\filters\mazdats\StartDateFilter;
use Illuminate\Support\Facades\Validator;
use App\filters\mazdats\SubCategoryFilter;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Mazdat\StoreRequest;
use App\Http\Requests\Api\Mazdat\updateRequest;
use App\Http\Resources\Api\Mazdat as  MazdatResource;
use App\Http\Resources\Api\Favorite as  FavoriteResource;
use App\Http\Resources\Rate                  as RateResource;

class MazdatController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index(Request $request)
    {
        
        //
        if($request->header('type') == "all") { 
            return  MazdatResource::collection(Mazdat::with(['category' , 'subcategory' , 'user' , 'images:id,mazdat_id,image', 'videos:id,mazdat_id,video'])->where(['status' => 1 , 'is_closed' => 0])->whereDate('end_date'  , '>='  , date('Y-m-d' , strtotime(Carbon::now()->addHours(2))))->paginate( config("constants.PAGIBNATION_COUNT")));
        }else {
            return  MazdatResource::collection(Mazdat::with(['category' , 'subcategory' , 'user' , 'images:id,mazdat_id,image' , 'videos:id,mazdat_id,video'])->where(['status' => 1 , 'is_closed' => 0 , 'user_id' => Auth::guard('api')->user()->id])->paginate( config("constants.PAGIBNATION_COUNT")));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {

     
        
        DB::beginTransaction();
        try{
            $validated                         =  $request->validated();
            $validated['user_id']              = Auth::guard('api')->user()->id;
            if($validated['type'] == 1) {
                $validated['display_date']     = Carbon::now()->addHours(2)->format('Y-m-d H:i');
                $validated['is_open']          = 1; 
            }
            $mazdat                            = Mazdat::create($validated);
            $mazdat->save();
            if($request->file('image')) {
                foreach($request->file('image') as $file) { 
                        $path = public_path('uploads/mazdats/');
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $fileName                   = uniqid() . '_' . trim($file->getClientOriginalName());
                        $file->move($path, $fileName);
                        $mazad_image                = new MazdatImage();
                        $mazad_image->image         =  $fileName;
                        $mazad_image->mazdat_id     =  $mazdat->id;
                        $mazad_image->save();
                }
            }

            if($request->file('video')) {
                foreach($request->file('video') as $file) { 
                        $path = public_path('uploads/mazdats/videos');
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $fileName                   = uniqid() . '_' . trim($file->getClientOriginalName());
                        $file->move($path, $fileName);
                        $mazad_video                = new MazdatVideo();
                        $mazad_video->video         =  $fileName;
                        $mazad_video->mazdat_id     =  $mazdat->id;
                        $mazad_video->save();
                }
            }
            DB::commit();
            if($mazdat) {

             /*   $factory = (new Factory)->withServiceAccount(__DIR__.'/firebaseKey.json')->withDatabaseUri('https://mazad-5d0f1-default-rtdb.firebaseio.com/');
                $database = $factory->createDatabase();
               // $database = (new Factory)->withServiceAccount(__DIR__.'/firebaseKey.json')->withDatabaseUri('https://mazad-5d0f1-default-rtdb.firebaseio.com/')->createDatabase();
                $user        =  !empty($mazdat->user())  ? $mazdat->user : [];
               // dd($user);
                $ref = "mazdats";
                $postData =  ['mazad_owner' => $user]  ; 
                $database->getReference($ref.'/'.  $mazdat->id)->set($postData);*/
                
                $user        =  !empty($mazdat->user())  ? $mazdat->user : [];
                // dd($user);
                 $ref = "mazdats";
                 $postData =  [
                    'mazad_id'                  => $mazdat->id 
                ] ; 
                $factory     = (new Factory)->withServiceAccount(__DIR__.'/firebaseKey.json');
                $firestore   =  $factory->createFirestore();
                $database    =  $firestore->database();
                $testRef     =  $database->collection('mazadats')->document($mazdat->id)->set($postData);

                $this->sendNotification($mazdat->id);
         
            }
            $success   = null; 
            return $this->sendResponse( $mazdat , trans('translation.data_addeded_successfully'));
            
        }catch(Exception $e) {
           DB::rollback();
           return $this->sendError('Server Error.', trans('messages.something_wrong_happen'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mazad             =  Mazdat::FindOrFail($id);
   
        $mview             =  UserViews::where('user_id' , Auth::guard('api')->user()->id)->where('mazdat_id' , $id)->first();
        if(empty($mview)) {
             $mazad->views      =  $mazad->views + 1 ; 
             $user_view               = new UserViews();
             $user_view->user_id      = Auth::guard('api')->user()->id;
             $user_view->mazdat_id    = $id;
             $user_view->save();
             $mazad->save();
        }
    
        return  MazdatResource::collection(Mazdat::with(['category' , 'subcategory' , 'user.rates_sum' , 'images:id,mazdat_id,image' , 'videos:id,mazdat_id,video'])->where(['id' => $id , 'status' => 1 , 'is_closed' => 0])->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateRequest $request, Mazdat $mazdat)
    {
        try{
            $validated                         =  $request->validated();
            $validated['user_id']              = Auth::guard('api')->user()->id;
            if($validated['type'] == 1) {
                $validated['display_date']     = Carbon::now()->addHours(2)->format('Y-m-d H:i');
                $validated['is_open']             = 1; 
            }else {
                $validated['is_open']             = 0; 
            }
            $mazdat->update($validated);
            $success   = null; 
            return $this->sendResponse($success, trans('translation.data_updated_successfully'));
            
        }catch(Exception $e) {
           DB::rollback();
           return $this->sendError('Server Error.', trans('messages.something_wrong_happen'));
        }
    }

    public function updateMazadImage(Request $request , MazdatImage $mazdat_image) {
       // dd($mazdat_image);
        $validator = Validator::make($request->all(), [
            //'mazadat_id'                     => ['required', Rule::exists('mazdats', 'id')],
            'image'                          => 'required|image|mimes:jpg,png,jpeg,gif,svg',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }
        if($request->file('image') && !empty($request->file('image'))) {
            $path = public_path('uploads/mazdats/');
            File::delete($path . $mazdat_image->getRawOriginal('image'));
            $file                 = $request->file('image');
            $fileName             = uniqid() . '_' . trim($file->getClientOriginalName());
            $image                = $fileName;
            $file->move($path, $fileName);
            $mazdat_image->image  = $image;
            $mazdat_image->save();
            $success   = null; 
            return $this->sendResponse($success, trans('translation.data_updated_successfully'));
        }    
    }


    public function updateMazadVideo(Request $request , MazdatVideo $mazdat_video) {
        // dd($mazdat_image);
         $validator = Validator::make($request->all(), [
             //'mazadat_id'                     => ['required', Rule::exists('mazdats', 'id')],
             'video'                            => 'required|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:102400'
         ]);
         if($validator->fails()){
             return $this->sendError('Validation Error.', $validator->errors()->first());
         }
         if($request->file('video') && !empty($request->file('video'))) {
             $path = public_path('uploads/mazdats/videos/');
             File::delete($path . $mazdat_video->getRawOriginal('video'));
             $file                 = $request->file('video');
             $fileName             = uniqid() . '_' . trim($file->getClientOriginalName());
             $video                = $fileName;
             $file->move($path, $fileName);
             $mazdat_video->video         =  $video ;
             $mazdat_video->save();
             $success   = null; 
             return $this->sendResponse($success, trans('translation.data_updated_successfully'));
         }    
     }

    public function OpenMazad(Request $request , Mazdat $mazdat) {
          $mazdat->is_open    = 1 ; 
          $mazdat->save();
    }

    public function CloseMazad(Request $request , Mazdat $mazdat) {
        $mazdat->is_closed    = 1 ; 
        $mazdat->save();
    }

    public function SelectMazadUser(Request $request) {
        // dd($mazdat_image);
         $validator = Validator::make($request->all(), [
             'mazdat_id'                      => ['required', Rule::exists('mazdats', 'id')->where('is_closed' , 0)],
             'user_id'                        => ['required', Rule::exists('users', 'id')],
             "price"                          => "required|regex:/^\d{1,13}(\.\d{1,4})?$/",
             'currency'                       => ['required'],
             'payment_status'                 => ['sometimes'],
         ]);
         if($validator->fails()){
             return $this->sendError('Validation Error.', $validator->errors()->first());
         }
        $mazad_user       =  MazadSelectedUser::where(['mazdat_id' =>   $request->mazdat_id])->first();
        $data             =  $request->all();
        $mazad            =  Mazdat::where('id' ,  $request->mazdat_id)->select('user_id')->first();
        if( $mazad_user) {
            if($mazad)  {
                $this->sendDeletedUserNotification( $mazad_user->user_id , $request->mazdat_id);
                $mazad_user->delete();  
                $data['owner_id']    = $mazad->user_id;
                MazadSelectedUser::create($data);   
            }
        }else {
            if($mazad)  {
               $data['owner_id']    = $mazad->user_id;
               MazadSelectedUser::create($data);
            }
        }
        $this->sendSelectedUserNotification($request->user_id , $request->mazdat_id);
        $success   = null; 
        return $this->sendResponse($success, trans('translation.data_addeded_successfully'));
            
     }


     public function MazadSelectedUser(Mazdat $mazdat) {
        // dd($mazdat_image);  
        $data       =  $mazdat->mazad_selected_user;
        return $this->sendResponse( $data , trans('translation.data_addeded_successfully'));
            
     }

     public function UserMazadsSelectedUsers() { 
        $data       =  MazadSelectedUser::with('mazad' , 'user' )->where(['owner_id' =>  auth()->guard('api')->user()->id])->get();
        return $this->sendResponse( $data , trans('translation.data_addeded_successfully'));
            
     }

     public function MySelectedMazdats() { 
        $data       =  MazadSelectedUser::with('mazad' , 'user' )->where(['user_id' =>  auth()->guard('api')->user()->id])->get();
        return $this->sendResponse( $data , trans('translation.data_addeded_successfully'));
            
     }

     // add mazad to favorites 
     public function addToFavorites(Request $request) {

        $validator = Validator::make($request->all(), [
            'mazdat_id'   => ['required', Rule::exists('mazdats', 'id')],
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }

        $f =  MazdatFavorite::where('mazdat_id', $request->mazdat_id)->where('user_id', Auth::guard('api')->user()->id)->first();

        if(!empty($f)) {
            $f->forceDelete();
            $success    = false;
            return $this->sendResponse($success, trans('translation.mazad_removed_from_your_favorite'));
        } else {
           $data = new MazdatFavorite();
           $data->mazdat_id = $request->mazdat_id;
           $data->user_id = Auth::guard('api')->user()->id;
           $data->save();
           $success    = true;
           return $this->sendResponse($success, trans('translation.mazad_addeded_to_your_favorites_successfully'));
        }
    }

    // get all use mazad favorites 

    public function UserFavorites() {
        return FavoriteResource::collection(MazdatFavorite::where('user_id',Auth::guard('api')->user()->id)->with('mazad.category')->with('mazad.images')->with('mazad.user.rates_sum')->paginate(config("constants.PAGIBNATION_COUNT")));
    }

    // filter mazad data 
    public function filter(Request $request) {
        
        $mazdats = app(Pipeline::class)
        ->send(Mazdat::query())
        ->through([
            CategoryFilter::class,
            SubCategoryFilter::class,
            NameFilter::class,
            StartDateFilter::class,
            EndDateFilter::class
        ])
        ->thenReturn()
        ->paginate(config("constants.PAGIBNATION_COUNT"));

        return  MazdatResource::collection($mazdats);
    }

    public function addToRates(Request $request) {

        $validator = Validator::make($request->all(), [
            'rate'              => 'required',
            'mazdat_id'   => ['required', Rule::exists('mazdats', 'id')],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }

        $rate = Rate::where('mazdat_id',$request->mazdat_id)->where('user_id',Auth::guard('api')->user()->id)->first();
        if( $rate ) {
            $rate->rate          = $request->rate;
            $rate->comment       = $request->comment;
            $rate->save();   
        }else {
            $data = new Rate();
            $data->mazdat_id      = $request->mazdat_id;
            $data->user_id       = Auth::guard('api')->user()->id;
            $data->rate          = $request->rate;
            $data->comment       = $request->comment;
            $data->save();    
        } 
        
        $success    = null;
        return $this->sendResponse($success, trans('translation.data_addeded_successfully'));
    }

    public function mazadRates(Request $request) {
        $validator = Validator::make($request->all(), [
            'mazdat_id'   => ['required', Rule::exists('mazdats', 'id')],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }
        return RateResource::collection(Rate::with(['mazad:id,product_name' , 'user:id,full_name,photo'])->where('mazdat_id' , $request->mazdat_id)->get());
     }

    public function search(Request $request) {
           
            if(!empty($request->search)) {
                 return MazdatResource::collection(Mazdat::with(['category' , 'subcategory' , 'user.rates_sum' , 'images:id,mazdat_id,image'])
                                               ->where(function($query) use($request ) {
                                                    $query->where('product_name','like', '%'.$request->search.'%')->orWhere('product_desc','like', '%'.$request->search.'%');
                                                })->where(['status' => 1 , 'is_closed' => 0])->paginate( config("constants.PAGIBNATION_COUNT")));    
            }   
    }


    public function featuredMazdats()
    {
        return MazdatResource::collection(Mazdat::with(['category' , 'subcategory' , 'user.rates_sum' , 'images:id,mazdat_id,image'])
                                                   ->inRandomOrder()->limit(10)->where(['status' => 1 , 'is_closed' => 0])->get());
    }
    
    public function openMazdats()
    {
        return MazdatResource::collection(Mazdat::with(['category' , 'subcategory' , 'user.rates_sum' , 'images:id,mazdat_id,image'])
                                                   ->inRandomOrder()->limit(10)->where(['status' => 1 , 'is_open' => 1 ,'is_closed' => 0])->get());
    }
    
    public function soldMazdats()
    {
        return MazdatResource::collection(Mazdat::with(['category' , 'subcategory' , 'user.rates_sum' , 'images:id,mazdat_id,image'])
                                                   ->inRandomOrder()->limit(10)->where(['status' => 1 , 'is_closed' => 1])->get());
    }

 

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mazdat $mazdat)
    {
        
     
        if(!empty($mazdat->images)) {
            foreach( $mazdat->images as  $image) {
                $path = public_path('uploads/mazdats/');
                if(File::exists($path . $image->getRawOriginal('image'))){

                    File::delete($path . $image->getRawOriginal('image'));
                }
               // File::delete($path . $image->getRawOriginal('image'));
            }
        }

        if(!empty($mazdat->videos)) {
            foreach( $mazdat->videos as  $video) {

                $path = public_path('uploads/mazdats/videos/');
               // dd($path . $video->getRawOriginal('video'));
                if(File::exists($path . $video->getRawOriginal('video'))){
                    File::delete($path . $video->getRawOriginal('video'));
                }
              //  File::delete($path . $video->getRawOriginal('video'));
            }
        }
        
        $mazdat->images()->delete();
        $mazdat->favorites()->delete();
        $mazdat->videos()->delete(); 
        $mazdat->delete();
       
        $success   = null; 
        return $this->sendResponse($success, trans('translation.data_deleted_successfully'));
    }

    public function sendNotification($mazad_id) {

        $title       =  "A new auction has been added";
        $title_ar    =  "تم اضافة مزاد جديد";
        $details     =  "A new auction has been added. Enter the application to know more details about the auction";
        $details_ar  =  "تم اضافة مزاد جديد ادخل على التطبيق لمعرفة تفاصيل اكتر عن المزاد";

        $users       =  User::select('id' , 'fcm_token')
                            ->chunk(50,function($data)  use( $mazad_id ,$title, $title_ar ,  $details ,  $details_ar ){
                                dispatch(new SendMazadNotification($data , $mazad_id , $title, $title_ar ,  $details ,  $details_ar ));
                            });
    }

    public function sendDeletedUserNotification($user_id ,   $mazad_id) {
            $title       =  "A bidder with a value higher than your value has been selected";
            $title_ar    =  "تم اختيار مزايد بقيمه اعلى من قيمتك";
            $details     =  "A bidder was chosen with a value higher than your value because you were late in paying. Enter the auction to offer a higher value";
            $details_ar  =  "تم اختيار مزايد بقيمه اعلى من قيمتك لانك تاخرت فى الدفع ادخل على المزاد لعرض قيمه اعلى ";
            
            $SERVER_API_KEY              = 'AAAA6VrAzvE:APA91bELB98C-KCuB4tGXQ2neDGhB7PhHzjqMW_woj8LR4kk26XJbIRWcAaGNrkqXSDr11ZkykXyaA7DmGHJpGyNWlpDo0PpeFjoEg1ggMMLmhTnUA8g3T1QscQcoyyeDtasXtmXZzW1';
          
            $user    = User::where('id' , $user_id)->first();
            if ( $user && !empty($user->fcm_token)) {
                  
                $data = [
                    'registration_ids'        =>   [ $user->fcm_token ],
                    'notification'            =>   [
                        'title'               =>   $title,
                        'body'                =>   $details,
                        'sound'               =>   'alert',
                        'content_available'   =>   true,
                        'priority'            =>   'high',
                    ],
                    'data'                    =>   [
                        'click_action'        =>   'FLUTTER_NOTIFICATION_CLICK',
                        'mazad_id'            =>    $mazad_id,
                        'type'                =>   'mazad'
                    ],
                    //  'to' =>  '/topics/topic',
                ];
                $dataString = json_encode($data);
                $headers = [
                    'Authorization: key=' . $SERVER_API_KEY,
                    'Content-Type: application/json',
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                $response = curl_exec($ch);

                $notifcation             = new Notification();
                $notifcation->title      =  ['en' => $title    , 'ar' => $title_ar];
                $notifcation->details    =  ['en' => $details  , 'ar' => $details_ar];
                $notifcation->mazad_id   =   $mazad_id ;
                $notifcation->user_id    =   $user_id;
                $notifcation->type       =  "mazad";
                $notifcation->save();
            }
     }


     public function sendSelectedUserNotification($user_id ,   $mazad_id) {

        $title       =  "You have been selected to purchase the auction";
        $title_ar    =  "تم اختيارك لشراء المزاد";
        $details     =  "You have been selected to buy the auction. Enter the auction to complete the payment process before offering a value higher than your value from another bidder.";
        $details_ar  =  "تم اخنيارك لشراء المزاد ادخل على المزاد لاتمام عملية الدفع قبل عرض قيمة اعلى من قيمتك من مزايد اخر";
        
        $SERVER_API_KEY              = 'AAAA6VrAzvE:APA91bELB98C-KCuB4tGXQ2neDGhB7PhHzjqMW_woj8LR4kk26XJbIRWcAaGNrkqXSDr11ZkykXyaA7DmGHJpGyNWlpDo0PpeFjoEg1ggMMLmhTnUA8g3T1QscQcoyyeDtasXtmXZzW1';
      
        $user    = User::where('id' , $user_id)->first();
        if ( $user && !empty($user->fcm_token)) {
              
            $data = [
                'registration_ids'        =>   [ $user->fcm_token ],
                'notification'            =>   [
                    'title'               =>   $title,
                    'body'                =>   $details,
                    'sound'               =>   'alert',
                    'content_available'   =>   true,
                    'priority'            =>   'high',
                ],
                'data'                    =>   [
                    'click_action'        =>   'FLUTTER_NOTIFICATION_CLICK',
                    'mazad_id'            =>    $mazad_id,
                    'type'                =>   'mazad'
                ],
                //  'to' =>  '/topics/topic',
            ];
            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);

            $notifcation             = new Notification();
            $notifcation->title      =  ['en' => $title    , 'ar' => $title_ar];
            $notifcation->details    =  ['en' => $details  , 'ar' => $details_ar];
            $notifcation->mazad_id   =   $mazad_id ;
            $notifcation->user_id    =   $user_id;
            $notifcation->type       =  "mazad";
            $notifcation->save();
        }
   }


   public function UpdatePaymentHistory(Request $request) {
    $validator = Validator::make($request->all(), [
        'id'              =>  'required|exists:mazad_selected_users,id',
        'payment_id'      =>  'required|unique:mazad_selected_users',
    ]);

    if ($validator->fails()) {
        return $this->sendError('Validation Error.', $validator->errors()->first());
    }

    $payment                  = MazadSelectedUser::where('id' , $request->id)->first();
    if( $payment) {
        $payment->payment_id  = $request->payment_id;
        $payment->save();
    }
    $success = true;
    return $this->sendResponse($success , 'data updated successfully.');
 }


 public function paymentCallback() {
    try {
        $data      = request()->query();
        dd($data);
        $id        = $data['id'];
        $order     = $data['order'];
        $success   = $data['success'];
        $order     = $data['pending'];
      //  return $this->sendResponse(  $id , 'subscription done successfully.');

    //  subscribt to package
            if ($success == "true") {
                $payment                   = MazadSelectedUser::where('payment_id',  $id)->first();
                   
                $payment2                  = ProductSelectedUser::where('payment_id',  $id)->first();
                   
                if ($payment2) {
                    $payment->payment_status   = "paid";
                    $payment->save();   
                    $product = Product::where('id' , $payment->product_id)->first();

                    if($product) {
                          $product->is_sold = 1;
                          $product->save();
                    }
                    // return success
                    return view('payments.success');

                } else {
                    $success = false;
                    return $this->sendResponse($success, 'This Product Not Available.');
                }

                    if ($payment) {
                        $payment->payment_status   = "paid";
                        $payment->save();   
                        $mazad = Mazdat::where('id' , $payment->mazdat_id)->first();

                        if($mazad) {
                              $mazad->is_closed = 1;
                              $mazad->save();
                        }
                        // return success
                        return view('payments.success');

                    } else {
                        $success = false;
                        return $this->sendResponse($success, 'This Mazad Not Available.');
                    }
            }else {
                // return failed
                    $success = false;
                    return view('payments.error');
            }

        } catch(Exception $e) {
            DB::rollback();
            dd($e);
        }


}

 


}
