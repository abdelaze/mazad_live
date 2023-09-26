<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Label84\HoursHelper\Facades\HoursHelper;

class DashboardController extends Controller
{
    public function index(){
        return view('admin.dashboard');
    }
    public function logout() {
        auth()->guard('admin')->logout();
        return redirect(route('get.admin.login'));
    }
}
