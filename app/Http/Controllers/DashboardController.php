<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard');
    }

    public function templates(){
        return view('dashboard.template');
    }

    
    public function templateView(){
        return view('dashboard.viewtemplate');
    }

}
