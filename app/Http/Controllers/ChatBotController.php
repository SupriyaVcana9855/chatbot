<?php

namespace App\Http\Controllers;

use App\Models\BotQuestion;
use App\Models\BotQuestionFlow;
use Illuminate\Http\Request;
use App\Models\ChatBot;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\BotUser;
use App\Models\NewQuestion;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use App\Helpers\Helper;

class ChatBotController extends Controller
{

    public function botChat($id)
    {
        // $botChats = ChatBot::with('botQuestions','botQuestions.questionAnswers')->where('id',$id)->get();
        // $botChats = BotUser::with('questionAnswer', 'questionAnswer.botQuestion', 'questionAnswer.chatBots')->where('id', $id)->get();
        if(Auth::user()->role == 1){
            $botChats = BotUser::with('bot', 'questionAnswer','questionAnswer.botQuestion')->where('chat_bot_id', $id)->get();
        }else{
            $botChats = BotUser::with('bot', 'questionAnswer','questionAnswer.botQuestion')->where('chat_bot_id', $id)->get();
        }

        return view('bots.bot-chat', compact('botChats'));
    }

    public function getBotChatData($id)
    {
       
        $botChatData = BotUser::with('bot', 'questionAnswer','questionAnswer.botQuestion','lastResponse')->where('id', $id)->get();
        // dd($botChatData);
        return response()->json(['data' => $botChatData]);
        
    }

    //single bot question listing

    public function singleBotListing($id)
    {
    
        $bots = BotQuestion::where('chat_bot_id', $id)->get();
        $sequence = BotQuestion::select('sequence')->where('chat_bot_id', $id)->get();

        $questionFlowIds = BotQuestionFlow::pluck('bot_question_id2')->toArray();
        $questionsNotInFlow = BotQuestion::where('chat_bot_id', $id)
            ->whereNotIn('id', $questionFlowIds)
            ->get();

        return view('bots.single-bot-listing', compact('bots', 'id', 'questionsNotInFlow'));
    }
   
    public function botQuestion($bot_id, $question_id = null)
    {
        $chatBot = ChatBot::find($bot_id);
        $botQuestions = BotQuestion::find($question_id);
        if ($botQuestions && is_string($botQuestions->options)) {
            $botQuestions->options = json_decode($botQuestions->options, true);
        }
        return view('bots/bot-question', compact('chatBot', 'botQuestions'));
    }
    public function questionsDelete($id)
    {
        $questionsDelete = BotQuestion::find($id);
        $questionsDelete->delete();
        return redirect()->back()->with('success', 'Question deleted successfully.');
    }

    
    public function downloadHistoryPdf($id)
    {
   
        $data = BotUser::with('bot', 'questionAnswer','questionAnswer.botQuestion','lastResponse')
                ->where('id', $id)
                ->get();
            if ($data->isEmpty()) {
                return back()->withErrors('No data found for the selected user.');
            }

        $pdf = Pdf::loadView('bots.chat-history', compact('data'));
        return $pdf->download('chat-history.pdf');
    }

    public function addQuestion(Request $request)
    {
        $newquestion = new NewQuestion();
        $newquestion->question = $request->question;
        $newquestion->chat_bot_id = $request->chat_bot_id;
        $newquestion->option_id = $request->option_id ?? 0;
        $newquestion->parent_id = $request->parent_id ?? 0;
        $newquestion->save();
        $optionIds = [];
        foreach ($request->option as $option) {
            $questionoption = new QuestionOption();
            $questionoption->option = $option;
            $questionoption->bot_question_id = $newquestion->id;
            $questionoption->save();
            $optionIds[] = $questionoption->id;
        }
        $data = [
            'parent_id' => $newquestion->id,
            'option_ids' => $optionIds,
            'chat_bot_id' => $request->chat_bot_id,
        ];
        return response()->json(['data' => $data], 200);
    }


    public function getQuestion($botId)
    {
        $question = BotQuestion::where('bot_id', $botId)->first();
        if ($question) {
            return response()->json([
                'question' => $question->question,
                'options' => $question->answer // Assuming options are stored in the database
            ]);
        }
        return response()->json(['message' => 'No more questions available.'], 404);
    }

    public function show($id)
    {
        $bot = ChatBot::find($id);
        return view('welcome', compact('bot'));
    }


