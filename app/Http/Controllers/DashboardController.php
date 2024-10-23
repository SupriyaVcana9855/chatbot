<?php

namespace App\Http\Controllers;

use App\Models\BotUser;
use App\Models\Template;
use App\Models\ChatBot;
use App\Models\QuestionAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
    


    // public function index()
    // {
    //     $getUserCount = User::count();
    //     $getBotCount = ChatBot::count();
    //     $getBotUserCount = BotUser::count();
    //     $getChatCount = QuestionAnswer::count();


     
    //     $timeFrames = [
    //         '5years' => Carbon::now()->subYears(5),
    //         'year' => Carbon::now()->subYear(),
    //         'month' => Carbon::now()->subDays(30),
    //         'week' => Carbon::now()->subDays(7),
    //     ];

    //     $chartData = [];

    //     foreach ($timeFrames as $key => $startDate) {
    //         $chartData[$key] = [
    //             'users' => User::where('created_at', '>=', $startDate)->get(),
    //             'bots' => ChatBot::where('created_at', '>=', $startDate)->get(),
    //             'bot_users' => BotUser::where('created_at', '>=', $startDate)->get(),
    //             // You might want to fetch more detailed data here if necessary
    //         ];
    //     }

    //     // Prepare the data for the chart

    //     $chartDataJson = json_encode($chartData);
    //     return view('dashboard.dashboard',compact('getUserCount','getBotCount','getChatCount','getBotUserCount','chartDataJson'));
    // }



//     public function index()
// {
//     $getUserCount = User::count();
//     $getBotCount = ChatBot::count();
//     $getBotUserCount = BotUser::count();
//     $getChatCount = QuestionAnswer::count();

//     $timeFrames = [
//         '5years' => Carbon::now()->subYears(5),
//         'year' => Carbon::now()->subYear(),
//         'month' => Carbon::now()->subDays(30),
//         'week' => Carbon::now()->subDays(7),
//     ];

//     $chartData = [];

//     foreach ($timeFrames as $key => $startDate) {
//         $chartData[$key] = [
//             'users' => User::where('created_at', '>=', $startDate)->count(),
//             'bots' => ChatBot::where('created_at', '>=', $startDate)->count(),
//             'bot_users' => BotUser::where('created_at', '>=', $startDate)->count(),
//         ];
//     }

//     // dd($chartData);

//     // Convert chart data to JSON
//     $chartDataJson = json_encode($chartData);
//     return view('dashboard.dashboard', compact('getUserCount', 'getBotCount', 'getChatCount', 'getBotUserCount', 'chartDataJson'));
// }

public function index()
{
    // Total counts
    $getUserCount = User::count();
    $getBotCount = ChatBot::count();
    $getBotUserCount = BotUser::count();
    $getChatCount = QuestionAnswer::count();

    // Define time frames for chart data
    $timeFrames = [
        '5years' => Carbon::now()->subYears(5),
        'year' => Carbon::now()->subYear(),
        'month' => Carbon::now()->subDays(30),
        'week' => Carbon::now()->subDays(7),
    ];

    $chartData = [];

    foreach ($timeFrames as $key => $startDate) {
        // Group users by date and count for each time frame
        $usersByDate = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date'); // Return key-value pairs of 'date' => 'count'

        // Group bots by date and count for each time frame
        $botsByDate = ChatBot::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');
        // dd($botsByDate);
        // Group bot users by date and count for each time frame
        $botUsersByDate = BotUser::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');
           
        // Store the data for the chart
        $chartData[$key] = [
            'users' => $usersByDate,
            'bots' => $botsByDate,
            'bot_users' => $botUsersByDate,
        ];
    }
    

    // Convert chart data to JSON
    $chartDataJson = json_encode($chartData);

    // Pass data to the view
    return view('dashboard.dashboard', compact('getUserCount', 'getBotCount', 'getChatCount', 'getBotUserCount', 'chartDataJson'));
}




public function chatanalytics($id)
{
    // Define time frames for chart data
    $timeFrames = [
        '5years' => Carbon::now()->subYears(5),
        'year' => Carbon::now()->subYear(),
        'month' => Carbon::now()->subDays(30),
        'week' => Carbon::now()->subDays(7),
    ];

    $chartData = [];

    foreach ($timeFrames as $key => $startDate) {
        // Group bot users by date and count for each time frame
        $botUsersByDate = BotUser::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->where('chat_bot_id', $id)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');
        
        // Store the data for the chart
        $chartData[$key] = [
            'categories' => $botUsersByDate->keys(), // dates (categories for x-axis)
            'counts' => $botUsersByDate->values(), // counts (data for y-axis)
        ];
    }

    // Convert chart data to JSON
    $chartDataJson = json_encode($chartData);

    return view('dashboard.chatanalytics', compact('chartDataJson'));
}

}
