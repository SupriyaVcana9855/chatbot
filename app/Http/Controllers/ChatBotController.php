<?php
// app/Http/Controllers/ChatBotController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatBot;

class ChatBotController extends Controller
{
    // public function getChatbotScript($id)
    // {
    //     $chatbot = ChatBot::find($id);
    //     if (!$chatbot) {
    //         return response('Chatbot not found', 404);
    //     }

    //     // Generate the full URL for the logo
    //     $logoUrl = asset('storage/' . $chatbot->logo);

    //     $script = "
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
    //                 bubbleAvatarUrl: 'https://custpostimages.s3.ap-south-1.amazonaws.com/11/1707999308430.jpg', // Use the full URL for the logo
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
    //             };
                
    //             if (typeof BotManChatWidget !== 'undefined') {
    //                 botmanChatWidget = new BotManChatWidget();
    //                 botmanChatWidget.init();

    //                 // Adding logo and name to the header after the widget is initialized
    //                 setTimeout(function() {
    //                     const header = document.querySelector('.botman-chat-widget-header');
    //                     if (header) {
    //                         header.style.background = 'green';
    //                         header.style.height = '40px';
    //                         header.style.lineHeight = '30px';
    //                         header.style.fontSize = '20px';
    //                         header.style.display = 'flex';
    //                         header.style.justifyContent = 'space-between';
    //                         header.style.padding = '5px 0px 5px 20px';
    //                         header.style.fontFamily = 'Lato, sans-serif';
    //                         header.style.color = 'rgb(255, 255, 255)';
    //                         header.style.cursor = 'pointer';
    //                         header.style.boxSizing = 'content-box';

    //                         const logo = document.createElement('img');
    //                         logo.src = 'https://custpostimages.s3.ap-south-1.amazonaws.com/11/17079993084302.jpg';
    //                         logo.alt = '{$chatbot->name} Logo';
    //                         logo.style.height = '30px'; // Adjust size as needed

    //                         const nameDiv = document.createElement('div');
    //                         nameDiv.style.display = 'flex';
    //                         nameDiv.style.alignItems = 'center';
    //                         nameDiv.style.padding = '0px 30px 0px 0px';
    //                         nameDiv.style.fontSize = '15px';
    //                         nameDiv.style.fontWeight = 'normal';
    //                         nameDiv.style.color = 'rgb(51, 51, 51)';
    //                         nameDiv.innerText = '{$chatbot->name}';

    //                         const closeButton = document.createElement('div');
    //                         closeButton.innerHTML = `
    //                            <img src='https://custpostimages.s3.ap-south-1.amazonaws.com/11/17079993084301.jpg'>

    //                         `;

    //                         header.innerHTML = ''; // Clear existing header content
    //                         header.appendChild(logo);
    //                         header.appendChild(nameDiv);
    //                         header.appendChild(closeButton);
    //                     }
    //                 }, 1000); // Delay to ensure the widget is fully loaded
    //             } else {
    //                 console.error('BotManChatWidget is not defined');
    //             }
    //         },
    //         t.onerror=function(){
    //             console.error('Failed to load the BotMan web widget script.');
    //         },
    //         e=a.getElementsByTagName('script')[0],e.parentNode.insertBefore(t,e))
    //     }();
    //     ";

    //     return response($script)->header('Content-Type', 'application/javascript');
    // }
    // public function getChatbotScript($id)
    // {
    //     $chatbot = ChatBot::find($id);
    //     if (!$chatbot) {
    //         return response('Chatbot not found', 404);
    //     }
    
    //     // Generate the full URL for the logo
    //     $logoUrl = asset('storage/' . $chatbot->logo);
    
    //     $script = "
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
    //                 bubbleAvatarUrl: '{$logoUrl}', // Use the full URL for the logo
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
    //             };

    //         },
    //         t.onerror=function(){
    //             console.error('Failed to load the BotMan web widget script.');
    //         },
    //         e=a.getElementsByTagName('script')[0],e.parentNode.insertBefore(t,e))
    //     }();

    //     setTimeout(() => {
    //     console.log('ele========>>>>>>>', document.querySelector('.desktop-closed-message-avatar'))
    //      document.querySelector('.desktop-closed-message-avatar').addEventListener('click',function(){
    //      setTimeout(() => {
         
    //        const header = document.querySelector('#botmanWidgetRoot > div:first-child > div:first-child');

    //        console.log('headera', header)

    //         header.append(`<img src='https://custpostimages.s3.ap-south-1.amazonaws.com/11/1707999308430.jpg'>');
    //      },1000)
         
    //     });
    //     },5000) ";
    
    //     return response($script)->header('Content-Type', 'application/javascript');
    // }

     
    public function getChatbotScript($id)
    {
        $chatbot = ChatBot::find($id);
        if (!$chatbot) {
            return response('Chatbot not found', 404);
        }
        // Generate the full URL for the logo
        // $logoUrl = asset('storage/' . $chatbot->logo);
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
                        placeholderText: 'Type a message...',
                        font: '{$chatbot->font}',
                        fontSize: '{$chatbot->font_size}',
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
                            console.log('headera', header);
                            if (header) {
                                const img = document.createElement('img');
                                img.src = '{$chatbot->logo}';
                                img.alt = 'Chatbot Logo';
                                img.className = 'ChatbotLogo';

                                img.style.height = '30px'; // Adjust as needed
                                img.style.marginRight = '10px'; // Adjust as needed
                                header.insertBefore(img, header.firstChild);
                                
                                $('.desktop-closed-message-avatar').remove(img);

                                // Add an event listener to the logo to remove it on click
                                // img.addEventListener('click', function() {
                                //          console.log('remove child',img);
                                //     header.removeChild(img);
                                //     const header1 = document.querySelector('#botmanWidgetRoot > div:first-child > div:first-child');


                                //     console.log('header2',header1);
                                // });
                            }
                                    // console.log('headerssssss',header1);

                        });
                    }

                    const observer = new MutationObserver(function(mutationsList, observer) {
                        for (let mutation of mutationsList) {
                            if (mutation.type === 'childList') {
                                mutation.addedNodes.forEach(function(node) {
                                    console.log('enter-node', node);
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

                },
                t.onerror=function(){
                    console.error('Failed to load the BotMan web widget script.');
                },
                e=a.getElementsByTagName('script')[0],e.parentNode.insertBefore(t,e))
            }();
        ";
        return response($script)->header('Content-Type', 'application/javascript');
    }


    
    public function websiteChat(){
        return view('bots.website-bot');
    }
}
