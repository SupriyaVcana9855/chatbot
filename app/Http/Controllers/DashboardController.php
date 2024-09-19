<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\ChatBot;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{

    public function welcome()
    {
        return view('welcome');
    }

    // public function sendMessage(Request $request)
    // {

    //     try {
    //          // Get the message and other necessary data from the request
    //     $message = $request->input('message');
    //     $mobile = "8557068128"; // Replace with actual mobile number or get from request if dynamic
    //     $template_id = "1234"; // Replace with actual template ID
    //     $auth_key = "430560AlWE3IZgFi366e983edP1"; // Replace with actual MSG91 auth key
    
    //     // Prepare the data to send in the cURL request
    //     $postData = [
    //         'template_id' => $template_id,
    //         'short_url' => '1', // Set as needed (1 for On, 0 for Off)
    //         'realTimeResponse' => '1', // Optional, set based on your needs
    //         'recipients' => [
    //             [
    //                 'mobiles' => $mobile,
    //                 'VAR1' => $message,
    //                 'VAR2' => 'VALUE 2', // Replace with actual value or get from request
    //             ]
    //         ]
    //     ];
    
    //     // Initialize cURL
    //     $curl = curl_init();
    
    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => "https://control.msg91.com/api/v5/flow",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => json_encode($postData),
    //         CURLOPT_HTTPHEADER => [
    //             "accept: application/json",
    //             "authkey: $auth_key",
    //             "content-type: application/json"
    //         ],
    //     ]);
    
    //     // Execute the request
    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);
    
    //     // Close cURL
    //     curl_close($curl);
    
    //     // Handle the response or error
    //     if ($err) {
    //         return response()->json(['error' => "cURL Error: $err"], 500);
    //     } else {
    //         return response()->json(['response' => json_decode($response)], 200);
    //     }
    //     } catch (\Exception $e) {
    //         dd($e);
    //     }
       
    // }
    
    public function sendMessage(Request $request)
    {
        try {
           
            $message = $request->input('message');
            $mobile = $request->input('mobile', '918557068128'); 
            $template_id = 'msg91'; 
            $auth_key = '430560AlWE3IZgFi366e983edP1'; 
    
            // Prepare the data to send in the cURL request
            $postData = [
                'template_id' => $template_id,
                'short_url' => '1', // Set as needed (1 for On, 0 for Off)
                'realTimeResponse' => '1', // Optional, set based on your needs
                'recipients' => [
                    [
                        'mobiles' => $mobile,
                        'VAR1' => $message,
                        'VAR2' => 'VALUE 2', // Replace with actual value or get from request
                    ]
                ]
            ];
    
            // Initialize cURL
            $curl = curl_init();
    
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://control.msg91.com/api/v5/flow",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => [
                    "accept: application/json",
                    "authkey: $auth_key",
                    "content-type: application/json"
                ],
            ]);
    
            // Execute the request
            $response = curl_exec($curl);
            $err = curl_error($curl);
    
            // Close cURL
            curl_close($curl);
    
            // Handle the response or error
            if ($err) {
                return response()->json(['error' => "cURL Error: $err"], 500);
            } else {
                $decodedResponse = json_decode($response, true);
    
                if (isset($decodedResponse['type']) && $decodedResponse['type'] === 'error') {
                    return response()->json(['error' => $decodedResponse['message']], 400);
                }
    
                return response()->json(['response' => $decodedResponse], 200);
            }
        } catch (\Exception $e) {
            // Handle exceptions properly by logging and returning a meaningful message
            \Log::error('Error in sendMessage: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while sending the message.'], 500);
        }
    }
    


    public function index()
    {
        return view('dashboard.dashboard');
    }

    public function templates()
    {
        return view('dashboard.template');
    }


    public function templateView($id)
    {
        $templates = Template::where('id', $id)->first();
        return view('dashboard.viewtemplate', compact('templates'));
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
                'option_border_color' => $tempData['option_border_color'] ?? null,
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


    public function chatanalytics()
    {
        return view('dashboard.chatanalytics');
    }
}
