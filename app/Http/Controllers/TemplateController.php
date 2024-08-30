<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{

    
    public function templates(){
        return view('dashboard.template');
    }
    public function templateView(){
        $templates = Template::where('id' ,1)->first();
        //dd($templates);
        return view('dashboard.viewtemplate',compact('templates'));
    }

  
}
