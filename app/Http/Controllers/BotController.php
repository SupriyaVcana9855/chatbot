<?php

namespace App\Http\Controllers;
use App\Models\ChatBot;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function bots()
    {
        $bots = ChatBot::all();
        return view('bots.bots',compact('bots'));
    }
    public function agent()
    {
        return view('bots.ai-agent');
    }
    public function savebot(Request $request)
    {
        $bot = new ChatBot();
        $bot->name =$request->name;
        $bot->type =$request->type;

        $bot->save();
    }
}
