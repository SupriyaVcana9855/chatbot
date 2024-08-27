<?php

namespace App\Http\Controllers;

use App\Models\BotQuestion;
use Illuminate\Http\Request;
use App\Models\ChatBot;

class ChatBotController extends Controller
{
    
    public function botQuestion($id)
    {
        return view('bots/bot-question');
    }
    public function addQuestion(Request $request)
    {
        dd($request);
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);
        $type = $request->type;
        $user = new BotQuestion();
        if($type == 'option')
        {
            $user->option1  = $request->option1;
            $user->option2  = $request->option2;
        }else
        {
            $user->bot_id  = $request->bot_id;
            $user->question  = $request->question;
            $user->answer  = $request->answer;
        }
        $user->save();
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
        // Simple echo reply for demonstration
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

    // public function scriptchatbot($id)
    // {
    //     $chatbot = ChatBot::find($id);
    //     if (!$chatbot) {
    //         return response('Chatbot not found', 404);
    //     }

    //     // Generate the full URL for the logo
    //     $logoUrl = asset('storage/' . $chatbot->logo);

    //     // Fetch the CSRF token
    //     $csrfToken = csrf_token();

    //     // Generate the script with dynamic values
    //     $script = "(function() {

    //     // Function to inject the chatbot HTML and CSS into the page
    //     function injectChatbot() {
    //         const chatbotContainer = document.createElement('div');
    //         chatbotContainer.id = 'chatbotContainer';
    //         chatbotContainer.innerHTML = `
    //             <div id='chatMessages'></div>
    //             <div class='message bot'>{$chatbot->intro_message}</div>
    //             <textarea id='userMessage' placeholder='Type a message...'></textarea>
    //             <button id='sendButton'>Send</button>
    //         `;
    
    //         const style = document.createElement('style');
    //         style.innerHTML = `
    //             /* Add your styles here */

    //             #sendButton{
    //             background:{$chatbot->main_color};
    //             }
    //             #chatbotContainer { 
    //                 position: fixed; 
    //                 bottom: 0; 
    //                 right: 0; 
    //                 width: 300px; 
    //                 border: 1px solid #ccc; 
    //                 // background: {$chatbot->main_color}; /* Dynamically set background color */
    //                 display: flex;
    //                 flex-direction: column;
    //             }
    //             #chatMessages { 
    //                 height: 300px; /* Default height */
    //                 overflow-y: auto;
    //             }
    //             .message {
    //                 padding: 10px; /* Default padding */
    //             }
    //             .bot {
    //                 background: #e0e0e0;
    //             }
    //             .user {
    //                 background: #d1ffd1;
    //             }
    //         `;
    //         document.head.appendChild(style);
    //         document.body.appendChild(chatbotContainer);
    
    //         // Attach event listener to the send button
    //         const chatMessages = document.getElementById('chatMessages');
    //         const userMessageInput = document.getElementById('userMessage');
    //         const sendButton = document.getElementById('sendButton');
    //         const botId = '{$chatbot->id}'; // Set your bot ID here dynamically
    //         const csrfToken = '{$csrfToken}'; // Set CSRF token dynamically
    
    //         sendButton.addEventListener('click', () => {
    //             const message = userMessageInput.value.trim();
    //             if (message) {
    //                 userMessageInput.value = '';
                  
    //                 fetch('/chatbot/message', {
    //                     method: 'POST',
    //                     headers: {
    //                         'Content-Type': 'application/json',
    //                         'X-CSRF-TOKEN': csrfToken
    //                     },
    //                     body: JSON.stringify({ message: message, bot_id: botId })
    //                 })
    //                 .then(response => response.json())
    //                 .then(data => {
    //                     // Ensure data.reply is properly displayed
    //                 const reply = data.reply ? data.reply : 'No reply received';
    //                 console.log(reply);

    //                 //   var datas = document.querySelector('.message').innerHTML =reply
    //                 // console.log(datas);
    //                var html1 = `<div class='message bot'>`;
    //                 var html3 = `</div>`;
    //                 var final = html1 + reply + html3;
    //                 chatMessages.innerHTML += final;

    //                 chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom

    //                 chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom
    //                 })
    //                 .catch(error => console.error('Error:', error));
    //             }
                    
    //         });
    //     }
     
