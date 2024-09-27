<?php
namespace App\Http\Controllers;
use App\Models\ChatBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

class BotController extends Controller
{
    public function setup($id)
    {
        $scriptId = 2; 
        $bot = ChatBot::find($id);
        return view('bots.setup',compact('id','bot','scriptId'));
    }
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
        // dd( $request->all());
        $bot = new ChatBot();
        $bot->name =$request->name;
        $bot->type =$request->type;
        $bot->user_id = Auth::user()->id;
        $bot->save();

        $baseUrl = url('/scriptchatbots');

        ChatBot::where('id',$bot->id)
            ->update(['script_link'=>'<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script><script src="'.$baseUrl.'/'.$bot->id.'"></script>
        ']);

        if($bot){
            return redirect()->back()->with('success', 'Data saved successfully.');
        }else{
            return redirect()->back()->with('error', 'Somthing went wrong.');
        }
    }
   
    public function updateBot(Request $request)
    {
        // dd($request->all());
        $formType = $request->input('form_type');
        $bot = ChatBot::find($request->bot_id);
        $radiusValueData = '';

         // If radius is set, append a percentage sign
        $radiusValue = $request->input('radius');
        if ($radiusValue !== null) {
            $radiusValueData = $radiusValue . '%';  // Append the percentage symbol
        }
        switch ($formType) {
            case 'form1':
                $bot->name = $request->name;
                $bot->intro_message = $request->intro_message;
                $bot->description = $request->description;
                $bot->font = $request->font;
                $bot->font_size = $request->font_size;
                break;
            case 'form2':
                $request->validate([
                    'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                ]);
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $path = $file->store('public/images/setup');
                    $bot->logo = $path;
                } elseif ($request->filled('selected_avatar')) {
                    $bot->logo = $request->selected_avatar; 
                }
                break;
            case 'form3':
                $bot->text_alignment = $request->text_alignment;
                $bot->bot_position = $request->position;
                $bot->radius = $radiusValueData;
                $bot->button_design = $request->button_design;
                break;
            case 'form4':
                $bot->main_color = $request->header_color;
                $bot->header_color = $request->header_color;
                $bot->background_color = $request->background_color;
                $bot->question_color = $request->question_color;
                $bot->answer_color = $request->answer_color;
                $bot->option_color = $request->option_color;
                $bot->option_border_color = $request->option_border_color;
                break;
        }
        $bot->save();

    return redirect()->back()->with('success', 'Bot updated successfully!');
}

public function deleteBot($id){
    $bot = ChatBot::find($id);
    $bot->delete();
    return redirect()->back()->with('message', 'Bot deleted successfully!');
}
    
}
