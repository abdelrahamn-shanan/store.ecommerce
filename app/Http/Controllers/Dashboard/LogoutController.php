<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class LogoutController extends Controller
{
    public function logout()
    {
        auth()->logout(); 
       return redirect()->route('admin.login');
    }
}
