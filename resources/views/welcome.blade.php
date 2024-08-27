<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatBot</title>
        <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Ensure CSRF token meta tag is present -->

  <style>
    /* Add your styles here */
    #chatbotContainer { 
        position: fixed; 
        bottom: 0; 
        right: 0; 
        width: 300px; 
        border: 1px solid #ccc; 
        background: {{ $bot->main_color }}; 
    }
    #chatMessages { 
        height: {{ $bot->font_size }}; 
        overflow-y: auto; 
    }
    .message { 
        padding: {{ $bot->radius }}; 
    }
    .bot { 
        background: #e0e0e0; 
    }
    .user { 
        background: #d1ffd1; 
    }
</style>

</head>
<body>
    <div id="chatbotContainer">
        <div id="chatMessages"></div>
        <textarea id="userMessage" placeholder="Type a message..."></textarea>
        <button id="sendButton">Send</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatMessages = document.getElementById('chatMessages');
            const userMessageInput = document.getElementById('userMessage');
            const sendButton = document.getElementById('sendButton');
            const botId = '{{ $bot->id }}'; // Pass bot ID to JavaScript

            sendButton.addEventListener('click', () => {
                const message = userMessageInput.value.trim();
                if (message) {
                    chatMessages.innerHTML += `<div class="message user">${message}</div>`;
                    userMessageInput.value = '';

                    fetch('/chatbot/message', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: message, bot_id: botId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        chatMessages.innerHTML += `<div class="message bot">${data.reply}</div>`;
                        chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>
</body>
</html>
