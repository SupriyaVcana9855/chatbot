<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveAgentRequest;
use App\Models\AiAgent;
use App\Models\UserChat;
use Illuminate\Http\Request;

class AiAgentBotController extends Controller
{

    public function liveChatAgent(){
        return view('ai_agent.live_chat');
    }

        // Store new message
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
        

        // Fetch messages
        public function fetchMessages()
        {
            return AiAgent::where('user_id', auth()->id())->orWhere('agent_id', auth()->id())->get();
        }

    public function agents(){
        $agents = AiAgent::get(); 
        return view('ai_agent.ai_agent_listing',compact('agents'));
    }

    
    public function addAgentform($id = null)
    {
        // Find the agent if the ID is provided, otherwise create a new instance for adding a new agent
        $agent = $id ? AiAgent::find($id) : new AiAgent();
        
        // Pass the agent (or empty model if adding) to the view
        return view('ai_agent.add_agent', compact('agent'));
    }
    
    public function saveAgent(SaveAgentRequest $request)
    {
        $validated = $request->all();
        $agent = AiAgent::find($request->agent_id) ?? new AiAgent();
        $agent->name = $validated['name'];
        $agent->email = $validated['email'];
        $agent->phone_number = $validated['phone_number'];
        $agent->save();

        if($agent){
            return redirect()->route('agent')->with('success', 'Agent saved successfully.');
        }else{
            return redirect()->back()->with('error', 'Somthing went wrong.');
        }
    }


    public function deleteAgent($id){
        $agent = AiAgent::find($id);
        $agent->delete();
        return redirect()->back()->with('message', 'Agent deleted successfully!');
    }
    
    
}
