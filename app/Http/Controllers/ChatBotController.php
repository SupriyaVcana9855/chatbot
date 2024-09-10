<?php

namespace App\Http\Controllers;

use App\Models\BotQuestion;
use App\Models\BotQuestionFlow;
use Illuminate\Http\Request;
use App\Models\ChatBot;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class ChatBotController extends Controller
{
    
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

    // //no neeed of this right now
    // public function botFlow($id)
    // {
    //     $questionFlowIds = BotQuestionFlow::pluck('bot_question_id2')->toArray();
    //     $questionsNotInFlow = BotQuestion::where('chat_bot_id', $id)
    //         ->whereNotIn('id', $questionFlowIds)
    //         ->get();
    //     return view('bots/botflow',compact('id','questionsNotInFlow'));
    // }
    //get answer of selected questions
    // public function getAnswer(Request $request)
    // {
    //     $questions = BotQuestion::find($request->id);
    //     return response()->json($questions);
    // }

    //page for adding questions for bot
    public function botQuestion($id)
    {
        return view('bots/bot-question',compact('id'));
    }

    //save bot question to the table
    // public function addQuestion(Request $request)
    // {
    //     dd($request);
    //     $question = strtolower($request->question);
    //     $user = new BotQuestion();
    //     $question_type = '';
    //     if (strpos($question, 'email') !== false) {
    //         $question_type ="email";
    //     } elseif (strpos($question, 'contact') !== false) {
    //         $question_type ="contact";
    //     } elseif (strpos($question, 'name') !== false) {
    //         $question_type ="name";
    //     }

    //     $type = $request->type;
    //     if($type == 'option')
    //     {
    //         $user->option1  = $request->option1;
    //         $user->option2  = $request->option2;
    //     }else
    //     {
    //         $user->answer  = $request->answer; 
    //     }
    //     $user->question_type = $question_type;
    //     $user->chat_bot_id  = $request->bot_id;
    //     $user->question  = $request->question;
    //     $user->save();
    //     return redirect()->back();
    // }


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

    public function getChatbotScript($id)
    {
        $chatbot = ChatBot::find($id);
        if (!$chatbot) {
            return response('Chatbot not found', 404);
        }
        // Generate the full URL for the logo
        // $logoUrl = asset('storage/' . $chatbot->logo);
        // $script = "
        //     var chatbot_id={$id};
        //     !function(){
        //         var t,e,a=document,s='botman-chatbot';
        //         a.getElementById(s)||(t=a.createElement('script'),
        //         t.id=s,t.type='text/javascript',
        //         t.src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js',

        //         t.onload=function(){
        //             window.botmanWidget = {
        //                 frameEndpoint: '/botman/chat',
        //                 introMessage: '{$chatbot->intro_message}',
        //                 chatServer: '/botman',
        //                 title: '{$chatbot->name}',
        //                 mainColor: '{$chatbot->main_color}',
        //                 bubbleBackground: '{$chatbot->bubble_background}',
        //                 bubbleAvatarUrl: '{$chatbot->logo}', // Use the full URL for the logo
        //                 userId: 'user',
        //                 placeholderText: 'Type a message...',
        //                 font: '{$chatbot->font}',
        //                 fontSize: '{$chatbot->font_size}',
        //                 botPosition: '{$chatbot->bot_position}',
        //                 messageBubble: '{$chatbot->message_bubble}',
        //                 radius: '{$chatbot->radius}',
        //                 textAlignment: '{$chatbot->text_alignment}',
        //                 questionColor: '{$chatbot->question_color}',
        //                 answerColor: '{$chatbot->answer_color}',
        //                 autoOpen: true,
        //                 autoOpenDelay: 1000,
        //             };

        //             const elementFound = (ele) => {
        //                 ele.addEventListener('click', function(){
        //                     const header = document.querySelector('#botmanWidgetRoot > div:first-child > div:first-child');
        //                     console.log('headera', header);
        //                     if (header) {
        //                         const img = document.createElement('img');
        //                         img.src = '{$chatbot->logo}';
        //                         img.alt = 'Chatbot Logo';
        //                         img.className = 'ChatbotLogo';

        //                         img.style.height = '30px'; // Adjust as needed
        //                         img.style.marginRight = '10px'; // Adjust as needed
        //                         header.insertBefore(img, header.firstChild);

        //                         $('.desktop-closed-message-avatar').remove(img);

        //                         // Add an event listener to the logo to remove it on click
        //                         // img.addEventListener('click', function() {
        //                         //          console.log('remove child',img);
        //                         //     header.removeChild(img);
        //                         //     const header1 = document.querySelector('#botmanWidgetRoot > div:first-child > div:first-child');


        //                         //     console.log('header2',header1);
        //                         // });
        //                     }
        //                             // console.log('headerssssss',header1);

        //                 });
        //             }

        //             const observer = new MutationObserver(function(mutationsList, observer) {
        //                 for (let mutation of mutationsList) {
        //                     if (mutation.type === 'childList') {
        //                         mutation.addedNodes.forEach(function(node) {
        //                             console.log('enter-node', node);
        //                             if (node.nodeType === 1 && node.matches('#botmanWidgetRoot')) {
        //                                 elementFound(node);
        //                                 observer.disconnect(); // Stop observing once the element is found
        //                             }
        //                         });
        //                     }
        //                 }
        //             });

        //             // Start observing the DOM for changes
        //             observer.observe(document.body, { childList: true, subtree: true });

        //         },
        //         t.onerror=function(){
        //             console.error('Failed to load the BotMan web widget script.');
        //         },
        //         e=a.getElementsByTagName('script')[0],e.parentNode.insertBefore(t,e))
        //     }();
        // ";
        $script = "
            var chatbot_id={$id};
            !function(){
                var t,e,a=document,s='botman-chatbot';
                a.getElementById(s)||(t=a.createElement('script'),
                t.id=s,t.type='text/javascript',
                t.src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js',

                t.onload=function(){
                    window.botmanWidget = {
                        frameEndpoint: '/botman/chat',
                        introMessage: '{$chatbot->intro_message}',
                        chatServer: '/botman',
                        title: '{$chatbot->name}',
                        mainColor: '{$chatbot->main_color}',
                        bubbleBackground: '{$chatbot->bubble_background}',
                        bubbleAvatarUrl: '{$chatbot->logo}', // Use the full URL for the logo
                        userId: 'user',
                        displayMessageTime: true,
                        placeholderText: 'Type a message...',
                        botPosition: '{$chatbot->bot_position}',
                        messageBubble: '{$chatbot->message_bubble}',
                        radius: '{$chatbot->radius}',
                        textAlignment: '{$chatbot->text_alignment}',
                        questionColor: '{$chatbot->question_color}',
                        answerColor: '{$chatbot->answer_color}',
                        autoOpen: true,
                        autoOpenDelay: 1000,
                    };
                    
                    const elementFound = (ele) => {
                        ele.addEventListener('click', function(){
                            const header = document.querySelector('#botmanWidgetRoot > div:first-child > div:first-child');
                            if (header) {
                                const img = document.createElement('img');
                                img.src = '{$chatbot->logo}';
                                img.alt = 'Chatbot Logo';
                                img.className = 'ChatbotLogo';

                                img.style.height = '30px'; // Adjust as needed
                                img.style.marginRight = '10px'; // Adjust as needed
                                header.insertBefore(img, header.firstChild);
                                
                                $('.desktop-closed-message-avatar').remove(img);
                            }
                        });
                                    
                        }

                    const observer = new MutationObserver(function(mutationsList, observer) {
                        for (let mutation of mutationsList) {
                            if (mutation.type === 'childList') {
                                mutation.addedNodes.forEach(function(node) {
                                    if (node.nodeType === 1 && node.matches('#botmanWidgetRoot')) {
                                        elementFound(node);
                                        observer.disconnect(); // Stop observing once the element is found
                                    }
                                });
                            }
                        }
                    });

                    // Start observing the DOM for changes
                    observer.observe(document.body, { childList: true, subtree: true });

                    // Inject custom styles after the widget loads
                    var style = document.createElement('style');
                    style.innerHTML = `
                        .botman-chat-message .message {
                            font-size: {$chatbot->font_size}px;
                            font-family: '{$chatbot->font}', sans-serif;
                        }
                        .botman-button {
                            background-color: {$chatbot->main_color};
                            color: white;
                            border: none;
                            padding: 10px 20px;
                            border-radius: 5px;
                            font-size: 14px;
                            font-family: '{$chatbot->font}', sans-serif;
                            cursor: pointer;
                        }
                        .botman-button:hover {
                            background-color: darken({$chatbot->main_color}, 10%);
                        }
                    `;
                    document.head.appendChild(style);

                },
                t.onerror=function(){
                    console.error('Failed to load the BotMan web widget script.');
                },
                e=a.getElementsByTagName('script')[0],e.parentNode.insertBefore(t,e))
            }();
        ";

        return response($script)->header('Content-Type', 'application/javascript');
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

        // Logic for processing the message and generating a reply
        $bot = ChatBot::find($question->bot->id);
        $reply = $this->generateReply($message, $bot,$question);

        return response()->json(['reply' => $reply]);
    }

    
    private function generateReply($message, $bot,$question)
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
        $saveanswer = new QuestionAnswer;
        $saveanswer->bot_question_id = $question->id;
        $saveanswer->answer = $message; 
        $saveanswer->user_id = 1;
        $saveanswer->chat_bot_id = $bot->id;
        $saveanswer->save();
        $questionsIds = QuestionAnswer::where('chat_bot_id', $bot->id)
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
    
    // public function scriptchatbots($id)
    // {
    //     $chatbot = ChatBot::find($id);
    //     $questionsIds = QuestionAnswer::where('chat_bot_id', $id)->pluck('bot_question_id')->toArray();
    //     $questions = BotQuestion::where('chat_bot_id', $id)
    //         ->whereNotIn('id', $questionsIds)
    //         ->first();
        
    //     if (!$chatbot) {
    //         return response('Chatbot not found', 404);
    //     }
    
    //     // Generate the full URL for the logo
    //     $logoUrl = asset('storage/' . $chatbot->logo);
    
    //     // Fetch the CSRF token
    //     $csrfToken = csrf_token();
    
    //     // Generate the script with dynamic values using concatenation
    //     $script = "
            // (function() {
            
            //     // Function to inject the chatbot HTML and CSS into the page
            //     function injectChatbot() {
            //         const chatbotContainer = document.createElement('div');
            //         chatbotContainer.id = 'chatbotContainer';
            //         chatbotContainer.innerHTML = `
            //             <meta charset='UTF-8'>
            //             <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            //             <meta name='csrf-token' content='" . $csrfToken . "'>
            //             <title>Chatbot</title>
            //             <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
            
            //             <body>
            //                 <div class='chat-toggle chat-boat-position chat-boat-position-" . $chatbot->bot_position . "' id='chatMessages'>
            //                     <img src='" . $logoUrl . "' alt='Chat Icon' id='chat-toggle-btn'>
            //                 </div>
            //                 <div class='chat-container chat-boat-position chat-boat-position-" . $chatbot->bot_position . "' id='chat-container'>
            //                     <div class='chat-header'>
            //                         <div class='chat-img'>
            //                             <img src='" . $logoUrl . "' alt='Chat Icon' id='chat-toggle-btn'>
            //                         </div>
            //                         <div>
            //                             <div class='chat-title'>" . $chatbot->name . "</div>
            //                             <div class='chat-subtitle'>" . $chatbot->type . "</div>
            //                         </div>
            //                         <div class='icon-head'>
            //                             <div>
            //                                 <img src='" . asset('assets/images/reload.png') . "'>
            //                             </div>
            //                             <div class='closeicon'>
            //                                 <img src='" . asset('assets/images/colse.png') . "' id='close-chat-icon'>
            //                             </div>
            //                         </div>
            //                     </div>
            //                     <div class='chat-body'>
            //                         <div class='message bot'>
            //                             <div class='text'><h4>" . $chatbot->intro_message . "</h4></div>
            //                         </div>
            //                         " . ($questions ? "
            //                         <div class='message bot'>
            //                             <div class='text'>" . $questions->question . "</div>
            //                             <input type='hidden' name='question_id' value='" . $questions->id . "' class='question_id'>
            //                         </div>" : "") . "
            //                         " . ($questions && $questions->option1 ? "
            //                         <div class='chat-btn'>
            //                             <button class='option1Select' value='" . $questions->option1 . "'>" . $questions->option1 . "</button>
            //                             <button class='option2Select' value='" . $questions->option2 . "'>" . $questions->option2 . "</button>
            //                         </div>" : "") . "
            //                     </div>
            //                     <div class='chat-footer'>
            //                         <input type='text' id='userMessage' placeholder='Enter your message...'>
            //                         <button><img src='" . asset('assets/images/fileupload.png') . "' /></button>
            //                         <button id='sendButton'><img src='" . asset('assets/images/Vector.png') . "' /></button>
            //                     </div>
            //                 </div>
            //         `;
            
            //         const style = document.createElement('style');
            //         style.innerHTML = `
            //             .chat-boat-position {
            //                 position: fixed;
            //                 bottom: var(--bottom, 0);
            //                 top: var(--top, auto);
            //                 left: var(--left, auto);
            //                 right: var(--right, auto);
            //             }
            //             .chat-boat-position-right {
            //                 --bottom: 20px;
            //                 --right: 20px;
            //             }
            //             .chat-boat-position-left {
            //                 --bottom: 20px;
            //                 --left: 20px;
            //             }
            //             .chat-boat-position-center {
            //                 --top: 37%;
            //                 --right: 20px;
            //             }
            //             .chat-toggle {
            //                 position:absolute;
            //                 width: 50px;
            //                 height: 50px;
            //                 cursor: pointer;
            //             }
            //             .chat-toggle img {
            //                 width: 100%;
            //                 height: 100%;
            //             }
            //             .chat-container {
            //                 width: 500px;
            //                 background-color: white;
            //                 border-radius: 10px;
            //                 box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            //                 font-family: Arial, sans-serif;
            //                 display: flex;
            //                 flex-direction: column;
            //                 display: none;
            //             }
            //             .icon-head {
            //                 display: flex;
            //                 justify-content: flex-end;
            //                 width: 61%;
            //             }
            //             .closeicon {
            //                 margin-left: 10px;
            //                 cursor: pointer;
            //             }
            //             .chat-header {
            //                 background: " . $chatbot->main_color . ";
            //                 color: white;
            //                 padding: 10px;
            //                 border-top-left-radius: 10px;
            //                 border-top-right-radius: 10px;
            //                 display: flex;
            //                 align-items: center;
            //             }
            //             .chat-img {
            //                 margin-right: 15px;
            //             }
            //             .chat-title {
            //                 font-weight: bold;
            //             }
            //             .chat-subtitle {
            //                 font-size: 0.8em;
            //             }
            //             .chat-body {
            //                 padding: 10px;
            //                 flex-grow: 1;
            //                 overflow-y: auto;
            //                 height: 335px;
            //             }
            //             .message {
            //                 display: flex;
            //                 margin-bottom: 10px;
            //             }
            //             .message.user {
            //                 justify-content: flex-end;
            //             }
            //             .message.bot {
            //                 justify-content: flex-start;
            //             }
            //             .message .text {
            //                 max-width: 75%;
            //                 padding: 10px;
            //                 border-radius: 5px;
            //                 background-color: " . $chatbot->main_color . ";
            //                 color: white;
            //                 word-wrap: break-word;
            //             }
            //             .chat-footer {
            //                 display: flex;
            //                 align-items: center;
            //                 padding: 10px;
            //                 border-top: 1px solid #ddd;
            //             }
            //             .chat-footer input {
            //                 flex-grow: 1;
            //                 padding: 5px;
            //                 border: 1px solid #ddd;
            //                 border-radius: 5px;
            //             }
            //             .chat-footer button {
            //                 background: none;
            //                 border: none;
            //                 cursor: pointer;
            //                 margin-left: 5px;
            //             }
            //         `;
                    
            //         document.head.appendChild(style);
            //         document.body.appendChild(chatbotContainer);
            //     }
            //         $(document).ready(function() {
            //             var chatMessages = $('#chatMessages');
            //             var userMessageInput = $('#userMessage');
            //             var sendButton = $('#sendButton');
            //             var chatBody = $('.chat-body');
            //             const csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
                    
            //             $('.option1Select,.option2Select').on('click', function() {
            //                 var data = $(this).val();
            //                 userMessageInput.val(data);
            //             });
                    
            //             sendButton.on('click', function() {
            //                 var botId = $('.question_id').val();
            //                 const message = userMessageInput.val().trim();
            //                 if (message) {
            //                     userMessageInput.val('');
            //                     handleUserMessage(message, botId);
            //                 }
            //             });
                    
            //             function handleUserMessage(message, botId) {
            //                 appendUserMessage(message);
            //                 $.ajax({
            //                     url: '/chatbot/message',
            //                     method: 'POST',
            //                     headers: {
            //                         'Content-Type': 'application/json',
            //                         'X-CSRF-TOKEN': csrfToken
            //                     },
            //                     data: JSON.stringify({
            //                         message: message,
            //                         bot_id: botId
            //                     }),
            //                     success: function(data) {
            //                         $('.question_id').val(data.reply.question_id);
            //                         alert(data.reply.question_id);
            //                         const reply = data.reply.message ? data.reply.message : 'No reply received';
            //                         const options = data.reply.options ? data.reply.options : [];
            //                         appendBotMessage(reply, options);
            //                     },
            //                     error: function(error) {
            //                         console.error('Error:', error);
            //                     }
            //                 });
            //             }
                    
            //             function appendUserMessage(message) {
            //                 console.log(message);
            //                 const userMessageDiv = $('<div>', { class: 'message user' });
            //                 const messageTextDiv = $('<div>', { class: 'text', text: message });
            //                 userMessageDiv.append(messageTextDiv);
            //                 chatBody.append(userMessageDiv);
            //                 chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
            //             }
                    
            //             function appendBotMessage(reply, options) {
            //                 const botMessageDiv = $('<div>', { class: 'message bot' })
            //                     .append($('<div>', { class: 'text', text: reply }));
            //                 chatBody.append(botMessageDiv);
            //                 chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
            //             }
                    
            //             function handleOptionSelect(optionValue) {
            //                 appendUserMessage(optionValue);
            //                 handleUserMessage(optionValue);
            //             }
                    
            //             $('#chat-toggle-btn').on('click', function() {
            //                 const chatContainer = $('#chat-container');
            //                 chatContainer.toggle();
            //             });
                    
            //             $('#close-chat-icon').on('click', function() {
            //                 $('#chat-container').hide();
            //             });
            //         });
            
            
            // })();
            // ";
    
    //     // Return the script as a response
    //     return response($script, 200)->header('Content-Type', 'application/javascript');
    // }






//     public function scriptchatbots($id)
// {
//     $chatbot = ChatBot::find($id);
//     $questionsIds = QuestionAnswer::where('chat_bot_id', $id)->pluck('bot_question_id')->toArray();
//     $questions = BotQuestion::where('chat_bot_id', $id)
//         ->whereNotIn('id', $questionsIds)
//         ->first();
    
//     if (!$chatbot) {
//         return response('Chatbot not found', 404);
//     }

//     // Generate the full URL for the logo
//     $logoUrl = asset('storage/' . $chatbot->logo);

//     // Fetch the CSRF token
//     $csrfToken = csrf_token();

//     // Generate the script with dynamic values using concatenation
//     $script = "
//     <script>
//     $(document).ready(function() {
//         var chatMessages = $('#chatMessages');
//         var userMessageInput = $('#userMessage');
//         var sendButton = $('#sendButton');
//         var chatBody = $('.chat-body');
//         const csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
    
//         $('.option1Select,.option2Select').on('click', function() {
//             var data = $(this).val();
//             userMessageInput.val(data);
//         });
    
//         sendButton.on('click', function() {
//             var botId = $('.question_id').val();
//             const message = userMessageInput.val().trim();
//             if (message) {
//                 userMessageInput.val('');
//                 handleUserMessage(message, botId);
//             }
//         });
    
//         function handleUserMessage(message, botId) {
//             appendUserMessage(message);
//             $.ajax({
//                 url: '/chatbot/message',
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': csrfToken
//                 },
//                 data: JSON.stringify({
//                     message: message,
//                     bot_id: botId
//                 }),
//                 success: function(data) {
//                     $('.question_id').val(data.reply.question_id);
//                     alert(data.reply.question_id);
//                     const reply = data.reply.message ? data.reply.message : 'No reply received';
//                     const options = data.reply.options ? data.reply.options : [];
//                     appendBotMessage(reply, options);
//                 },
//                 error: function(error) {
//                     console.error('Error:', error);
//                 }
//             });
//         }
    
//         function appendUserMessage(message) {
//             console.log(message);
//             const userMessageDiv = $('<div>', { class: 'message user' });
//             const messageTextDiv = $('<div>', { class: 'text', text: message });
//             userMessageDiv.append(messageTextDiv);
//             chatBody.append(userMessageDiv);
//             chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
//         }
    
//         function appendBotMessage(reply, options) {
//             const botMessageDiv = $('<div>', { class: 'message bot' })
//                 .append($('<div>', { class: 'text', text: reply }));
//             chatBody.append(botMessageDiv);
//             chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
//         }
    
//         function handleOptionSelect(optionValue) {
//             appendUserMessage(optionValue);
//             handleUserMessage(optionValue);
//         }
    
//         $('#chat-toggle-btn').on('click', function() {
//             const chatContainer = $('#chat-container');
//             chatContainer.toggle();
//         });
    
//         $('#close-chat-icon').on('click', function() {
//             $('#chat-container').hide();
//         });
//     });
//     </script>
//     ";
    

//     return response()->json(['script' => $script]);
// }



// public function scriptchatbots($id)
// {
//     $chatbot = ChatBot::find($id);

//     if (!$chatbot) {
//         return response('Chatbot not found', 404);
//     }

//     $questionsIds = QuestionAnswer::where('chat_bot_id', $id)->pluck('bot_question_id')->toArray();
//     $questions = BotQuestion::where('chat_bot_id', $id)
//         ->whereNotIn('id', $questionsIds)
//         ->first();

//     // Generate the full URL for the logo
//     $logoUrl = asset('storage/' . $chatbot->logo);

//     // Fetch the CSRF token
//     $csrfToken = csrf_token();

//     // Generate the script with dynamic values using concatenation
//     $script = "
//             (function() {

//                 // Function to inject the chatbot HTML and CSS into the page
//                 function injectChatbot() {
//                     const chatbotContainer = document.createElement('div');
//                     chatbotContainer.id = 'chatbotContainer';
//                     chatbotContainer.innerHTML = `
//                         <meta charset='UTF-8'>
//                         <meta name='viewport' content='width=device-width, initial-scale=1.0'>
//                         <meta id='meta-tag' name='csrf-token' content='" . $csrfToken . "'>
//                         <title>Chatbot</title>
//                         <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>

//                         <body>
//                             <div class='chat-toggle chat-boat-position chat-boat-position-" . $chatbot->bot_position . "' id='chatMessages'>
//                                 <img src='" . $logoUrl . "' alt='Chat Icon' id='chat-toggle-btn'>
//                             </div>
//                             <div class='chat-container chat-boat-position chat-boat-position-" . $chatbot->bot_position . "' id='chat-container'>
//                                 <div class='chat-header'>
//                                     <div class='chat-img'>
//                                         <img src='" . $logoUrl . "' alt='Chat Icon' id='chat-toggle-btn'>
//                                     </div>
//                                     <div>
//                                         <div class='chat-title'>" . htmlspecialchars($chatbot->name) . "</div>
//                                         <div class='chat-subtitle'>" . htmlspecialchars($chatbot->type) . "</div>
//                                     </div>
//                                     <div class='icon-head'>
//                                         <div>
//                                             <img src='" . asset('assets/images/reload.png') . "'>
//                                         </div>
//                                         <div class='closeicon'>
//                                             <img src='" . asset('assets/images/close.png') . "' id='close-chat-icon'>
//                                         </div>
//                                     </div>
//                                 </div>
//                                 <div class='chat-body'>
//                                     <div class='message bot'>
//                                         <div class='text'><h4>" . htmlspecialchars($chatbot->intro_message) . "</h4></div>
//                                     </div>
//                                     " . ($questions ? "
//                                     <div class='message bot'>
//                                         <div class='text'>" . htmlspecialchars($questions->question) . "</div>
//                                         <input type='hidden' name='question_id' value='" . $questions->id . "' class='question_id'>
//                                     </div>" : "") . "
//                                     " . ($questions && $questions->option1 ? "
//                                     <div class='chat-btn'>
//                                         <button class='option1Select' value='" . htmlspecialchars($questions->option1) . "'>" . htmlspecialchars($questions->option1) . "</button>
//                                         <button class='option2Select' value='" . htmlspecialchars($questions->option2) . "'>" . htmlspecialchars($questions->option2) . "</button>
//                                     </div>" : "") . "
//                                 </div>
//                                 <div class='chat-footer'>
//                                     <input type='text' id='userMessage' placeholder='Enter your message...'>
//                                     <button><img src='" . asset('assets/images/fileupload.png') . "' /></button>
//                                     <button id='sendButton'><img src='" . asset('assets/images/Vector.png') . "' /></button>
//                                 </div>
//                             </div>
//                     `;

//                     const style = document.createElement('style');
//                     style.innerHTML = `
//                         .chat-boat-position {
//                             position: fixed;
//                             bottom: var(--bottom, 0);
//                             top: var(--top, auto);
//                             left: var(--left, auto);
//                             right: var(--right, auto);
//                         }
//                         .chat-boat-position-right {
//                             --bottom: 20px;
//                             --right: 20px;
//                         }
//                         .chat-boat-position-left {
//                             --bottom: 20px;
//                             --left: 20px;
//                         }
//                         .chat-boat-position-center {
//                             --top: 37%;
//                             --right: 20px;
//                         }
//                         .chat-toggle {
//                             position:absolute;
//                             width: 50px;
//                             height: 50px;
//                             cursor: pointer;
//                         }
//                         .chat-toggle img {
//                             width: 100%;
//                             height: 100%;
//                         }
//                         .chat-container {
//                             width: 500px;
//                             background-color: white;
//                             border-radius: 10px;
//                             box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
//                             font-family: Arial, sans-serif;
//                             display: flex;
//                             flex-direction: column;
//                             display: none;
//                         }
//                         .icon-head {
//                             display: flex;
//                             justify-content: flex-end;
//                             width: 61%;
//                         }
//                         .closeicon {
//                             margin-left: 10px;
//                             cursor: pointer;
//                         }
//                         .chat-header {
//                             background: " . htmlspecialchars($chatbot->main_color) . ";
//                             color: white;
//                             padding: 10px;
//                             border-top-left-radius: 10px;
//                             border-top-right-radius: 10px;
//                             display: flex;
//                             align-items: center;
//                         }
//                         .chat-img {
//                             margin-right: 15px;
//                         }
//                         .chat-title {
//                             font-weight: bold;
//                         }
//                         .chat-subtitle {
//                             font-size: 0.8em;
//                         }
//                         .chat-body {
//                             padding: 10px;
//                             flex-grow: 1;
//                             overflow-y: auto;
//                             height: 335px;
//                         }
//                         .message {
//                             display: flex;
//                             margin-bottom: 10px;
//                         }
//                         .message.user {
//                             justify-content: flex-end;
//                         }
//                         .message.bot {
//                             justify-content: flex-start;
//                         }
//                         .message .text {
//                             max-width: 75%;
//                             padding: 10px;
//                             border-radius: 5px;
//                             background-color: " . htmlspecialchars($chatbot->main_color) . ";
//                             color: white;
//                             word-wrap: break-word;
//                         }
//                         .chat-footer {
//                             display: flex;
//                             align-items: center;
//                             padding: 10px;
//                             border-top: 1px solid #ddd;
//                         }
//                         .chat-footer input {
//                             flex-grow: 1;
//                             padding: 5px;
//                             border: 1px solid #ddd;
//                             border-radius: 5px;
//                         }
//                         .chat-footer button {
//                             background: none;
//                             border: none;
//                             cursor: pointer;
//                             margin-left: 5px;
//                         }
//                     `;
                    
//                     document.head.appendChild(style);
//                     document.body.appendChild(chatbotContainer);
//                 }

//             $(document).ready(function() {
//                 var chatMessages = $('#chatMessages');
//                 var userMessageInput = $('#userMessage');
//                 var sendButton = $('#sendButton');
//                 var chatBody = $('.chat-body');
//                 var csrfToken = $('#meta-tag').attr('content');
                
//                 // Handle click on option buttons to set message input value
//                 $('.option1Select, .option2Select').on('click', function() {
//                     var data = $(this).val();
//                     userMessageInput.val(data);
//                 });

//                 // Handle click on send button
//                 sendButton.on('click', function() {
//                     var botId = $('.question_id').val();
//                     var message = userMessageInput.val().trim();
//                     if (message) {
//                         userMessageInput.val('');
//                         handleUserMessage(message, botId);
//                     }
//                 });

//                 // Function to handle sending user message
//                 function handleUserMessage(message, botId) {
//                     appendUserMessage(message);
//                     $.ajax({
//                         url: '/chatbot/message',
//                         method: 'POST',
//                         headers: {
//                             'Content-Type': 'application/json',
//                             'X-CSRF-TOKEN': csrfToken
//                         },
//                         data: JSON.stringify({
//                             message: message,
//                             bot_id: botId
//                         }),
//                         success: function(data) {
//                             var reply = data.reply.message || 'No reply received';
//                             var options = data.reply.options || [];
//                             appendBotMessage(reply, options);
//                             $('.question_id').val(data.reply.question_id); // Update the hidden question_id input
//                         },
//                         error: function(error) {
//                             console.error('Error:', error);
//                         }
//                     });
//                 }

//                 // Function to append user message to chat
//                 function appendUserMessage(message) {
//                     var userMessageDiv = $('<div>', { class: 'message user' });
//                     var messageTextDiv = $('<div>', { class: 'text', text: message });
//                     userMessageDiv.append(messageTextDiv);
//                     chatBody.append(userMessageDiv);
//                     chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
//                 }

//                 // Function to append bot message to chat
//                 function appendBotMessage(reply, options) {
//                     var botMessageDiv = $('<div>', { class: 'message bot' });
//                     var messageTextDiv = $('<div>', { class: 'text', text: reply });
//                     botMessageDiv.append(messageTextDiv);

//                     // Add options buttons if they exist
//                     if (options.length > 0) {
//                         var optionsDiv = $('<div>', { class: 'options' });
//                         options.forEach(function(option) {
//                             var optionButton = $('<button>', { 
//                                 class: 'option-btn', 
//                                 text: option,
//                                 click: function() {
//                                     handleOptionSelect(option);
//                                 }
//                             });
//                             optionsDiv.append(optionButton);
//                         });
//                         botMessageDiv.append(optionsDiv);
//                     }

//                     chatBody.append(botMessageDiv);
//                     chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
//                 }

//                 // Handle option button click
//                 function handleOptionSelect(optionValue) {
//                     appendUserMessage(optionValue);
//                     handleUserMessage(optionValue, $('.question_id').val());
//                 }

//                 // Toggle chat container visibility
//                 $('#chat-toggle-btn').on('click', function() {
//                     $('#chat-container').toggle();
//                 });

//                 // Close chat container
//                 $('#close-chat-icon').on('click', function() {
//                     $('#chat-container').hide();
//                 });
//             });


//         })();
//         ";

//     return response($script)->header('Content-Type', 'application/javascript');
// }

    
//  public function scriptchatbots($id)
//  {
//       $chatbot = ChatBot::find($id);
//      $questionsIds = QuestionAnswer::pluck('bot_question_id')->where('chat_bot_id ',$id)->toArray();
//           $questions = BotQuestion::where('chat_bot_id', $id)
//        ->whereNotIn('id', $questionsIds)
//          ->first();
//      if (!$chatbot) {

//         return response('Chatbot not found', 404);
//      }
//      // Generate the full URL for the logo
//       $logoUrl = asset('storage/' . $chatbot->logo);
 
//      // Fetch the CSRF token
//       $csrfToken = csrf_token();
//      $chatbot = ChatBot::find($id);
//      if (!$chatbot) {
//          return response('Chatbot not found', 404);
//      }

//      // Generate the full URL for the logo
//      $logoUrl = asset('storage/' . $chatbot->logo);
     
//      // Fetch the CSRF token
//      $csrfToken = csrf_token();

//      // Generate the script with dynamic values
//      $script = "(function() {

//          // Function to inject the chatbot HTML and CSS into the page
//          function injectChatbot() {
//              const chatbotContainer = document.createElement('div');
//              chatbotContainer.id = 'chatbotContainer';
//                chatbotContainer.innerHTML = `
//                      <div class='chat-toggle chat-boat-position' id='chatMessages'>
//                          <img src='./image/chaticon.png' alt='Chat Icon' id='chat-toggle-btn'>
//                      </div>
//                      <div class='chat-container chat-boat-position' id='chat-container'>
//                          <div class='chat-header'>
//                              <div class='chat-img'>
//                                  <img src='./image/clientimg.png' />
//                              </div>
//                              <div>
//                                  <div class='chat-title'>Lorem Ipsum</div>
//                                  <div class='chat-subtitle'>Support</div>
//                              </div>
//                              <div class='icon-head'>  
//                                  <div >
//                                      <img src='./image/reload.png'>
//                                  </div>
//                                  <div class='closeicon'>
//                                      <img src='./image/colse.png' id='close-chat-icon'>
//                                  </div>
//                              </div>
//                          </div>
//                          <div class='chat-body'>
//                              <div class='message bot'>
//                                  <div class='text'>Hello! Welcome to our chatbot. How can I assist you today?</div>
//                              </div>
//                              <div class='message user'>
//                                  <div class='text'>Hi there! I'm interested in building an e-commerce website for my business.</div>
//                              </div>
//                          </div>
//                          <div class='chat-footer'>
//                              <input type='text' id ='userMessage' placeholder='Enter your message...'>
//                              <button><img src='./image/fileupload.png'/></button>
//                              <button id='sendButton' ><img src='./image/Vector.png'/></button>
//                          </div>
//                      </div>
//              `;
     
//              const style = document.createElement('style');
//              style.innerHTML = `
//                  /* Add your styles here */
//                   .chat-boat-position{
//                          bottom: 20px;
//                          right: 20px;
//                          left:20px;
//                          bottom:20px;
//                          top:40%;
//                          right:20px;
//                      }


//                      .chat-toggle {
//                          position:absolute;
//                          width: 50px;
//                          height: 50px;
//                          cursor: pointer;
//                      }
                 
//                      .chat-toggle img {
//                          width: 100%;
//                          height: 100%;
//                      }
                     
//                      .chat-container {
//                          width: 500px;
//                          background-color: white;
//                          border-radius: 10px;
//                          box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
//                          font-family: Arial, sans-serif;
//                          display: flex;
//                          flex-direction: column;
//                          position:absolute;
//                          /* bottom: 80px;
//                          right: 20px; */
//                          display: none; /* Initially hidden */
//                      }
//                      .icon-head {
//                          display: flex;
//                          justify-content: flex-end;
//                          width: 61%;
//                      }
//                      .closeicon {
//                          margin-left: 10px;
//                          cursor: pointer;
//                      }
                     
//                      .chat-header {
//                          background: linear-gradient(90deg, #001A2B 0%, #005791 100%);
//                          color: white;
//                          padding: 10px;
//                          border-top-left-radius: 10px;
//                          border-top-right-radius: 10px;
//                          text-align: left;
//                          display: flex;
//                          align-items: center;
//                      }
//                      .chat-img {
//                          margin-right: 15px;
//                      }
//                      .chat-title {
//                          font-weight: bold;
//                      }
                     
//                      .chat-subtitle {
//                          font-size: 0.8em;
//                      }
                     
//                      .chat-body {
//                          padding: 10px;
//                          flex-grow: 1;
//                          overflow-y: auto;
//                          height: 335px;
//                      }
                     
//                      .message {
//                          display: flex;
//                          align-items: flex-start;
//                          margin-bottom: 10px;
//                      }
                     
//                      .message.user {
//                          justify-content: flex-end;
//                      }
                     
//                      .message.bot .avatar, 
//                      .message.user .avatar {
//                          width: 30px;
//                          height: 30px;
//                          background-color: #014263;
//                          border-radius: 50%;
//                          margin-right: 10px;
//                      }
                     
//                      .message.user .avatar {
//                          margin-left: 10px;
//                          margin-right: 0;
//                          background-color: #014263;
//                      }
                     
//                      .message .text {
//                          max-width: 70%;
//                          padding: 10px;
//                          background-color:#F0F2F7;
//                          border-radius: 15px;
//                          position: relative;
//                          border-radius: 0px 5px 5px 0px;
//                          color: #606060;
//                          font-weight: 400;
//                          line-height: 25px;
//                      }
                     
//                      .message.user .text {
//                          background-color: #014263;
//                          color: white;
//                          border-radius: 5px 0px 0px 5px !important;
//                      }
                     
//                      .chat-footer {
//                          display: flex;
//                          border-top: 1px solid #e0e0e0;
//                          padding: 10px;
//                      }
                     
//                      .chat-footer input {
//                          flex-grow: 1;
//                          border: none;
//                          padding: 10px;
//                          border-radius: 5px;
//                          margin-right: 10px;
//                          background-color: #ffffff;
//                      }
//                      .chat-footer input::placeholder {
//                          color: #9A9A9A;
//                      }
//                      .chat-footer input:focus-visible {
//                          outline-color: #f1f1f100!important;
//                      }
//                      .chat-footer button {
//                          background-color: #01426300;
//                          color: white;
//                          border: none;
//                          padding: 10px 20px;
//                          border-radius: 5px;
//                          cursor: pointer;
//                      }
//              `;
//              document.head.appendChild(style);
//              document.body.appendChild(chatbotContainer);
//              const chatMessages = document.getElementById('chatMessages');
//              const userMessageInput = document.getElementById('userMessage');
//              const sendButton = document.getElementById('sendButton');
//              const botId = '{$chatbot->id}';
//              const csrfToken = '{$csrfToken}';
     
//              sendButton.addEventListener('click', () => {
//                  const message = userMessageInput.value.trim();
//                  if (message) {
//                      userMessageInput.value = '';
//                      handleUserMessage(message);
//                  }
//              });

//              // Function to handle user messages and fetch the bot's response
//              function handleUserMessage(message) {
//                  appendUserMessage(message);
                 
//                  // fetch('/chatbot/message', {
//                  //     method: 'POST',
//                  //     headers: {
//                  //         'Content-Type': 'application/json',
//                  //         'X-CSRF-TOKEN': csrfToken
//                  //     },
//                  //     body: JSON.stringify({ message: message, bot_id: botId })
//                  // })
//                  // .then(response => response.json())
//                  // .then(data => {
//                  //     const reply = data.reply ? data.reply : 'No reply received';
//                  //     const options = data.options ? data.options : [];
//                  //     appendBotMessage(reply, options);
//                  // })
//                  // .catch(error => console.error('Error:', error));
//              }

//              // Function to append user message to the chat
//            function appendUserMessage(message) {
//                  const userMessageDiv = document.createElement('div');
//                  userMessageDiv.className = 'message user';

//                  const messageTextDiv = document.createElement('div');
//                  messageTextDiv.className = 'text';
//                  messageTextDiv.textContent = message;

//                  userMessageDiv.appendChild(messageTextDiv);
                 
//                  chatMessages.appendChild(userMessageDiv);
//                  chatMessages.scrollTop = chatMessages.scrollHeight;
//              }

//              // Function to append bot message and options to the chat
//              function appendBotMessage(reply, options) {
//                  const botMessageDiv = document.createElement('div');
//                  botMessageDiv.className = 'message bot';
//                  botMessageDiv.innerHTML = reply;

//                  if (options.length > 0) {
//                      const optionsContainer = document.createElement('div');
//                      optionsContainer.className = 'options';
//                      options.forEach(option => {
//                          const button = document.createElement('button');
//                          button.innerText = option.text;
//                          button.onclick = () => handleOptionSelect(option.value);
//                          optionsContainer.appendChild(button);
//                      });
//                      botMessageDiv.appendChild(optionsContainer);
//                  }

//                  chatMessages.appendChild(botMessageDiv);
//                  chatMessages.scrollTop = chatMessages.scrollHeight;
//              }

//              // Function to handle option selection
//              function handleOptionSelect(optionValue) {
//                  appendUserMessage(optionValue);
//                  handleUserMessage(optionValue);
//              }

//               document.getElementById('chat-toggle-btn').addEventListener('click', function() {
//                  var chatContainer = document.getElementById('chat-container');
//                  if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
//                      chatContainer.style.display = 'flex'; // Open the chat
//                  } else {
//                      chatContainer.style.display = 'none'; // Close the chat
//                  }
//              });

//              document.getElementById('close-chat-icon').addEventListener('click', function() {
//                  var chatContainer = document.getElementById('chat-container');
//                  chatContainer.style.display = 'none'; // Close the chat when the close icon is clicked
//              });
//          }
     
//          // Inject the chatbot when the document is ready
//          document.addEventListener('DOMContentLoaded', injectChatbot);
//      })();

        
        


//      ";

//      return response($script)->header('Content-Type', 'application/javascript');
//  }
public function scriptchatbots($id)
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
            
                var botId = $('.question_id').val();
                console.log(botId);
                    const message = userMessageInput.val().trim();
                    if (message) {
                        userMessageInput.val('');
                        handleUserMessage(message,botId);
                    }
                });

                function handleUserMessage(message,botId) {
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
                            bot_id: botId
                        }),
                        success: function(data) {
                            //will work on monday as i have to set the next question id .....
                        var cc = $('.question_id').val(data.reply.question_id);
                        alert(data.reply.question_id);
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
                    console.log(message);
                    

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
          
            //  document.getElementById('chat-toggle-btn').addEventListener('click', function() {
            //     var chatContainer = document.getElementById('chat-container');
            //     if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
            //         chatContainer.style.display = 'flex'; // Open the chat
            //     } else {
            //         chatContainer.style.display = 'none'; // Close the chat
            //     }
            // });

            // document.getElementById('close-chat-icon').addEventListener('click', function() {
            //     var chatContainer = document.getElementById('chat-container');
            //     chatContainer.style.display = 'none'; // Close the chat when the close icon is clicked
            // });
        }
    
        // Inject the chatbot when the document is ready
        document.addEventListener('DOMContentLoaded', injectChatbot);
    })();

       
       


    ";

    return response($script)->header('Content-Type', 'application/javascript');
}

}
