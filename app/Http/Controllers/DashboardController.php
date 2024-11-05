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
    // Total counts
    $getUserCount = User::where('role','!=','1')->count();
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
            ->where('role','!=','1')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date'); // Return key-value pairs of 'date' => 'count'
// dd( $usersByDate);
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
