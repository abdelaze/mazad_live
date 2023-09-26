<?php

namespace App\Http\Controllers\Api;

use File;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\UserViews;
use App\Models\Notification;
use App\Models\ProductImage;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use App\Models\MazdatFavorite;
use App\Models\ProductFavorite;
use Illuminate\Validation\Rule;
use App\Models\ProductMazadUser;
use App\Models\MazadSelectedUser;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSelectedUser;
use App\filters\products\NameFilter;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendProductNotification;
use App\filters\products\EndDateFilter;
use App\filters\products\CategoryFilter;
use App\filters\products\StartDateFilter;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Product\StoreRequest;
use App\Http\Requests\Api\Product\UpdateRequest;
use App\Http\Resources\Api\Product as  ProductResource;
use App\Http\Resources\Api\Favorite as  FavoriteResource;
use App\Http\Resources\Api\UserFavorite as  UserFavoriteResource;
use App\Http\Resources\Api\ProductFavorite as  ProductFavoriteResource;

class ProductController extends BaseController
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
            return  ProductResource::collection(Product::with(['category' , 'subcategory' , 'country' , 'state' , 'city' , 'user' , 'images:id,product_id,image'])->where(['status' => 1 , 'is_sold' => 0])->paginate( config("constants.PAGIBNATION_COUNT")));
        }else {
            return  ProductResource::collection(Product::with(['category' , 'subcategory' , 'country' , 'state' , 'city', 'user' , 'images:id,product_id,image'])->where(['status' => 1 , 'is_sold' => 0 , 'user_id' => Auth::guard('api')->user()->id])->paginate( config("constants.PAGIBNATION_COUNT")));
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
            $validated['user_id']              =  Auth::guard('api')->user()->id;
            $product                            =  Product::create($validated);
            $product->save();
            if($request->file('image')) {
                foreach($request->file('image') as $file) { 
                        $path = public_path('uploads/products/');
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $fileName                      = uniqid() . '_' . trim($file->getClientOriginalName());
                        $file->move($path, $fileName);
                        $product_image                 = new ProductImage();
                        $product_image->image          =  $fileName;
                        $product_image->product_id     =  $product->id;
                        $product_image->save();
                }
            }

            $this->storeOptions( $request->options, $request->options_ar, $product );
            DB::commit();

            $this->sendNotification($product->id);

            $success   = null; 
            return $this->sendResponse($success, trans('translation.data_addeded_successfully'));
            
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
        $product             =  Product::FindOrFail($id);
        $mview               =  UserViews::where('user_id' , Auth::guard('api')->user()->id)->where('product_id' , $id)->first();
        if(empty($mview)) {
             $user_view               = new UserViews();
             $user_view->user_id      = Auth::guard('api')->user()->id;
             $user_view->product_id    = $id;
             $user_view->save();
             $product->views      =  $product->views + 1 ; 
             $product->save();
        }
       
        return  ProductResource::collection(Product::with(['category' , 'subcategory' ,'brand', 'country' , 'state' , 'city' , 'user.rates_sum' , 'images:id,product_id,image' ,'options' ,'options_ar'])->where(['id' => $id , 'status' => 1 , 'is_sold' => 0])->get());
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
    public function update(UpdateRequest $request, Product $product)
    {
        try{
            $validated                         =  $request->validated();
            $validated['user_id']              =  Auth::guard('api')->user()->id;
            $product->update($validated);
            $success   = null; 
            return $this->sendResponse($success, trans('translation.data_updated_successfully'));
            
        }catch(Exception $e) {
           DB::rollback();
           return $this->sendError('Server Error.', trans('messages.something_wrong_happen'));
        }
    }

    public function updateProductImage(Request $request , ProductImage $product_image) {

        $validator = Validator::make($request->all(), [
            'image'                          => 'required|image|mimes:jpg,png,jpeg,gif,svg',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }
        if($request->file('image') && !empty($request->file('image'))) {
            $path = public_path('uploads/products/');
            File::delete($path . $product_image->image);
            $file                 = $request->file('image');
            $fileName             = uniqid() . '_' . trim($file->getClientOriginalName());
            $image                = $fileName;
            $file->move($path, $fileName);
            $product_image->image  = $image;
            $product_image->save();
            $success   = null; 
            return $this->sendResponse($success, trans('translation.data_updated_successfully'));
        }    
    }

    public function SoldProduct(Request $request , Product $product) {
          $product->is_sold    = 1 ; 
          $product->save();
    }

    public function SelectProductUser(Request $request) {
        // dd($mazdat_image);
         $validator = Validator::make($request->all(), [
             'product_id'                     => ['required', Rule::exists('products', 'id')->where('is_sold' , 0)],
             'user_id'                        => ['required', Rule::exists('users', 'id')],
             "amount"                         => "required|regex:/^\d{1,13}(\.\d{1,4})?$/",
             'currency'                       => ['required'],
             'payment_status'                 => ['sometimes'],
         ]);
         if($validator->fails()){
             return $this->sendError('Validation Error.', $validator->errors()->first());
         }
         
         $product_user       =  ProductSelectedUser::where(['product_id' =>   $request->product_id ])->first();
         $data               =  $request->all();
         $product            =  Product::where('id' ,  $request->product_id)->select('user_id')->first();
         if( $product_user) {
            if($product)  {
                $this->sendDeletedUserNotification( $product_user->user_id , $request->product_id);
                $product_user->delete();   
                $data['owner_id']    = $product->user_id;
                ProductSelectedUser::create($data); 
            } 
         }else {
            if($product)  {
               $data['owner_id']    = $product->user_id;
               ProductSelectedUser::create($data);
            }
         }
        $this->sendSelectedUserNotification($request->user_id , $request->product_id);
         
       // ProductSelectedUser::create($request->all());
        $success   = true; 
        return $this->sendResponse($success, trans('translation.data_addeded_successfully'));
            
     }

     public function AddMazadProduct(Request $request) {
        // dd($mazdat_image);
         $validator = Validator::make($request->all(), [
             'product_id'                     => ['required', Rule::exists('products', 'id')],
             'user_id'                        => ['required', Rule::exists('users', 'id')],
             "amount"                         => "required|regex:/^\d{1,13}(\.\d{1,4})?$/",
             'currency'                       => ['required'],
         ]);

         if($validator->fails()){
             return $this->sendError('Validation Error.', $validator->errors()->first());
         }
         
         $product_user       =    ProductMazadUser::where(['product_id' =>   $request->product_id ])->first();
         
       
            if( $product_user) {
                if($product_user->amount >= $request->amount) {
                    $success   = false; 
                    return $this->sendResponse($success, trans('translation.data_amount_larager_than_existed'));
                }
                $product_user->delete();    
                ProductMazadUser::create($request->all()); 
            }else {
                ProductMazadUser::create($request->all());
            }
            $success   = true; 
            return $this->sendResponse($success, trans('translation.data_addeded_successfully'));
       
       
       
            
     }

     public function UserSelectProduct(Product $product) {
        // dd($mazdat_image);  
        $data       =  $product->product_mazad_user;
        return $this->sendResponse( $data , trans('translation.data_returned_successfully'));
            
     }

     public function ProductSelectedUser(Product $product) {
        // dd($mazdat_image);  
        $data       =  $product->product_selected_user;
        return $this->sendResponse( $data , trans('translation.data_returned_successfully'));
            
     }

     // add product to favorites 
     public function addToFavorites(Request $request) {

        $validator = Validator::make($request->all(), [
            'product_id'     => ['required', Rule::exists('products', 'id')],
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }

        $f =  ProductFavorite::where('product_id', $request->product_id)->where('user_id', Auth::guard('api')->user()->id)->first();

        if(!empty($f)) {
            $f->forceDelete();
            $success    = false;
            return $this->sendResponse($success, trans('translation.product_removed_from_your_favorite'));
        } else {
           $data = new ProductFavorite();
           $data->product_id = $request->product_id;
           $data->user_id = Auth::guard('api')->user()->id;
           $data->save();
           $success    = true;
           return $this->sendResponse($success, trans('translation.product_addeded_to_your_favorites_successfully'));
        }
    }

      // add user to favorites 
      public function addUserToFavorites(Request $request) {

        $validator = Validator::make($request->all(), [
            'user_id'     => ['required', Rule::exists('users', 'id')],
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }

        $f =  UserFavorite::where('favorite_user_id', $request->user_id)->where('user_id', Auth::guard('api')->user()->id)->first();

        if(!empty($f)) {
            $f->forceDelete();
            $success    = false;
            return $this->sendResponse($success, trans('translation.user_removed_from_your_favorite'));
        } else {
           $data                    = new UserFavorite();
           $data->favorite_user_id  = $request->user_id;
           $data->user_id           = Auth::guard('api')->user()->id;
           $data->save();
           $success    = true;
           return $this->sendResponse($success, trans('translation.user_addeded_to_your_favorites_successfully'));
        }
    }
   

    // get all use mazad favorites 

    public function UserFavorites() {
        return FavoriteResource::collection(MazdatFavorite::where('user_id',Auth::guard('api')->user()->id)->with('mazad.category')->with('mazad.images')->with('mazad.user.rates_sum')->paginate(config("constants.PAGIBNATION_COUNT")));
    }

    public function UserFavoriteProducts() {
        return ProductFavoriteResource::collection(ProductFavorite::where('user_id',Auth::guard('api')->user()->id)->with('product.category')->with('product.subcategory')->with('product.images')->with('product.user.rates_sum')->paginate(config("constants.PAGIBNATION_COUNT")));
    }

    public function UserFavoriteUsers() {
        return UserFavoriteResource::collection(UserFavorite::where('user_id',Auth::guard('api')->user()->id)->with('favorite_user.rates_sum')->paginate(config("constants.PAGIBNATION_COUNT")));
    }

    // filter mazad data 
    public function filter(Request $request) {
        
        $products = app(Pipeline::class)
        ->send(Product::query())
        ->through([
            CategoryFilter::class,
            NameFilter::class,
        ])
        ->thenReturn()
        ->paginate(config("constants.PAGIBNATION_COUNT"));

        return  ProductResource::collection($products);
    }


    public function search(Request $request) {
       
        if(!empty($request->search)) {
            return ProductResource::collection(Product::with(['category' , 'subcategory' , 'country' , 'state' , 'city' , 'user.rates_sum' , 'images:id,product_id,image'])
                                           ->where(function($query) use($request ) {
                                                $query->where('product_name','like', '%'.$request->search.'%')->orWhere('product_desc','like', '%'.$request->search.'%');
                                            })->where(['status' => 1 , 'is_sold' => 0])->paginate( config("constants.PAGIBNATION_COUNT")));       
        }    
    }

    public function featuredProducts()
    {
        return ProductResource::collection(Product::with(['category' , 'subcategory' , 'country' , 'state' , 'city' , 'user.rates_sum' , 'images:id,product_id,image'])
                                                   ->inRandomOrder()->limit(10)->where(['status' => 1 , 'is_sold' => 0])->get());
    }


 

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        $success   = null; 
        return $this->sendResponse($success, trans('translation.data_deleted_successfully'));
    }

    private function storeOptions($options ,$options_ar, $product)
    {
        if (!empty($options)) {
            foreach ($options as $kkey => $value) {
                if (!empty($options[$kkey])  && is_array($options[$kkey])) {
                    $value = implode(',', $options[$kkey]);
                }
                
                $product->options()->updateOrCreate(['product_id' => $product->id, 'key' => $kkey], ['value' => $value]);
            }
        }

        if (!empty($options_ar)) {
            foreach ($options_ar as $key2 => $value_ar) {
                if (is_array($options_ar[$key2])) {
                    $value_ar = implode(',', $options_ar[$key2]);
                }
                $product->options()->updateOrCreate(['product_id' => $product->id, 'key_ar' => $key2], ['value_ar' => $value_ar]);
            }
        }
    }

    public function sendNotification($product_id) {

        $title       =  "A new auction has been added";
        $title_ar    =  "تم اضافة منتج جديد";
        $details     =  "A new auction has been added. Enter the application to know more details about the auction";
        $details_ar  =  "تم اضافة منتج جديد ادخل على التطبيق لمعرفة تفاصيل اكتر عن المنتج";

        $users       =  User::select('id' , 'fcm_token')
                            ->chunk(50,function($data)  use( $product_id ,$title, $title_ar ,  $details ,  $details_ar ){
                                dispatch(new SendProductNotification($data , $product_id , $title, $title_ar ,  $details ,  $details_ar ));
                            });
    }

    
    public function sendDeletedUserNotification($user_id ,   $product_id) {
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
                    'mazad_id'            =>    $product_id,
                    'type'                =>   'product'
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
            $notifcation->mazad_id   =   $product_id ;
            $notifcation->user_id    =   $user_id;
            $notifcation->type       =  "product";
            $notifcation->save();
        }
 }


 public function sendSelectedUserNotification($user_id ,   $product_id) {
    $title       =  "You have been selected to purchase the auction";
    $title_ar    =  "تم اختيارك لشراء المنتج";
    $details     =  "You have been selected to buy the product. Enter the auction to complete the payment process before offering a value higher than your value from another bidder.";
    $details_ar  =  "تم اخنيارك لشراء المنتج ادخل على المنتج لاتمام عملية الدفع قبل عرض قيمة اعلى من قيمتك من مزايد اخر";
    
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
                    'mazad_id'            =>    $product_id,
                    'type'                =>   'product'
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
            $notifcation->mazad_id   =   $product_id ;
            $notifcation->user_id    =   $user_id;
            $notifcation->type       =  "product";
            $notifcation->save();
        }
    }

   
    public function UserProductsSelectedUsers() { 
        $data       =  ProductSelectedUser::with('product' , 'user' )->where(['owner_id' =>  auth()->guard('api')->user()->id])->get();
        return $this->sendResponse( $data , trans('translation.data_returned_successfully'));
            
     }

     public function MySelectedProducts() { 
        $data       =  ProductSelectedUser::with('product' , 'user' )->where(['user_id' =>  auth()->guard('api')->user()->id])->get();
        return $this->sendResponse( $data , trans('translation.data_returned_successfully'));
            
     }

     public function UpdatePaymentHistory(Request $request) {
        $validator = Validator::make($request->all(), [
            'id'              =>  'required|exists:product_selected_users,id',
            'payment_id'      =>  'required|unique:product_selected_users',
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }
    
        $payment                  = ProductSelectedUser::where('id' , $request->id)->first();
        if( $payment) {
            $payment->payment_id  = $request->payment_id;
            $payment->save();
        }
        $success = true;
        return $this->sendResponse($success , 'data updated successfully.');
     }
}
