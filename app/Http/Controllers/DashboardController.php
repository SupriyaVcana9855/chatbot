<?php

namespace App\Http\Controllers;

use App\Models\Template;
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
        $templates = Template::get();
        return view('dashboard.viewtemplate',compact('templates'));
    }

}
