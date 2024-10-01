<?php
namespace App\Http\Controllers;
use App\Models\ChatBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

class BotController extends Controller
{


    public function userbots()
    {
        $bots = ChatBot::where('user_id',Auth::user()->id)->get();
        return view('bots.bots',compact('bots'));
    }
    public function setup($id)
    {
        // dd($id);
        $scriptId = $id; 
        $bot = ChatBot::find($id);
        return view('bots.setup',compact('id','bot','scriptId'));
    }
    public function bots()
    {
        if(Auth::user()->role == 1)
        {
            $bots = ChatBot::all();
        }else
        {
            $bots = ChatBot::where('user_id',Auth::user()->id)->get();
        }
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
    
        // Validate input data
        // $validated = $request->validate([
        //     'name' => 'required|unique:chat_bots,name,' . $request->bot_id . '|max:20|min:3', // Ignore the current bot name during unique check
        //     'font' => 'required',
        //     'font_size' => 'required',
        // ], [
        //     'name.required' => 'The bot name is required.',
        //     'name.unique' => 'This bot name is already taken. Please choose another one.',
        //     'name.max' => 'The bot name cannot exceed 20 characters.',
        //     'name.min' => 'The bot name must be at least 3 characters.',
        //     'font.required' => 'Please select a font for the bot.',
        //     'font_size.required' => 'Please select a font size for the bot.',
        // ]);

    // Retrieve the bot by its ID
    $bot = ChatBot::find($request->bot_id);

    // Check if bot exists
    if (!$bot) {
        return redirect()->back()->withErrors('Bot not found. Please check the ID and try again.');
    }

    $formType = $request->input('form_type');
    $radiusValueData = '';

    // If radius is set, append a percentage sign
    $radiusValue = $request->input('radius');
    if ($radiusValue !== null) {
        $radiusValueData = $radiusValue . '%';  // Append the percentage symbol
    }
    // Update bot data based on form type
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

    // Save the updated bot data
    $bot->save();
    if ($bot) {
        return redirect()->route('bots')->with('success', 'Bot updated successfully.');
    } else {
        return redirect()->back()->with('error', 'Something went wrong.');
    }
}

   
//     public function updateBot(Request $request)
//     {
//         $validated = $request->validate([
//             'name' => 'required|unique:chat_bots,name|max:20|min:3',
//             'font' => 'required',
//             'font_size' => 'required',
//         ], [
//             'name.required' => 'The bot name is required.',
//             'name.unique' => 'This bot name is already taken. Please choose another one.',
//             'name.max' => 'The bot name cannot exceed 20 characters.',
//             'name.min' => 'The bot name must be at least 3 characters.',
//             'font.required' => 'Please select a font for the bot.',
//             'font_size.required' => 'Please select a font size for the bot.',
//         ]);
        
//         // dd($request->all());
//         $formType = $request->input('form_type');
//         $bot = ChatBot::find($request->bot_id);
//         $radiusValueData = '';

//          // If radius is set, append a percentage sign
//         $radiusValue = $request->input('radius');
//         if ($radiusValue !== null) {
//             $radiusValueData = $radiusValue . '%';  // Append the percentage symbol
//         }
//         switch ($formType) {
//             case 'form1':
//                 $bot->name = $request->name;
//                 $bot->intro_message = $request->intro_message;
//                 $bot->description = $request->description;
//                 $bot->font = $request->font;
//                 $bot->font_size = $request->font_size;
//                 break;
//             case 'form2':
//                 $request->validate([
//                     'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
//                 ]);
//                 if ($request->hasFile('image')) {
//                     $file = $request->file('image');
//                     $path = $file->store('public/images/setup');
//                     $bot->logo = $path;
//                 } elseif ($request->filled('selected_avatar')) {
//                     $bot->logo = $request->selected_avatar; 
//                 }
//                 break;
//             case 'form3':
//                 $bot->text_alignment = $request->text_alignment;
//                 $bot->bot_position = $request->position;
//                 $bot->radius = $radiusValueData;
//                 $bot->button_design = $request->button_design;
//                 break;
//             case 'form4':
//                 $bot->main_color = $request->header_color;
//                 $bot->header_color = $request->header_color;
//                 $bot->background_color = $request->background_color;
//                 $bot->question_color = $request->question_color;
//                 $bot->answer_color = $request->answer_color;
//                 $bot->option_color = $request->option_color;
//                 $bot->option_border_color = $request->option_border_color;
//                 break;
//         }
//         $bot->save();

//     return redirect()->back()->with('success', 'Bot updated successfully!');
// }

public function deleteBot($id){
    $bot = ChatBot::find($id);
    $bot->delete();
    return redirect()->back()->with('message', 'Bot deleted successfully!');
}
    
}
