@extends('layout.app')

@section('content')
<style>
    .botmain {
        margin-top: 135px !important;
    }

    .botmain h4 {
        font-family: Inter;
        font-size: 20px;
        font-weight: 500px;
        color: #777777;
        font-size: 25px;
    }

    .tab-btn button {
        color: #005B96;
        font-size: 20px;
        font-weight: 500;
        margin-left: 5px;
        padding: 12px 25px;
        width: 216px;
        border: 2px solid #005B96;
    }

    .tab-btn button.active {
        /* background-color:#000000!important; */
        background: linear-gradient(90deg, #005B96 0%, #00315F 100%) !important;
        color: #ffffff;
    }

    .set-recent-heading {
        display: flex;
        align-content: center;
        align-items: center;
        justify-content: space-between;
    }

    .set-recent-heading h3 {
        font-size: 22px;
        font-weight: 500;
        color: #005B96;
    }

    .set-recent-heading img {
        width: 20px;
        height: 20px;
    }

    .bot-search {
        display: flex;

    }

    .bot-search button {
        padding: 8px 10px;
        border-radius: 5px 0px 0px 5px;
        background-color: #fff;
        border: 2px solid #C6C6C6;
        border-right: 0px;
    }


    .bot-search input.gsearch {
        padding: 8px 10px;
        border-radius: 0px 5px 5px 0px;
        background-color: #fff;
        border: 2px solid #C6C6C6;
        width: 100%;
        border-left: 0px;
    }

    input.gsearch::placeholder {
        color: #C6C6C6;
    }

    .siderbar-border {
        border-right: 3px solid;
        border-color: #ECECEC;

    }

    .user-data h4 {
        font-size: 18px;
        font-weight: 600;
        font-family: inter;
        color: #005B96;

    }

    .user-data p {
        color: #777777;
        font-size: 14px;

    }

    .user-icon {
        text-align: center;
        flex-grow: 1;
    }

    .user-icon span {
        background-color: #005B96;
        padding: 10px;
        border-radius: 5px;
        color: #fff;
    }

    .bot-search input:focus-visible {
        outline: 0px;
    }

    .user-data {
        cursor: pointer;
    }

    .shared h5 {
        font-size: 25px;
        font-weight: 600;
        color: #005B96;
    }

    .shared {
        margin-top: 40px;
        margin-bottom: 20px;
    }

    .shared img {
        width: 45% !important;
        height: auto !important;
        margin-bottom: 20px;
        margin-right: 10px;
    }

    /* chat section css  */
    .user-hearder h4 {
        color: #005B96;
        font-size: 18px;
        font-weight: 600;
    }

    .user-hearder p {
        color: #777777
    }

    .chat-body {
        flex: 1;
        padding: 15px;
        overflow-y: auto;

    }

    .message {
        margin-bottom: 20px;
        display: flex;
        align-items: flex-end;
    }

    .message .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #ccc;
        margin-right: 10px;
        overflow: hidden;
    }

    .message .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .message .text {
        max-width: 70%;
        padding: 12px 15px;
        border-radius: 20px;
        background-color: #E7F5FF;
        position: relative;
        font-size: 18px;
        color: #777777;

    }

    .message.sent {
        justify-content: flex-end;
    }

    .message.sent .text {
        background-color: #005B96;
        ;
        color: #fff;
        text-align: right;
    }

    .message.sent .avatar {
        order: 2;
        margin-left: 10px;
        margin-right: 0;
    }

    .message.sent .text img {
        max-width: 100%;
        border-radius: 10px;
    }

    .message .time {
        font-size: 10px;
        color: #aaa;
        text-align: center;
        margin: 10px 0;
    }

    .chat-footer {
        padding: 15px;
        display: flex;
        align-items: center;

    }

    .chat-footer .icons {
        display: flex;
        align-items: center;
        margin-right: 10px;
        gap: 10px;
    }

    .chat-footer .icons img {
        /* width: 20px;
            height: 20px; */
        cursor: pointer;
    }

    .chat-footer input[type="text"] {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #ccc;
        outline: none;
        font-size: 14px;
    }

    .chat-footer button {
        padding: 10px 20px;
        color: #fff;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        outline: none;
        margin-left: 10px;
        background-color: #00000000;
    }

    .chat-footer input {
        border: 2px solid #C6C6C6;
        border-radius: 6px;
    }

    .info-section img {
        width: 100px;
        height: 100px;
    }

    .boat-info {
        font-family: 'Inter';
        font-weight: 500;
        font-size: 17px;
        color: #8b8b8b;
        line-height: 15px;
    }

    .info-section h5 {
        font-family: Inter;
        font-size: 18px;
        font-weight: 700;
        color: #005B96;
        padding: 0px;
        margin-top: 18px;
    }

    .info-section p {
        font-size: 12px;
        color: #777777;
        padding: 0px;
    }

    /* chat section css  */
