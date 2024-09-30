<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\ChatBot;
use Illuminate\Http\Request;
use Auth;
use Twilio\Rest\Client;

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
        // Validate the incoming request
        $request->validate([
            'to' => 'required|string', // Ensure the 'to' number is provided
            'message' => 'required|string', // Ensure the message is provided
        ]);
    
        // Get Twilio credentials from the .env file
        $twilioSid = env('TWILIO_SID');
        $twilioAuthToken = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
    
        // Create a Twilio client
        $client = new Client($twilioSid, $twilioAuthToken);
    
        // Send the WhatsApp message
        // try {
        //     $client->messages->create(
        //         'whatsapp:' . $request->input('to'), // Use 'whatsapp:' prefix for the number
        //         [
        //             'from' => 'whatsapp:' . $twilioWhatsAppNumber, // Use 'whatsapp:' for the Twilio number
        //             'body' => $request->input('message'),
        //         ]
        //     );
    
        //     return response()->json(['status' => 'Message sent successfully!'], 200);
        // } catch (\Exception $e) {
        //     // Log error details for troubleshooting
        //     dd($e);
            
        //     return response()->json(['error' => 'Failed to send message: ' . $e->getMessage()], 500);
        // }
    }
    


    public function index()
    {
        return view('dashboard.dashboard');
    }


    public function chatanalytics()
    {
        return view('dashboard.chatanalytics');
    }
}