    public function websiteChat()
    {
        return view('bots.website-bot');
    }



    public function handleMessage(Request $request)
    {

        $message = $request->input('message');
        $botId = $request->input('bot_id');
        $question = BotQuestion::find($botId);
        $bot = ChatBot::find($request->chatbotId);
        $reply = Helper::getData($message,$bot,$request);
        if(count($reply))
        {
            return response()->json(['reply' => $reply]);
        }
        else {

             $reply = Helper::generateReply($message,$bot,$question,$request);
        }
        return response()->json(['reply' => $reply]);
    }
    // public function getData($message, $bot)
    // {
    //     if ($message == 'schedule a meeting') {
    //         $url = '<a target="_blank" href="https://calendly.com/anshul_seo/30min?month=2024-09">click here to schedule a meeting</a>';
    //         $data = [
    //             'message' => $url,
    //             'question_id' => 0,
    //             'chat_bot_type' => $bot->type,
    //         ];
    //         return $data;
    //     } else if ($bot->type != 'lead' && $message == 'chat with live agent') {
    //         $data = [
    //             'message' => "Let me check if any agent is available for you....please wait.",
    //             'question_id' => 0,
    //             'chat_bot_type' => $bot->type,
    //         ];
    //         return $data;
    //     } elseif ($message == 'exit') {
    //         $data = [
    //             'message' => "Thanx for the information we will contact you soon.......",
    //             'question_id' => 0,
    //             'chat_bot_type' => $bot->type,
    //         ];
    //         return $data;
    //     } else {
    //       return $data = [];
    //     }
    // }

    // private function generateReply($message, $bot, $question, $request)
    // {
    //     $coloum = '';
    //     if ($question) {
    //         if ($question->answer_type == 'email') {
    //             if (!preg_match('/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/', $message)) {
    //                 $data = [
    //                     'message' => "Enter a valid email!",
    //                     'question_id' => $question->id
    //                 ];
    //                 return $data;
    //             }
    //             $coloum = 'email';
    //         } else if ($question->answer_type == 'contact') {
    //             if (!preg_match('/^\+?[0-9]{10,15}$/', $message)) {
    //                 $data = [
    //                     'message' => "Enter a valid phone number!",
    //                     'question_id' => $question->id
    //                 ];
    //                 return $data;
    //             }
    //             $coloum = 'contact';
    //         } else if ($question->answer_type == 'name') {
    //             if (!preg_match('/^[\p{L} ]+$/u', $message)) {

    //                 $data = [
    //                     'message' => "Enter a valid name!",
    //                     'question_id' => $question->id
    //                 ];
    //                 return $data;
    //             }
    //             $coloum = 'name';
    //         } else {
    //             if (!preg_match('/./', $message)) { // Simple check for non-empty string
    //                 $data = [
    //                     'message' => "Enter a valid data!",
    //                     'question_id' => $question->id
    //                 ];
    //                 return $data;
    //             }
    //         }
    //     }

    //     $botUserData = BotUser::find($request->bot_user_id);
    //     if($request->bot_id !='0')
    //     {
    //         if (!$botUserData) {
    //             $botUserData = new BotUser;
    //             $botUserData->chat_bot_id = $bot->id;
    //             $botUserData->save();
    //         } else {
    //             if ($coloum != '') {
    //                 $botUserData->$coloum = $message;
    //                 $botUserData->save();
    //             }
    //         }
    //     }
    //     $saveanswer = new QuestionAnswer;
    //     $saveanswer->bot_question_id = ($question) ? $question->id : '0';
    //     $saveanswer->answer = $message;
    //     $saveanswer->user_id = 1; //chat bot ka malik 
    //     $saveanswer->chat_bot_id = $bot->id;
    //     $saveanswer->status = '1';
    //     $saveanswer->bot_user_id = ($botUserData)?$botUserData->id:$request->bot_user_id; // kon chat krne aaya
    //     $saveanswer->save();

