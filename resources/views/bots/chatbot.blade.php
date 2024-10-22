<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='csrf-token' content='{{ $csrfToken }}'>
    <title>Chatbot</title>
    <style>

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
    position: fixed;
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
    background: {{ $chatbot->main_color }};
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

.message .text {
    max-width: 70% !important;
    padding: 10px;
    background-color:{{ $chatbot->question_color }};
    /* border-radius: 15px; */
    position: relative;
    border-radius: 0px 5px 5px 0px;
    color: #606060;
    font-weight: 400;
    line-height: 25px;
   word-wrap: break-word;
}

.message.user .text {
    background-color:{{ $chatbot->answer_color }};
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
.chat-btn {
    display: inline;
}
button.option1Select {
    display: inline-block !important;
    width: auto;
    margin-bottom: 10px;
}
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class='chat-toggle chat-boat-position {{ 'chat-boat-position-' . $chatbot->bot_position }}' id='chatMessages'>
   @if(str_starts_with($chatbot->logo, 'public/'))
        <img src='{{ Storage::url($chatbot->logo) }}' alt='Chat Icon' id='chat-toggle-btn'>
    @else
        <img src='{{ asset($chatbot->logo) }}' alt='Chat Icon' id='chat-toggle-btn'>

     @endif   

        {{-- <img src='{{ Storage::url($chatbot->logo) }}' alt='Chat Icon' id='chat-toggle-btn'> --}}
    </div>
    <div class='chat-container chat-boat-position {{ 'chat-boat-position-' . $chatbot->bot_position }}' id='chat-container'>
        <div class='chat-header'>
            <div class='chat-img'>
              @if(str_starts_with($chatbot->logo, 'public/'))
                    <img src='{{ Storage::url($chatbot->logo) }}' alt='Chat Icon' id='chat-toggle-btn'>
                @else
                    <img src='{{ asset($chatbot->logo) }}' alt='Chat Icon' id='chat-toggle-btn'>
                @endif   
            </div>
            <div>
                <div class='chat-title'>{{ $chatbot->name }}</div>
                <div class='chat-subtitle'>{{ $chatbot->type }}</div>
            </div>
            <div class='icon-head'>
                <div>
                    <img src='{{ asset('assets/images/reload.png')}}'>
                </div>
                <div class='closeicon'>
                    <img src='{{ asset('assets/images/colse.png')}}' id='close-chat-icon'>
                </div>
            </div>
        </div>
        <div class='chat-body'>
            <div class='message bot'>
                <div class='text'><h4>{{ $chatbot->intro_message }}</h4></div>
                    
            </div>
            @if($questions)
                <div class='message bot'>
                    <div class='text'>{{ $questions->question }}</div>
                    <input type="hidden" name="question_id" value="{{ $questions->id }}" class ="question_id">
                </div>
                @if($questions->options)
                        @foreach($questions->options as $options)
                            <div class="chat-btn">
                                <button class = "option1Select" value = "{{ $options }}">{{ $options }}</button>
                            </div>
                        @endforeach
                @endif
            @endif
        </div>
        <div class='chat-footer'>
            <input type='text' id='userMessage' placeholder='Enter your message...'>
            <button><img src='{{ asset('assets/images/fileupload.png')}}' /></button>
            <button id='sendButton'><img src='{{ asset('assets/images/Vector.png')}}' /></button>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const $chatMessages = $('#chatMessages');
            const $userMessageInput = $('#userMessage');
            const $sendButton = $('#sendButton');
             const $chatBody = $('.chat-body');
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            $('.option1Select,.option2Select').on('click',function(){
                var data  = $(this).val();
                $userMessageInput.val(data);
            })
            $sendButton.on('click', function() {
           
            var botId = $('.question_id').val();
                const message = $userMessageInput.val().trim();
                if (message) {
                    $userMessageInput.val('');
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
                $chatBody.append(userMessageDiv);
                $chatMessages.scrollTop($chatMessages.prop('scrollHeight'));
            }

            function appendBotMessage(reply, options) {

                const botMessageDiv = $('<div>', { class: 'message bot' })
                    .append($('<div>', { class: 'text', text: reply }));
                $chatBody.append(botMessageDiv);
                $chatMessages.scrollTop($chatMessages.prop('scrollHeight'));
            }

            function handleOptionSelect(optionValue) {
                appendUserMessage(optionValue);
                handleUserMessage(optionValue);
            }

            $('#chat-toggle-btn').on('click', function() {
                const $chatContainer = $('#chat-container');
                $chatContainer.toggle();
            });

            $('#close-chat-icon').on('click', function() {
                $('#chat-container').hide();
            });
        });
    </script>
</body>

</html>
