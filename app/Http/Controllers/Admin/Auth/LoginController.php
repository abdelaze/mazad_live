<?php

namespace App\Http\Controllers\Admin\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use Auth;
use Validator;
class LoginController extends Controller
{
    public function __construct() {
        $this->middleware('guest:admin')->except('logout');
    }      
    public function showAdminLoginForm() {

        return view('admin.auth.login', ['url' => 'admin']);
    }
    public function adminLogin(Request $request)
    {
        $this->validate($request, [ 
             'user_name'   => 'required',
             'password'    => 'required'
        ]);
        //dd($request->all());
        if (Auth::guard('admin')->attempt(['user_name' => $request->user_name, 'password' => $request->password , 'status' => 1], $request->get('remember'))) {
             return redirect()->intended('/admin/dashboard');
        } 
        return back()->withInput($request->only('user_name', 'remember'));
    }
   
}