    //     $questionsIds = QuestionAnswer::where('chat_bot_id', $bot->id)
    //         ->where('status', '1')
    //         ->where('bot_question_id', '!=', '0')
    //         ->pluck('bot_question_id')
    //         ->toArray();
    //     $questionsIds = array_unique($questionsIds);
    //     if ($bot->type == 'lead') {
    //         if($request->option_id != '')
    //         {
    //             $questions = BotQuestion::where(function ($query) use ($bot, $request) {
    //                 // Match chat_bot_id with the specific bot id or global (0)
    //                 $query->where('chat_bot_id', $bot->id)
    //                     ->orWhere('chat_bot_id', 0);
    //                     })
    //             ->whereNotIn('id', $questionsIds)
    //             ->where('option_id',$request->option_id) 
    //             ->first();
    //         }else{
    //             $questions = BotQuestion::where(function ($query) use ($bot,$request) {
    //                 $query->where('chat_bot_id', $bot->id)
    //                     ->orWhere('chat_bot_id', 0);
    //             })
    //             ->whereNotIn('id', $questionsIds)
    //             ->first();
    //         }
    //     } else {

    //         $data = BotQuestion::where(function ($query) use ($bot) {
    //             $query->Where('chat_bot_id', 0);
    //         })
    //         ->whereNotIn('id', $questionsIds)
    //         ->first();
        

    //         if ($data) {
    //             $length = 0;
    //             $questions = $data;
    //         } else {
    //             $length = 1;
    //             $questions = BotQuestion::where(function ($query) use ($bot) {
    //                 $query->where('chat_bot_id', $bot->id);
    //             })
    //             ->whereNotIn('id', $questionsIds)
    //             ->get();
    //         }
    //     }
    //     $getAllOptions = '';
    //     if ($questions) {
    //         $arr = [];
    //         if ($bot->type == 'lead') {
    //             $questionNew = $questions->question;
    //             $getAllOptions = QuestionOption::where('bot_question_id', $questions->id)
    //             ->pluck('id');
    //             $optionNew = ($questions->options) ? $questions->options : null;
              

    //             $questionId = $questions->id;
    //         } else {
    //             $questionId=[];
    //             if ($length > 0) {
    //                 if ($message == 'schedule a meeting') {
    //                     //add anchor tag linkis not working
    //                     $url = '<a href="https://calendly.com/anshul_seo/30min?month=2024-09">click here to schedule a meeting</a>';
    //                     $data = [
    //                         'message' => $url,
    //                         'question_id' => 0,
    //                     ];
    //                     return $data;
    //                 } else if ($message == 'chat with live agent') {

    //                     // add twilio acount and end message to the livwe agent using agent function.
    //                     $data = [
    //                         'message' => "Let me check if any agent is available for you....please wait.",
    //                         'question_id' => 0,
    //                     ];
    //                     return $data;
    //                 } elseif ($message == 'exit') {
    //                     $data = [
    //                         'message' => "Thanx for the information we will contact you soon...",
    //                         'question_id' => 0,
    //                     ];
    //                     return $data;
    //                 } else {

    //                     $botAnswer = BotQuestion::where('question', 'LIKE', '%' . $message . '%')->first();
                      

    //                     $questionNew = ($botAnswer) ? $botAnswer->answer : '' . '<br><br>Please select to know more about our website.....';

    //                     foreach ($questions as $ques) {
    //                         //will add the question only

    //                         $arr[] = $ques->question;
    //                         $questionId[] = $ques->id;
    //                     }
    //                 // dd($questionId);

    //                     $optionNew = $arr;
    //                     if (!count($optionNew)) {
    //                         if($botAnswer)
    //                         {
    //                             if($botAnswer->answer)
    //                             {
    //                                 $optionNew = array('Please select to know more about our website.....','schedule a meeting', 'chat with live agent');
    //                             }else
    //                             {
    //                                 $optionNew = array('schedule a meeting', 'chat with live agent');
    //                             }
    //                         }else
    //                         {
    //                             $optionNew = array('schedule a meeting', 'chat with live agent');
    //                         }
                           
    //                     }
    //                 }
    //             } else {
    //                 // dd('dsfsdfdsfgd');
    //                 $questionNew = $questions->question;
    //                 $optionNew = ($questions->options) ? $questions->options : null;
    //                 $questionId = $questions->id;
    //             }
    //         }
    //       if($optionNew)
    //       {
    //         array_push($optionNew, "exit");
    //       }
    //       if($questions)
    //       {
    //         $newQuestionId = $questionId;
    //       }else
    //       {
    //         $newQuestionId = '';
    //       }
    //         $data = [
    //             'message' => $questionNew,
    //             'question_id' => ($questions->count() > 0) ? $questionId : '',
    //             'bot_user_id' => ($botUserData)?$botUserData->id:'',
    //             'chat_bot_type' => $bot->type,
    //             'options' =>  $optionNew,
    //             'questions' => $questions,
    //             'question_option_ids' => ($getAllOptions) ?$getAllOptions:$newQuestionId,
    //         ];
    //     } else {

