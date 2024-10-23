<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive WhatsApp-like Chat UI</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f3f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .chat-container {
            width: 100%;
            height: 100vh;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            border-radius: 0;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        /* Chat header */
        .chat-header {
            background-color: #4a90e2;
            /* Blue background */
            color: #fff;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ddd;
            margin-right: 15px;
        }

        h4 {
            margin: 0;
        }

        p {
            margin: 5px 0 0;
            font-size: 14px;
        }

        /* Chat area */
        .chat-box {
            flex: 1;
            padding: 20px;
            background-color: #e6f1f5;
            /* Light blue background */
            overflow-y: scroll;
        }

        .message {
            margin-bottom: 20px;
            display: flex;
        }

        .message.sent {
            justify-content: flex-end;
        }

        .message.sent .message-bubble {
            background-color: #dcf8c6;
            /* Light green for sent messages */
        }

        .message.received .message-bubble {
            background-color: #fff;
        }

        .message-bubble {
            max-width: 70%;
            padding: 15px;
            border-radius: 10px;
            position: relative;
            word-wrap: break-word;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .timestamp {
            font-size: 10px;
            color: #999;
            margin-top: 5px;
            text-align: right;
        }

        /* Chat input area */
        .chat-input {
            background-color: #f0f0f0;
            padding: 15px;
            display: flex;
            align-items: center;
            border-top: 1px solid #ddd;
        }

        .chat-input input {
            flex: 1;
            padding: 10px 15px;
            border: none;
            border-radius: 25px;
            background-color: #fff;
            margin-right: 10px;
        }

        .chat-input button {
            background-color: #4a90e2;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .chat-input button:hover {
            background-color: #357ab8;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .chat-container {
                max-width: 100%;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                margin-right: 10px;
            }

            .chat-header {
                padding: 15px;
            }

            .chat-box {
                padding: 15px;
            }

            .chat-input {
                padding: 10px;
            }

            .chat-input input {
                padding: 8px 12px;
            }

            .chat-input button {
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .chat-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .user-avatar {
                width: 35px;
                height: 35px;
                margin-right: 8px;
            }

            .chat-input input {
                padding: 6px 10px;
            }

            .chat-input button {
                padding: 6px;
            }
        }
    </style>
</head>

<body>

    <div class="chat-container">
        <!-- Chat header -->
        <div class="chat-header">
            <div class="user-info">
          
                <div class="user-avatar"></div>
                <div>
                    <h4>{{$data[0]['bot']->name}}</h4>
                    <p>Online</p>
                </div>
            </div>
        </div>

        <!-- Chat messages area -->
        <div class="chat-box">
            <div class="message received">
                <div class="message-bubble">
                   {{$data[0]->bot->intro_message}}
                </div>
            </div>
            @foreach ($data[0]['questionAnswer'] as $chat)
            <div class="message received">
                <div class="message-bubble">
                    {{$chat->botQuestion->question ?? ''}}
                    <!-- <div class="timestamp"> {{ isset($chat->created_at) ? \Carbon\Carbon::parse($chat->created_at)->format('Y-m-d h:i a') : '' }}</div> -->
                </div>
            </div>

            <div class="message sent">
                <div class="message-bubble">
                    {{$chat->answer}}
                    <!-- <div class="timestamp">{{ isset($chat->created_at) ? \Carbon\Carbon::parse($chat->created_at)->format('Y-m-d h:i a') : '' }}</div> -->
                </div>
            </div>
            @endforeach

            <div class="message received">
                <div class="message-bubble">
                    <div class="text">Thank You</div>
                </div>
            </div>

        </div>

        <!-- Chat input -->
        <!-- <div class="chat-input">
    <input type="text" placeholder="Type a message...">
    <button>&#9658;</button>
  </div> -->
    </div>

</body>

</html>