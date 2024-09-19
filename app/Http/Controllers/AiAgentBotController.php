<?php

namespace App\Http\Controllers;

use App\Models\AiAgent;
use App\Models\UserChat;
use Illuminate\Http\Request;

class AiAgentBotController extends Controller
{
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
    
    public function saveAgent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:ai_agents,email,' . $request->agent_id, 
            'phone_number' => 'required|digits_between:10,15|unique:ai_agents,phone_number,' . $request->agent_id,
        ]);
    
        $agent = AiAgent::find($request->agent_id) ?? new AiAgent();
        
        $agent->name = $validated['name'];
        $agent->email = $validated['email'];
        $agent->phone_number = $validated['phone_number'];
        $agent->save();
        
        return redirect()->route('agent')->with('message', 'Agent saved successfully!');
    }


    public function deleteAgent($id){
        $agent = AiAgent::find($id);
        $agent->delete();
        return redirect()->back()->with('message', 'Agent deleted successfully!');
    }
    
    
}