    //         if ($message == 'schedule a meeting') {
    //             //add anchor tag linkis not working
    //             $url = '<a href="https://calendly.com/anshul_seo/30min?month=2024-09">click here to schedule a meeting</a>';
    //             $data = [
    //                 'message' => $url,
    //                 'question_id' => 0,
    //             ];
    //         } else if ($message == 'chat with live agent') {
    //             $data = [
    //                 'message' => "Let me check if any agent is available for you....please wait.",
    //                 'question_id' => 0,
    //             ];
    //         } elseif ($message == 'exit') {
    //             $data = [
    //                 'message' => "Thanx for the information we will contact you soon.......",
    //                 'question_id' => 0,
    //                 'chat_bot_type' => $bot->type,
    
    //             ];
    //             return $data;
    //         } else {
    //             if($bot->type == 'lead')
    //             {
    //                 $optionNew = array('schedule a meeting','exit');

    //             }else{
    //                 $optionNew = array('schedule a meeting');

    //             }
    //             $data = [
    //                 'message' => "Please Select from following to know more about us...",
    //                 'question_id' => 0,
    //                 'chat_bot_type' => $bot->type,
    //                 'options' =>  $optionNew,
    
    //             ];
    //             return $data;
    //         }
    //     }
    //     return $data;
    // }