</style>
<div>

    <div class="row">
        <div class="col-12 botmain">
            <div class="row set-bot-tab ">
                <div class="col-4 ">
                    <h4>Bot Chat</h4>

                </div>

                <div class="col-8 tab-btn">
                    <ul class="nav nav-pills mb-3 d-flex justify-content-end" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Inbox View</button>
                        </li>

                        <li class="nav-item download-chat-history" role="presentation" >
                            <button class="nav-link" id="pills-contact-tab" data-id="" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Download data</button>
                        </li>
                    </ul>
                </div>


                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="row">
                            <div class="col-3 user-sidebar siderbar-border">
                                <div class="set-recent-heading">
                                    <h3>Recent Chats</h3>
                                    <img src="{{asset('assets/images/editicon.png')}}">
                                </div>
                                <div>
                                    <form class="bot-search mt-3">
                                        <button type="submit"><i class="fa-solid fa-magnifying-glass" style="color: #c6c6c6;"></i></button>
                                        <input type="search" class="gsearch" name="gsearch" placeholder="Search">
                                    </form>
                                </div>
                                <div>
                                </div>
                 
                                @if (count($botChats) > 0)
                                @foreach ($botChats as $bots)
                                <div class="user-data mt-4 d-flex userChatData" data-id="{{$bots->id ?? ''}}">
                                    <div class="user-image">
                                        <img src="{{asset('assets/images/userk.png')}}" alt="" class="mr-2">
                                    </div>
                                    <div>
                                        <h4>{{$bots->name ?? ''}}</h4>
                                        <p>{{$bots->email ?? ''}}</p>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <p>Chat Data Not Found</p>
                                @endif
                            </div>

                            <div class="col-9 siderbar-border" id="user-chat-section">
                                <div class="set-chat-section">
                                    <div class="set-user-imgSection d-flex justify-content-between">
                                        <div class="user-hearder  d-flex">
                                            <div class="inner-data">
                                                <img src="{{asset('assets/images/userk.png')}}" alt="" class="mr-5">
                                            </div>
                                            <div class="set-chat-icon">
                                                <h4 id="user_name">Kapil Kapoor</h4>
                                                <p>Are Active</p>
                                            </div>
                                        </div>
                                        <div class="header-icon">
                                            <img src="{{asset('assets/images/call.png')}}" alt="" class="ml-1">
                                            <img src="{{asset('assets/images/video.png')}}" alt="" class="ml-1">
                                            <img src="{{asset('assets/images/user-a.png')}}" alt="" class="ml-1">
                                        </div>
                                    </div>

                                    <div class="chat-body">
                                    </div>

                                    <div class="chat-footer">
                                        <div class="icons">
                                            <img src="{{asset('assets/images/imoje.png')}}" alt="Emoji Icon">
                                            <img src="{{asset('assets/images/file.png')}}" alt="Emoji Icon">
                                            <img src="{{asset('assets/images/camera.png')}}" alt="Attachment Icon">
                                            <img src="{{asset('assets/images/mic.png')}}" alt="Camera Icon">
                                        </div>
                                        <input type="text" placeholder="Enter Your Text Here" readonly>
                                        <button> <img src="{{asset('assets/images/send.png')}}"></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
    $('.download-chat-history').on('click', function() {
        var chatbotId = $('#pills-contact-tab').data('id');
        if (!chatbotId) {
            alert("No Chatbot ID found!");
            return;
        }

        // Perform AJAX request to fetch the PDF
        $.ajax({
            url: '{{ route("download-history-pdf", ":id") }}'.replace(':id', chatbotId),
            method: 'GET',
            xhrFields: {
                responseType: 'blob' // Indicate the response should be a Blob (binary data)
            },
            success: function(data, status, xhr) {
                // Create a new Blob from the response data (PDF content)
                var blob = new Blob([data], {
                    type: 'application/pdf'
                });

                // Create a temporary link element to trigger the download
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "chat-history.pdf"; // Define the name of the downloaded file
                link.click(); // Programmatically trigger the download
            },
            error: function(xhr, status, error) {
                console.error('Error downloading PDF:', error);
                alert("Error downloading chat history.");
            }
        });
    });
});

    
    // Get all elements with the 'userChatData' class
    let chatElements = document.getElementsByClassName('userChatData');

    // Loop through the HTMLCollection and attach the event listener to each element
    for (let i = 0; i < chatElements.length; i++) {
        chatElements[i].addEventListener('click', function() {
            // Get the data-id attribute from the clicked element
            var bot_user_id = this.getAttribute('data-id');
            // alert(bot_user_id);
            // Make an AJAX request to fetch chat data
            $.ajax({
                url: "{{route('getBotChatData')}}" + '/' + bot_user_id,
                type: 'GET',
                success: function(response) {
                    const getBotChatData = response.data;
                    // console.log(getBotChatData);
                    // Clear the chat body before appending new messages
                    const chatBody = document.querySelector('.chat-body');
                    chatBody.innerHTML = '';

                    // Set the user's name from the response
                    document.getElementById('user_name').textContent = getBotChatData[0].name ?? '';
                    document.getElementById('pills-contact-tab').setAttribute('data-id',bot_user_id);

                    // Loop through the response and append each message dynamically
                    $.each(getBotChatData[0].question_answer, function(key, value) {

                        // console.log('value',value);
                        // Append bot question if available
                        if (value.bot_question) {
                            const botMessage = `
                            <div class="message">
                                <div class="text">${value.bot_question.question}</div>
                            </div>
                        `;
                            chatBody.insertAdjacentHTML('beforeend', botMessage);
                        }


                         if (value.bot_question.chat_bot_id != '0' && value.bot_question.answer != null)
                         {
                             const userMessage = `
                               <div class="message sent">
                                 <div class="text">${value.bot_question.answer}</div>
                             </div>
                         `;
                             chatBody.insertAdjacentHTML('beforeend', userMessage);
                         }else{

                            if (value.answer) {
                                const userMessage = `
                                <div class="message sent">
                                    <div class="text">${value.answer}</div>
                                </div>
                            `;
                                chatBody.insertAdjacentHTML('beforeend', userMessage);
                            }
                         }
                    });
                
                    // Check if last_response exists and has at least one element
                    if (getBotChatData[0].last_response && getBotChatData[0].last_response.length > 0) {
                        // Get the last message in the array
                        let lastResponse = getBotChatData[0].last_response[getBotChatData[0].last_response.length - 1];

                        // Check for last question
                        if (lastResponse.question) {
                            // Display the last question
                            const userMessage = `
                                <div class="message sent">
                                    <div class="text">${lastResponse.question}</div>
                                </div>
                            `;
                            chatBody.insertAdjacentHTML('beforeend', userMessage);
                        }

                        console.log(lastResponse.answer);
                        console.log(lastResponse.answer);

                        // Check if lastResponse.answer contains a link and extract the URL
                        if (lastResponse.answer) {
                            // Regular expression to match the href URL inside the anchor tag
                            const urlRegex = /href="([^"]*)"/;
                            const match = lastResponse.answer.match(urlRegex);

                            if (match && match[1]) {
                                // Extracted URL
                                const extractedUrl = match[1];
                                console.log(extractedUrl); // Outputs the extracted URL

                                // Display the extracted URL (instead of the full anchor tag)
                                const botMessage = `
                                    <div class="message">
                                        <div class="text">${extractedUrl}</div>
                                    </div>
                                `;
                                chatBody.insertAdjacentHTML('beforeend', botMessage);
                            }
                        }
                    }


                    // Show the chat and details sections
                    document.getElementById('user-chat-section').style.display = 'block';
                    document.getElementById('user-details-section').style.display = 'block';
                },
                error: function(err) {
                    console.error("Error fetching chat data", err);
                }
            });
        });
    }

    // Initially hide the chat and details sections
    document.getElementById('user-chat-section').style.display = 'none';
    document.getElementById('user-details-section').style.display = 'none';
</script>



@endsection