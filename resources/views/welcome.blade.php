<?php
$count =0;
for($off =0;$off<4;$off++)
{
    if($count ==0)
    {
        $count = $off+1;
    }else
    {
        $count=$off+2;
    }
    for($off1 =0;$off1<$count;$off1++)
    {
        echo "*";
    }
    echo "<br>";
}


for($off =1;$off<=4;$off++)
{
    for($off1 =1;$off1<=$off+1;$off1++)
    {
        if($off1%2 == 0)
        {
            echo "_";
        }else
        {
            echo "*";
        }
    }
    echo "<br>";
}
?>




{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Template</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .chatbot-container {
            width: 375px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .chatbot-header {
            background-color: #0054F5;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
        }

        .chatbot-header .avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0054F5;
        }

        .chatbot-header .title {
            flex-grow: 1;
            margin-left: 10px;
            font-weight: bold;
        }

        .chatbot-header .options {
            display: flex;
            align-items: center;
        }

        .chatbot-header .options i {
            margin-right: 10px;
            cursor: pointer;
        }

        .chatbot-body {
            padding: 15px;
            height: 300px;
            overflow-y: auto;
        }

        .message {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .message.bot .avatar {
            margin-right: 10px;
        }

        .message.bot .text {
            background-color: #E1F1FF;
            color: #000;
        }

        .message .text {
            max-width: 75%;
            padding: 10px;
            border-radius: 10px;
            font-size: 14px;
        }

        .message img {
            max-width: 100%;
            border-radius: 10px;
            margin-top: 10px;
        }

        .message .options {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
        }

        .message .options button {
            background-color: #0054F5;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .chatbot-footer {
            padding: 10px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
        }

        .chatbot-footer input {
            flex-grow: 1;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid #f0f0f0;
            margin-right: 10px;
        }

        .chatbot-footer button {
            background-color: #0054F5;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 50%;
            cursor: pointer;
        }

        .chatbot-container .powered-by {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="chatbot-container">
        <div class="chatbot-header">
            <div class="avatar"><i class="fa-solid fa-user"></i></div>
            <div class="title">Survey Bot</div>
            <div class="options">
                <i class="fa-solid fa-sync-alt"></i>
                <i class="fa-solid fa-ellipsis-v"></i>
            </div>
        </div>
        <div class="chatbot-body">
            <div class="message bot">
                <div class="avatar"><i class="fa-solid fa-user"></i></div>
                <div class="text">Can I have two minutes? A Small survey on smartbot.</div>
            </div>
            <div class="message bot">
                <img src="https://via.placeholder.com/300x150" alt="Survey Image">
                <div class="options">
                    <button>Yes</button>
                    <button>No</button>
                </div>
            </div>
        </div>
        <div class="chatbot-footer">
            <input type="text" placeholder="Enter your message...">
            <button><i class="fa-solid fa-paper-plane"></i></button>
        </div>
        <div class="powered-by">
            Powered by <a href="#">SmartBot</a>
        </div>
    </div>
</body>
</html> --}}