    public function scriptchatbots($id)
    {
        $chatbot = ChatBot::find($id);
        $questionsIds = QuestionAnswer::pluck('bot_question_id')->where('chat_bot_id ', $id)->where('status ', '0')->toArray();
        $questions = BotQuestion::where('chat_bot_id', $id)
            ->orWhere('chat_bot_id', 0)
            ->whereNotIn('id', $questionsIds)
            ->first();
        if (!$chatbot) {
            return response('Chatbot not found', 404);
        }
        if (str_starts_with($chatbot->logo, 'public/')) {
            $logoUrl = Storage::url($chatbot->logo);
        } else {
            $logoUrl = $chatbot->logo;
        }
        $csrfToken = csrf_token();
        $chatbot = ChatBot::find($id);
        if (!$chatbot) {
            return response('Chatbot not found', 404);
        }
        $csrfToken = csrf_token();
        $script = "(function() {
            // Function to inject the chatbot HTML and CSS into the page
            function injectChatbot() {
                const chatbotContainer = document.createElement('div');
                chatbotContainer.id = 'chatbotContainer';
                chatbotContainer.innerHTML = `
                    <meta charset='UTF-8'>
                            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <meta name='csrf-token' content='{{ csrf_token() }}'>
                            <title>Chatbot</title>
                            <body>
                                <input type='hidden' name='bot_type' value='" . $chatbot->type . "' class='bot_type'>

                             <input type='hidden' name='chat_bot_id' value='" . $chatbot->id . "' class='chat_bot_id'>
                                <input type='hidden' name='selected_option_id' value='' class='selected_option_id'>
                                <input type='hidden' name='bot_user_id' value='' class='bot_user_id'>
                                <div class='chat-toggle chat-boat-position chat-boat-position-" . $chatbot->bot_position . "' id='chatMessages'>
                                    <img src='" . $logoUrl . "' alt='Chat Icon' id='chat-toggle-btn'>
                                </div>
                                <div class='chat-container chat-boat-position chat-boat-position-" . $chatbot->bot_position . "' id='chat-container'>
                                    <div class='chat-header'>
                                        <div class='chat-img'>
                                            <img src='" . $logoUrl . "' alt='Chat Icon' id='chat-toggle-btn'>
                                        </div>
                                        <div>

                                            <div class='chat-title'>" . htmlspecialchars($chatbot->name) . "</div>
                                            <div class='chat-subtitle'>" . htmlspecialchars($chatbot->type) . "</div>
                                        </div>
                                        <div class='icon-head'>
                                            <div>
                                                <img src='" . asset('assets/images/reload.png') . "'>
                                            </div>
                                            <div class='closeicon'>
                                                <img src='" . asset('assets/images/close.png') . "' id='close-chat-icon'>
                                            </div>
                                            <div>
                                                <img class='download-history' src='".asset('assets/images/download.png')."' style='width: 13px; margin-left: 10px;' data-id='$chatbot->id'>
                                            </div>

                                        </div>
                                    </div>
                                    <div class='chat-body'>
                                        <div class='message bot'>
                                            <div class='text'><h4 style='font-size: 16px;
                                                font-weight: 600'>" . htmlspecialchars($chatbot->intro_message) . "</h4></div>
                                        </div>
                                        " . ($questions ? "
                                        <div class='message bot'>
                                            <div class='text'>" . htmlspecialchars($questions->question) . "</div>
                                                <input type='hidden' name='question_id' value='" . $questions->id . "' class='question_id'>
                                        </div>" : "") . "
                                         " . ($questions && $questions->options ? "
                                        <div class='chat-btn'>
                                            <button class='option1Select' value='" . htmlspecialchars($questions->options) . "'>" . htmlspecialchars($questions->options) . "</button>
                                        </div>" : "") . "
                                    </div>
                                    <div class='chat-footer'>
                                        <input type='text' id='userMessage' placeholder='Enter your message...'>
                                        <button><img src='" . asset('assets/images/fileupload.png') . "' /></button>
                                        <button id='sendButton'><img src='" . asset('assets/images/Vector.png') . "' /></button>
                                    </div>
                                </div>
                `;
        
                const style = document.createElement('style');
                style.innerHTML = `
                                    .chat-boat-position {
                                    position: fixed;
                                    /* Default positioning */
                                    bottom: var(--bottom, 0);
                                    top: var(--top, auto);
                                    left: var(--left, auto);
                                    right: var(--right, auto);
                                }
                                
                                /* Define positioning variables for different cases */
                                .chat-boat-position-right {
                                    --bottom: 20px;
                                    --right: 20px;
                                }
                                button.option1Select {
                                    text-align: left;
                                    width: 90%;
                                    margin-top: 15px !important;
                                }
                                .chat-boat-position-left {
                                    --bottom: 20px;
                                    --left: 20px;
                                }
                                
                                .chat-boat-position-center {
                                    --top: 51%;
                                    --right: 20px;
                                }
                                
                                
                                .chat-toggle {
                                    position:fixed;
                                    width: 50px;
                                    height: 50px;
                                    cursor: pointer;
                                }
                                
                                .chat-toggle img {
                                    width: 100%;
                                    height: 100%;
                                }
                                
                                .chat-container {
                                    width: 500px;
                                    background-color: white;
                                    border-radius: 10px;
                                    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
                                    font-family: Arial, sans-serif;
                                    display: flex;
                                    flex-direction: column;
                                    position:absolute;
                                    /* bottom: 80px;
                                    right: 20px; */
                                    display: none; /* Initially hidden */
                                }
                                .icon-head {
                                    display: flex;
                                    justify-content: flex-end;
                                    width: 61%;
                                }
                                .closeicon {
                                    margin-left: 10px;
                                    cursor: pointer;
                                }
                                
                                .chat-header {
                                    background:  $chatbot->main_color ;
                                    color: white;
                                    padding: 10px;
                                    border-top-left-radius: 10px;
                                    border-top-right-radius: 10px;
                                    text-align: left;
                                    display: flex;
                                    align-items: center;
                                }
                                .chat-img {
                                    margin-right: 15px;
                                }
                                .chat-title {
                                    font-weight: bold;
                                }
                                
                                .chat-subtitle {
                                    font-size: 0.8em;
                                }
                                
                                .chat-body {
                                    padding: 10px;
                                    flex-grow: 1;
                                    overflow-y: auto;
                                    height: 335px;
                                }
                                
                                .message {
                                    display: flex;
                                    align-items: flex-start;
                                    margin-bottom: 10px;
                                }
                                
                                .message.user {
                                    justify-content: flex-end;
                                }
                                
                                .message.bot .avatar, 
                                .message.user .avatar {
                                    width: 30px;
                                    height: 30px;
                                    background-color: #014263;
                                    border-radius: 50%;
                                    margin-right: 10px;
                                }
                                
                                .message.user .avatar {
                                    margin-left: 10px;
                                    margin-right: 0;
                                    background-color: #014263;
                                }
                                
                                .message.bot .text {
                                    width: 70%;
                                    padding: 10px;
                                    background-color:$chatbot->question_color;
                                    border-radius: $chatbot->radius;
                                    position: relative;
                                    color: white;
                                    font-weight: 400;
                                    line-height: 25px;
                                    word-wrap: break-word;
                                }
                                
                                .message.user .text {
                                    background-color: $chatbot->answer_color;
                                    color: white;
                                    border-radius: $chatbot->radius;
                                    width: 70% !important;
                                    padding: 10px;
                                    position: relative;
                                    font-weight: 400;
                                    line-height: 25px;
                                    word-wrap: break-word;
                                }
                                
                                .chat-footer {
                                    display: flex;
                                    border-top: 1px solid #e0e0e0;
                                    padding: 10px;
                                }
                                
                                .chat-footer input {
                                    flex-grow: 1;
                                    border: none;
                                    padding: 10px;
                                    border-radius: 5px;
                                    margin-right: 10px;
                                    background-color: #ffffff;
                                }
                                .chat-footer input::placeholder {
                                    color: #9A9A9A;
                                }
                                .chat-footer input:focus-visible {
                                    outline-color: #f1f1f100!important;
                                }
                                .chat-footer button {
                                    background-color: #01426300;
                                    color: white;
                                    border: none;
                                    padding: 10px 20px;
                                    border-radius: 5px;
                                    cursor: pointer;
                                }
                                .chat-btn button {
                                    padding: 11px 21px;
                                    border-radius: $chatbot->button_design;
                                    border: 0px;
                                    background: $chatbot->button_color;
                                    color: $chatbot->button_text_color;
                                }
                                .chat-btn button:hover{
                                     opacity: 0.8;
                                }
                                .chat-img img{
                                    width:57px;
                                    height:52px;
                                    border-radius: 26px;
                                }
                                img#chat-toggle-btn {
                                    border-radius: 26px;
                                }
                                .chat-btn {
                                    display: inline;
                                }
                                .option1Select {
                                    margin-left: 10px;
                                    margin-bottom: 10px;
                                }
                `;
                document.head.appendChild(style);
                document.body.appendChild(chatbotContainer);


                $(document).ready(function() 
                {


                    var chatbotId = $('.chat_bot_id').val();
                  
                    $.ajax({
                        url: '/change/status',
                        method: 'get',
                        data: {
                            bot_id: chatbotId
                        },
                        success: function(data) {
                            
                        },
                        error: function(error) {
                            console.error('Error:', error);
                        }
                    });

                    const chatMessages = $('#chatMessages');
                    const userMessageInput = $('#userMessage');
                    const sendButton = $('#sendButton');

                    const chatBody = $('.chat-body');

                 $(document).on('click', '.option1Select', function() {
                        var data = $(this).val();
                        //  var option_data_id = $(this).attr('dataID');
                        var selected_option_id = $(this).attr('dataattr');
                        var bot_type = $('.bot_type').val();

                        if(selected_option_id && bot_type == 'support')
                        {
                           var qq = $('.question_id').val(selected_option_id);
                            console.log(qq);

                        }else
                        {
                            console.log('sdgsdgd');
                        }

                            $('.selected_option_id').val(selected_option_id);

                        userMessageInput.val(data); // Assuming userMessageInput is defined elsewhere in your code
                    });
                    sendButton.on('click', function() {
                        var bot_user_id = $('.bot_user_id').val();
                        var botId = $('.question_id').val();
                        const message = userMessageInput.val().trim();

                        if (message) {
                            userMessageInput.val('');
                            handleUserMessage(message,botId,bot_user_id);
                        }
                    });

                    function handleUserMessage(message,botId,bot_user_id) {
                        var selectedOptionvalue =   $('.selected_option_id').val();
                        appendUserMessage(message);
                        $.ajax({
                            url: '/chatbot/message',
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                            },
                            data: JSON.stringify({
                                message: message,
                                bot_id: botId,
                                bot_user_id:bot_user_id,
                                chatbotId:chatbotId,
                                option_id:selectedOptionvalue,
                                 parent_id:botId

                            }),
                            success: function(data) {
                            console.log('data');

                            console.log(data);
                            //will work on monday as i have to set the next question id .....
                          $('.question_id').val(data.reply.question_id);
                          $('.bot_user_id').val(data.reply.bot_user_id);


                                const question_option_ids = data.reply.question_option_ids ? data.reply.question_option_ids : [];
                                const reply = data.reply.message ? data.reply.message : 'No reply received';
                                const options = data.reply.options ?data.reply.options : [];
                                const ids = data.reply.question_id ? data.reply.question_id : [];
                                appendBotMessage(reply, options,ids,question_option_ids);
                            },
                            error: function(error) {
                                console.error('Error:', error);
                            }
                        });
                    }
                    function appendUserMessage(message) {
                        

                        const userMessageDiv = $('<div>', { class: 'message user' });
                        const messageTextDiv = $('<div>', { class: 'text', text: message });

                        userMessageDiv.append(messageTextDiv);
                        chatBody.append(userMessageDiv);
                        chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
                    }


                    // function appendBotMessage(reply, options) {

                    //     const botMessageDiv = $('<div>', { class: 'message bot' })
                    //         .append($('<div>', { class: 'text', text: reply }));
                    //     chatBody.append(botMessageDiv);
                    //     chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
                    // }


                    function appendBotMessage(reply, options,ids,question_option_ids) {
                        // Create the bot message div
                        const botMessageDiv = $('<div>', { class: 'message bot' });

                        // Append the bot's message text inside the bot div

                        console.log(reply);
                        //  botMessageDiv.append($('<div>', { class: 'text', text: reply }));
                            botMessageDiv.append($('<div>', { class: 'text' }).html(reply));

                        // Append the message to the chat body first
                        chatBody.append(botMessageDiv);

                        // Now check if options exist and append them separately after the message bot div
                       if (options && options.length > 0) {
                                options.forEach((option, index) => {
                                    let button;
                                    
                                    // if (option == 'Please select to know more about our website.....') {
                                    //     button = $('<button>', {
                                    //         value: option,
                                    //         text: option,
                                    //         data-attr:question_option_ids[index],
                                    //     });
                                    // } else {
                                        button = $('<button>', {
                                            class: 'option1Select',
                                            value: option,
                                            text: option,
                                            dataattr :question_option_ids[index],
                                            data: { id: ids[index] }  // Use data attribute in jQuery
                                        });
                                    // }

                                    const buttonWrapper = $('<div>', { class: 'chat-btn' }).append(button);

                                    // Append the buttons outside the message bot div, directly into the chat body
                                    chatBody.append(buttonWrapper);
                                });
                            }


                        // Scroll to the bottom to ensure new messages are visible
                        chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
                    }


                    function handleOptionSelect(optionValue) {
                        appendUserMessage(optionValue);
                        handleUserMessage(optionValue);
                    }

                    $('#chat-toggle-btn').on('click', function() {
                        const chatContainer = $('#chat-container');
                        chatContainer.toggle();
                    });

                    $('#close-chat-icon').on('click', function() {
                        $('#chat-container').hide();
                    });
                });
        
            }
        
            // Inject the chatbot when the document is ready
            document.addEventListener('DOMContentLoaded', injectChatbot);
        })();
        ";
        return response($script)->header('Content-Type', 'application/javascript');
    }


    public function changeStatus(Request $request)
    {
        $questionAnswer = QuestionAnswer::where('chat_bot_id', $request->bot_id)->update(['status' => '0']);
        if ($questionAnswer) {
            return 1;
        } else {
            return 0;
        }
    }
    public function scriptchatbot($id)
    {
        $chatbot = ChatBot::find($id);
        $questionsIds = QuestionAnswer::pluck('bot_question_id')->where('chat_bot_id ', $id)->toArray();
        $questions = BotQuestion::where('chat_bot_id', $id)
            ->whereNotIn('id', $questionsIds)
            ->first();
        if (!$chatbot) {

            return response('Chatbot not found', 404);
        }

        // Generate the full URL for the logo
        $logoUrl = asset('storage/' . $chatbot->logo);

        // Fetch the CSRF token
        $csrfToken = csrf_token();

        return view('bots.chatbot', compact('chatbot', 'logoUrl', 'csrfToken', 'questions'));
    }
}