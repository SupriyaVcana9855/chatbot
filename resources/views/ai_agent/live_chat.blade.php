@extends('layout.app')
@section('content')

<div class="boxpadding ">
    <div class="row searchi-section mt-4">
        <div class="col-2 set-boat-heading">
            <h6>AI Agents</h6>
        </div>
    </div>
    <div class="col-6 siderbar-border" id="user-chat-section">
        <div class="set-chat-section">
            <div class="set-user-imgSection d-flex justify-content-between">
                <div class="user-hearder  d-flex">
                    <div class="inner-data">
                        <img src="{{asset('assets/images/userk.png')}}" alt="" class="mr-5">
                    </div>
                    <div class="set-chat-icon">
                        <h4>Kapil Kapoor</h4>
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
                <div class="message">
                    <div class="avatar"><img src="avatar1.png" alt="User Avatar"></div>
                    <div class="text">Hello</div>
                </div>
                <div class="message">
                    <div class="avatar"><img src="avatar1.png" alt="User Avatar"></div>
                    <div class="text">Lorem Ipsum</div>
                </div>
                <div class="message">
                    <div class="avatar"><img src="avatar1.png" alt="User Avatar"></div>
                    <div class="text">Lorem Ipsum Dolor text dummy</div>
                </div>
                <div class="message sent">
                    <div class="text">Hi</div>
                    <div class="avatar"><img src="avatar2.png" alt="User Avatar"></div>
                </div>
                <div class="message sent">
                    <div class="text">Lorem Ipsum</div>
                    <div class="avatar"><img src="avatar2.png" alt="User Avatar"></div>
                </div>
                <div class="message sent">
                    <div class="text">Lorem Ipsum Dolor text dummy</div>
                    <div class="avatar"><img src="avatar2.png" alt="User Avatar"></div>
                </div>
                <div class="message sent">
                    <div class="text">
                        <div>Look at the picture</div>
                    </div>
                    <div class="avatar"><img src="avatar2.png" alt="User Avatar"></div>
                </div>
                <div class="avtar-img text-end mt-4">
                    <img src="{{asset('assets/images/chatuserimg.png')}}" alt="" class="mr-2">
                </div>
                <div class="message">
                    <div class="avatar"><img src="avatar1.png" alt="User Avatar"></div>
                    <div class="text">Thank You</div>
                </div>

            </div>
            <div class="chat-footer">
                <div class="icons">
                    <img src="{{asset('assets/images/imoje.png')}}" alt="Emoji Icon">
                    <img src="{{asset('assets/images/file.png')}}" alt="Emoji Icon">
                    <img src="{{asset('assets/images/camera.png')}}" alt="Attachment Icon">
                    <img src="{{asset('assets/images/mic.png')}}" alt="Camera Icon">
                </div>
                <form action="{{route('message')}}" method="post">
                    <input type="text" name="message" placeholder="Enter Your Text Here">
                    <button type="submit" name="submit"><img src="{{asset('assets/images/send.png')}}"></button>
                </form>
            </div>
        </div>
    </div>
</div>


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

<SCript>
    $(document).ready(function() {
        $('#customSwitch1').change(function() {
            $('#switch-label').text(this.checked ? '1' : '0');
            var status = this.checked ? '1' : '0';

            // Send the AJAX request to change status in the backend
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/changeStatus',
                data: {
                    'status': status,
                    'user_id': user_id
                },
                success: function(data) {
                    console.log(data.success); // Handle success response
                }
            });
        });
    });
</SCript>
@endsection