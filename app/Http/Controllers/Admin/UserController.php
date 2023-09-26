<?php

namespace App\Http\Controllers\Admin;

use DB;
use Hash;
use DataTables;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data     =  User::orderBy('id' , 'DESC')->get();
            return Datatables::of($data)->addIndexColumn()
                
                ->editColumn('status', function(User $data) {
                                
                    if($data->isVerified == 1) {
                        $btn =  '<div class="form-check form-switch form-switch-lg mb-3 col-md-2" dir="ltr">
                                   <input class="form-check-input update_status" data-id="'.$data->id.'" type="checkbox" id="SwitchCheckSizelg" name="status"  checked>
                                </div>';
                     }else {
                         $btn =  '<div class="form-check form-switch form-switch-lg mb-3 col-md-2" dir="ltr">
                                     <input class="form-check-input update_status" data-id="'.$data->id.'" type="checkbox" id="SwitchCheckSizelg" name="status" >
                                   </div>';
                     }
                     return $btn;
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('admin.users.index');
    }


    public function admins(Request $request)
    {

        if ($request->ajax()) {

            $data     =  Admin::orderBy('id' , 'DESC')->get();
            return Datatables::of($data)->addIndexColumn()
                
                ->editColumn('status', function(Admin $data) {
                                
                    if($data->status == 1) {
                        $btn =  '<div class="form-check form-switch form-switch-lg mb-3 col-md-2" dir="ltr">
                                   <input class="form-check-input update_status" data-id="'.$data->id.'" type="checkbox" id="SwitchCheckSizelg" name="status"  checked>
                                </div>';
                     }else {
                         $btn =  '<div class="form-check form-switch form-switch-lg mb-3 col-md-2" dir="ltr">
                                     <input class="form-check-input update_status" data-id="'.$data->id.'" type="checkbox" id="SwitchCheckSizelg" name="status" >
                                   </div>';
                     }
                     return $btn;
                })  ->addColumn('action', function(Admin $data){
                    $btn = '<a href="'.route('users.edit', $data->id).'" class="btn btn-primary btn-sm btn-rounded">'.trans('translation.edit').'</a>';
                    return $btn;
                })
                ->rawColumns(['status' , 'action'])
                ->make(true);
        }

        return view('admin.users.admins');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('admin.users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required|same:confirm-password',
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Admin::create($input);
       // $user->assignRole($request->input('roles'));
        return redirect()->route('admins.index')
                        ->with('success',trans('translation.data_addeded_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Admin::find($id);
       // $roles = Role::pluck('name','name')->all();
      //  $userRole = $user->roles->pluck('name','name')->all();
        return view('admin.users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        

        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'email' => 'required|email|unique:admins,email,'.$id,
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = bcrypt($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = Admin::find($id);
        $user->update($input);
      //  DB::table('model_has_roles')->where('model_id',$id)->delete();
       // $user->assignRole($request->input('roles'));
        return redirect()->route('admins.index')
                        ->with('success',trans('translation.data_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("admins")->where('id',$id)->delete();
        return redirect()->route('users.index')
                        ->with('success',trans('translation.data_deleted_successfully'));
    }

    public function updateUserStatus(Request $request) {
        $user = User::findOrFail($request->user_id);
        ($user->isVerified == 1) ?  $user->isVerified = 0  : $user->isVerified = 1;
        $user->save();
        return response()->json(array('msg'=> 'success'), 200);
    }

    public function updateAdminStatus(Request $request) {
        $user = Admin::findOrFail($request->user_id);
        ($user->status == 1) ?  $user->status = 0  : $user->status = 1;
        $user->save();
        return response()->json(array('msg'=> 'success'), 200);
    }

    public function editProfile (Admin $admin)
    {
        return view('admin.users.update_profile' , compact('admin'));
    }

    public function updateProfile(Request $request , Admin $admin)
    {
    
        $validator = Validator::make($request->all(), [
            'user_name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('admins', 'user_name')->ignore($admin->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin->id)],
            'photo'  => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $data   = $request->all();
        if (!empty($request->password)) {
            $data['password']   = bcrypt($request->password);
        }else {
            $data['password'] = $admin->password;
        }

        if(!empty($data['photo'])){
            $path = public_path('uploads/admins/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file                 = $request->file('photo');
            $fileName             = uniqid() . '_' . trim($file->getClientOriginalName());
            $data['photo']        = $fileName;
            $file->move($path, $fileName);
        }

        $admin->update($data);

        return redirect(route('admin.dashboard'))->with('success', trans('translation.data_updated_successfully'));
    }
}
