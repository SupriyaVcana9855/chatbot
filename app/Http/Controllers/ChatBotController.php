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


class ChatBotController extends Controller
{

    public function botChat(){
        
        return view('bots.bot-chat');
    }
    
    // public function editPrefrence(Request $request)
    // {
    //    return 1;
    // }    

    //single bot question listing
    public function singleBotListing($id)
    {
        $bots=BotQuestion::where('chat_bot_id',$id)->get();
        $sequence = BotQuestion::select('sequence')->where('chat_bot_id',$id)->get();

        $questionFlowIds = BotQuestionFlow::pluck('bot_question_id2')->toArray();
        $questionsNotInFlow = BotQuestion::where('chat_bot_id', $id)
            ->whereNotIn('id', $questionFlowIds)
            ->get();

        return view('bots.single-bot-listing',compact('bots','id','questionsNotInFlow'));
    }    
    // saving questions flow .
    public function addQuestionFlow(Request $request)
    {
        $addFlow = BotQuestionFlow::where('bot_question_id', $request->question_1)->first();
        if(!$addFlow)
        {
            $addFlow = new BotQuestionFlow();
        }
        $addFlow->bot_question_id = $request->question_1;
        $addFlow->bot_question_id2 = $request->question_2;
        $addFlow->chat_bot_id = $request->bot_id;

        $addFlow->save();
        $questionFlowIds = BotQuestionFlow::pluck('bot_question_id2')->toArray();
        $questionsNotInFlow = BotQuestion::where('chat_bot_id', $request->bot_id)
            ->whereNotIn('id', $questionFlowIds)
            ->where('id', '!=', $request->question_1)
            ->get();

        return  response()->json($questionsNotInFlow);

    }


    //page for adding questions for bot
    public function botQuestion($id)
    {
        return view('bots/bot-question',compact('id'));
    }