    //     // Inject the chatbot when the document is ready
    //     document.addEventListener('DOMContentLoaded', injectChatbot);
    // })();
    // ";
    //     return response($script)->header('Content-Type', 'application/javascript');
    // }

    public function scriptchatbot($id)
    {
        $chatbot = ChatBot::find($id);
        if (!$chatbot) {
            return response('Chatbot not found', 404);
        }

        // Generate the full URL for the logo
        $logoUrl = asset('storage/' . $chatbot->logo);
        
        // Fetch the CSRF token
        $csrfToken = csrf_token();

        // Generate the script with dynamic values
        $script = "(function() {

            // Function to inject the chatbot HTML and CSS into the page
            function injectChatbot() {
                const chatbotContainer = document.createElement('div');
                chatbotContainer.id = 'chatbotContainer';
                chatbotContainer.innerHTML = `
                    <div id='chatMessages'></div>
                    <div class='message bot'>{$chatbot->intro_message}</div>
                    <textarea id='userMessage' placeholder='Type a message...'></textarea>
                    <button id='sendButton'>Send</button>
                `;
        
                const style = document.createElement('style');
                style.innerHTML = `
                    /* Add your styles here */
                    #sendButton {
                        background: {$chatbot->main_color};
                    }
                    #chatbotContainer { 
                        position: fixed; 
                        bottom: 0; 
                        right: 0; 
                        width: 300px; 
                        border: 1px solid #ccc; 
                        display: flex;
                        flex-direction: column;
                    }
                    #chatMessages { 
                        height: 300px;
                        overflow-y: auto;
                    }
                    .message {
                        padding: 10px;
                    }
                    .bot {
                        background: #e0e0e0;
                    }
                    .user {
                        background: #d1ffd1;
                    }
                    .options button {
                        margin: 5px;
                        padding: 5px 10px;
                        border: none;
                        background: #007bff;
                        color: #fff;
                        cursor: pointer;
                    }
                    .options button:hover {
                        background: #0056b3;
                    }
                `;
                document.head.appendChild(style);
                document.body.appendChild(chatbotContainer);
                const chatMessages = document.getElementById('chatMessages');
                const userMessageInput = document.getElementById('userMessage');
                const sendButton = document.getElementById('sendButton');
                const botId = '{$chatbot->id}';
                const csrfToken = '{$csrfToken}';
        
                sendButton.addEventListener('click', () => {
                    const message = userMessageInput.value.trim();
                    if (message) {
                        userMessageInput.value = '';
                        handleUserMessage(message);
                    }
                });

                // Function to handle user messages and fetch the bot's response
                function handleUserMessage(message) {
                    appendUserMessage(message);
                    
                    fetch('/chatbot/message', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ message: message, bot_id: botId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const reply = data.reply ? data.reply : 'No reply received';
                        const options = data.options ? data.options : [];
                        appendBotMessage(reply, options);
                    })
                    .catch(error => console.error('Error:', error));
                }

                // Function to append user message to the chat
                function appendUserMessage(message) {
                    const userMessageDiv = document.createElement('div');
                    userMessageDiv.className = 'message user';
                    userMessageDiv.textContent = message;
                    chatMessages.appendChild(userMessageDiv);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }

                // Function to append bot message and options to the chat
                function appendBotMessage(reply, options) {
                    const botMessageDiv = document.createElement('div');
                    botMessageDiv.className = 'message bot';
                    botMessageDiv.innerHTML = reply;

                    if (options.length > 0) {
                        const optionsContainer = document.createElement('div');
                        optionsContainer.className = 'options';
                        options.forEach(option => {
                            const button = document.createElement('button');
                            button.innerText = option.text;
                            button.onclick = () => handleOptionSelect(option.value);
                            optionsContainer.appendChild(button);
                        });
                        botMessageDiv.appendChild(optionsContainer);
                    }

                    chatMessages.appendChild(botMessageDiv);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }

                // Function to handle option selection
                function handleOptionSelect(optionValue) {
                    appendUserMessage(optionValue);
                    handleUserMessage(optionValue);
                }
            }
        
            // Inject the chatbot when the document is ready
            document.addEventListener('DOMContentLoaded', injectChatbot);
        })();
        ";

        return response($script)->header('Content-Type', 'application/javascript');
    }

}
