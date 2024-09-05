<?php

namespace App\Http\Controllers;

use App\Models\BotQuestion;
use App\Models\BotQuestionFlow;
use Illuminate\Http\Request;
use App\Models\ChatBot;
use Illuminate\Support\Facades\Validator;

class ChatBotController extends Controller
{
    
    public function editPrefrence(Request $request)
    {
       return 1;
    }    
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
    public function addQuestionFlow(Request $request)
    {
        $addFlow = new BotQuestionFlow();
        $addFlow->bot_question_id = $request->question_1;
        $addFlow->bot_question_id2 = $request->question_2;
        $addFlow->save();
        return 1;
    }
    public function botFlow($id)
    {
        $questionFlowIds = BotQuestionFlow::pluck('bot_question_id2')->toArray();
        $questionsNotInFlow = BotQuestion::where('chat_bot_id', $id)
            ->whereNotIn('id', $questionFlowIds)
            ->get();
        return view('bots/botflow',compact('id','questionsNotInFlow'));
    }
    public function getAnswer(Request $request)
    {
        $questions = BotQuestion::find($request->id);
        return response()->json($questions);
    }

    public function botQuestion($id)
    {
        return view('bots/bot-question',compact('id'));
    }
    public function addQuestion(Request $request)
    {
        $question = strtolower($request->question);
        $user = new BotQuestion();
        $question_type = '';
        if (strpos($question, 'email') !== false) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
        } elseif (strpos($question, 'contact') !== false) {
            $validator = Validator::make($request->all(), [
                'contact' => 'required|regex:/^(\+\d{1,3}[- ]?)?\d{10}$/', // Example regex for phone numbers
            ]);
        } elseif (strpos($question, 'name') !== false) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2',
            ]);
        }

        $type = $request->type;
        if($type == 'option')
        {
            $user->option1  = $request->option1;
            $user->option2  = $request->option2;
        }else
        {
            $user->answer  = $request->answer; 
        }
        $user->answerType = $question_type;
        $user->chat_bot_id  = $request->bot_id;
        $user->question  = $request->question;
        $user->save();
        return redirect()->back();
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

    public function handleMessage(Request $request)
    {
        $message = $request->input('message');
        $botId = $request->input('bot_id');

        // Logic for processing the message and generating a reply
        $bot = ChatBot::find($botId);
        $reply = $this->generateReply($message, $bot);

        return response()->json(['reply' => $reply]);
    }

    
    private function generateReply($message, $bot)
    {
        if ($message == "hello") {
        return "Hello !!!.";
        }
         else {
            return "I'm not sure how to respond to that. Please wait while I connect you to a customer service representative.";
        }
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

    public function scriptchatbot($id)
    {
        $chatbot = ChatBot::find($id);
        // dd($chatbot->botQuestions->first());
        if (!$chatbot) {
            return response('Chatbot not found', 404);
        }
    
        // Generate the full URL for the logo
        $logoUrl = asset('storage/' . $chatbot->logo);
    
        // Fetch the CSRF token
        $csrfToken = csrf_token();
    
        return view('bots.chatbot', compact('chatbot', 'logoUrl', 'csrfToken'));
    }
    

  

}