    public function addQuestion(Request $request)
    {
        // Loop through each question in the "questions" array
        foreach ($request->questions as $questionData) {
            
            $questionText = strtolower($questionData['question']);
            $questionType = '';
            
            // Determine the type of question based on the content
            if (strpos($questionText, 'email') !== false) {
                $questionType = "email";
            } elseif (strpos($questionText, 'contact') !== false) {
                $questionType = "contact";
            } elseif (strpos($questionText, 'name') !== false) {
                $questionType = "name";
            }

            // Create a new BotQuestion instance
            $botQuestion = new BotQuestion();
            
            // Set the common fields
            $botQuestion->chat_bot_id = $questionData['bot_id'];
            $botQuestion->question = $questionData['question'];
            $botQuestion->question_type = $questionType;

            // Handle question based on its type
            if ($questionData['type'] == 'option') {
                // For MCQ (multiple choice questions), store the options
                $botQuestion->option1 = $questionData['options'][0] ?? null;
                $botQuestion->option2 = $questionData['options'][1] ?? null;
            } else {
                // For single-answer questions, store the answer
                $botQuestion->answer = $questionData['answer'] ?? null;
            }

            // Save each question
            $botQuestion->save();
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Questions added successfully!');
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
        $bot = ChatBot::find($question->bot->id);
        $reply = $this->generateReply($message, $bot,$question,$request);

        return response()->json(['reply' => $reply]);
    }

    
    private function generateReply($message, $bot,$question,$request)
    {
        if ($question->question_type == 'email')  {
            if (!preg_match('/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/', $message)) {
                $data = [
                    'message'=>"Enter a valid email!",
                    'question_id' =>$question->id
                ];
                return $data;
            }
        } else if ($question->question_type == 'contact') {
            if (!preg_match('/^\+?[0-9]{10,15}$/', $message)) {
                $data = [
                    'message'=>"Enter a valid phone number!",
                    'question_id' =>$question->id
                ];
                return $data;
            }
        } else if ($question->question_type == 'name') {
            if (!preg_match('/^[\p{L} ]+$/u', $message)) {

                $data = [
                    'message'=>"Enter a valid name!",
                    'question_id' =>$question->id
                ];
                return $data;
            }
        } else {
            if (!preg_match('/./', $message)) { // Simple check for non-empty string
                $data = [
                    'message'=>"Enter a valid data!",
                    'question_id' =>$question->id
                ];
                return $data;
            }
        }



            $botUserData = BotUser::find($request->bot_user_id);
            if(!$botUserData)
            {
                $botUserData = new BotUser;
                $botUserData->chat_bot_id = $bot->id;
                $botUserData->save();
            }
           
        $saveanswer = new QuestionAnswer;
        $saveanswer->bot_question_id = $question->id;
        $saveanswer->answer = $message; 
        $saveanswer->user_id = 1;
        $saveanswer->chat_bot_id = $bot->id;
        $saveanswer->status = '1';
        $saveanswer->bot_user_id = $botUserData->id;
        $saveanswer->save();
        $questionsIds = QuestionAnswer::where('chat_bot_id', $bot->id)
        ->where('status', '1')
        ->pluck('bot_question_id')
        ->toArray();
        $questions = BotQuestion::where('chat_bot_id', $bot->id)
            ->whereNotIn('id', $questionsIds)
            ->first();
            if($questions)
            {
                $data = [
                    'message'=>$questions->question,
                    'question_id' =>$questions->id,
                    'bot_user_id' =>$botUserData->id,
                    'option1'=>($questions->option1)?$questions->option1:null,
                    'option2'=>($questions->option2)?$questions->option2:null,
                ];
            }else
            {
                $data = [
                    'message'=>"Thanx for the information we will contact you soon...",
                    'question_id' =>0,
                ];
            }
            return $data;
        
    }
    public function scriptchatbot($id)
    {
        $chatbot = ChatBot::find($id);
        $questionsIds = QuestionAnswer::pluck('bot_question_id')->where('chat_bot_id ',$id)->toArray();
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
    
        return view('bots.chatbot', compact('chatbot', 'logoUrl', 'csrfToken','questions'));

    }

    public function scriptchatbots($id)
    {
        $chatbot = ChatBot::find($id);
        $questionsIds = QuestionAnswer::pluck('bot_question_id')->where('chat_bot_id ',$id)->where('status ','0')->toArray();
            $questions = BotQuestion::where('chat_bot_id', $id)
        ->whereNotIn('id', $questionsIds)
            ->first();
        if (!$chatbot) {

        return response('Chatbot not found', 404);
        }
        // Generate the full URL for the logo
        if(str_starts_with($chatbot->logo, 'public/'))
        {
            $logoUrl = Storage::url($chatbot->logo);
        }
        else
        {
            $logoUrl = $chatbot->logo;
        }


        // Fetch the CSRF token
        $csrfToken = csrf_token();
        $chatbot = ChatBot::find($id);
        if (!$chatbot) {
            return response('Chatbot not found', 404);
        }

        // Generate the full URL for the logo
        // $logoUrl = asset('storage/' . $chatbot->logo);
        
        // Fetch the CSRF token
        $csrfToken = csrf_token();

        // Generate the script with dynamic values
        $script = "(function() {

            // Function to inject the chatbot HTML and CSS into the page
            function injectChatbot() {
                const chatbotContainer = document.createElement('div');
                chatbotContainer.id = 'chatbotContainer';
                chatbotContainer.innerHTML = `
                    <meta charset='UTF-8'>
                            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                            <meta id='meta-tag' name='csrf-token' content='" . $csrfToken . "'>
                            <title>Chatbot</title>
                            <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>

                            <body>

                             <input type='hidden' name='chat_bot_id' value='" . $chatbot->id . "' class='chat_bot_id'>

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
                                        " . ($questions && $questions->option1 ? "
                                        <div class='chat-btn'>
                                            <button class='option1Select' value='" . htmlspecialchars($questions->option1) . "'>" . htmlspecialchars($questions->option1) . "</button>
                                            <button class='option2Select' value='" . htmlspecialchars($questions->option2) . "'>" . htmlspecialchars($questions->option2) . "</button>
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
                                
                                .chat-boat-position-left {
                                    --bottom: 20px;
                                    --left: 20px;
                                }
                                
                                .chat-boat-position-center {
                                    --top: 37%;
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
                                    max-width: 70% !important;
                                    padding: 10px;
                                    background-color:$chatbot->question_color ;
                                    border-radius: 15px;
                                    position: relative;
                                    border-radius: 0px 5px 5px 0px;
                                    color: #606060;
                                    font-weight: 400;
                                    line-height: 25px;
                                    word-wrap: break-word;
                                }
                                
                                .message.user .text {
                                    background-color: $chatbot->answer_color ;
                                    color: white;
                                    border-radius: 5px 0px 0px 5px !important;
                                    max-width: 70% !important;
                                    padding: 10px;
                                    border-radius: 15px;
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
                                    border-radius: 30px;
                                    border: 0px;
                                    background: linear-gradient(90deg, #001A2B 0%, #005791 100%);
                                    color: #fff;
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
                `;
                document.head.appendChild(style);
                document.body.appendChild(chatbotContainer);


                $(document).ready(function() 
                {


                    var botId = $('.chat_bot_id').val();
                    console.log('sdfsdf',botId);
                    $.ajax({
                        url: '/change/status',
                        method: 'get',
                        data: {
                            bot_id: botId
                        },
                        success: function(data) {
                            
                            console.log('dsfjsflsdf',data);
                        },
                        error: function(error) {
                            console.error('Error:', error);
                        }
                    });

                    const chatMessages = $('#chatMessages');
                    const userMessageInput = $('#userMessage');
                    const sendButton = $('#sendButton');
                    const chatBody = $('.chat-body');
                    console.log(chatMessages.val());
                    const csrfToken = $('#meta-tag').attr('content');
                    $('.option1Select,.option2Select').on('click',function(){
                        var data  = $(this).val();
                        userMessageInput.val(data);
                    })
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

                        appendUserMessage(message);
                        $.ajax({
                            url: '/chatbot/message',
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            data: JSON.stringify({
                                message: message,
                                bot_id: botId,
                                bot_user_id:bot_user_id,
                            }),
                            success: function(data) {
                                //will work on monday as i have to set the next question id .....
                          $('.question_id').val(data.reply.question_id);
                          $('.bot_user_id').val(data.reply.bot_user_id);


                            
                                const reply = data.reply.message ? data.reply.message : 'No reply received';
                                const options = data.reply.options ?data.reply.options : [];
                                appendBotMessage(reply, options);
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


                    function appendBotMessage(reply, options) {

                        const botMessageDiv = $('<div>', { class: 'message bot' })
                            .append($('<div>', { class: 'text', text: reply }));
                        chatBody.append(botMessageDiv);
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
        $questionAnswer = QuestionAnswer::where('chat_bot_id',$request->bot_id)->update(['status' => '0']);
        if($questionAnswer)
        {
            return 1;
        }else
        {
            return 0;
        }
    }

}
