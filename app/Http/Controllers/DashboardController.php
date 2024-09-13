<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\ChatBot;
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

    
    public function templateView($id){
        $templates = Template::where('id',$id)->first();
        return view('dashboard.viewtemplate',compact('templates'));
    }

    public function addBotTemplate(Request $request)
    {
        $tempData = json_decode($request->tempData, true);
    
        if (is_array($tempData)) {
            $chatBot = ChatBot::create([
                'name'              => $request->bot_name, 
                'type'              => $tempData['type'] ?? null, // Assuming type is part of tempData
                'intro_message'     => $tempData['intro_message'] ?? null, 
                'main_color'        => $tempData['main_color'] ?? null, 
                'bubble_background' => $tempData['bubble_background'] ?? null, 
                'logo'              => $tempData['logo'] ?? null, 
                'description'       => $tempData['description'] ?? null, 
                'font'              => $tempData['font'] ?? null, 
                'font_size'         => $tempData['font_size'] ?? null, 
                'bot_position'      => 'left', 
                'message_bubble'    => $tempData['message_bubble'] ?? null, 
                'radius'            => $tempData['question_radius'] ?? null, 
                'text_alignment'    => $tempData['text_alignment'] ?? null, 
                'question_color'    => $tempData['question_color'] ?? null, 
                'answer_color'      => $tempData['answer_color'] ?? null, 
                'status'            => $tempData['status'] ?? null, 
                'header_color'      => $tempData['header_color'] ?? null, 
                'background_color'  => $tempData['background_color'] ?? null, 
                'option_color'      => $tempData['option_color'] ?? null, 
                'option_border_color'=> $tempData['option_border_color'] ?? null, 
                'button_design'     => $tempData['button_type'] ?? null, 
                // 'button_type'       => $tempData['button_type'] ?? null,
            ]);
    
            // Return success or redirect
            return response()->json(['success' => true, 'data' => $chatBot], 200);
        } else {
            // If the tempData is not an array or cannot be decoded
            return response()->json(['error' => 'Invalid data format'], 400);
        }
    }

    
    public function chatanalytics(){
        return view('dashboard.chatanalytics');
    }

    
}
