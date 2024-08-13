<div class="container-fluid set-header">
        <div class="row">
            <div class="col-2 set-logo">
                <img src="{{asset('/assets/images/logo.png')}}" alt="">
            </div>
            <div class="col-11 set-icon">
                <div class="search-box set-search-color">
                    <button class="btn-search"><i class="fa-solid fa-magnifying-glass"
                            style="color: #777777;"></i></button>
                    <input type="text" class="input-search" placeholder="Type to Search...">
                </div>
                <div class="set-line"><img src="{{asset('/assets/images/notifaction.png')}}" alt=""></div>
                <!-- profile drop down  -->
                <div class="dropdown ">
                    <div class="dropbtn set-profileimg ">
                        <h4>Kapil Kapoor</h4>
                        <p>kapil@vcanaglobal.com</p>
                    </div>
                    <div class="set-profileimg"><img src="{{asset('/assets/images/proimg.png')}}"><i class="fa-solid fa-angle-down"
                            style="color: #777777;"></i></div>
                    <div class="dropdown-content">
                        <div class="chat-status ">
                            <div class=" set-statuse ">
                                <h5>Live Chat Status<h5>

                                        <input type="radio" class="form-check-input" id="radio1" name="optradio"
                                            value="option1" checked>
                                        <label class="form-check-label1" for="radio1">Online</label>
                                        <input type="radio" class="form-check-input2" id="radio1" name="optradio"
                                            value="option1" checked>
                                        <label class="form-check-label2" for="radio1">Away</label>

                            </div>
                            <div class="chat-status set-statuse">
                                <h5>Account</h5>

                            </div>
                            <div class="chat-status set-statuse">
                                <h5>Help</h5>
                            </div>
                            <div class="chat-status set-statuse">
                                <h5>Plans</h5>
                                <h3>PRO-TRIAL <span>End In 10 Days</span></h3>
                            </div>
                        </div>
                        <div class="chat-status set-statusee">
                            <h6> <a href="{{route('logout')}}">Logout</a></h6>
                        </div>
                    </div>

                </div>
                <!-- profile drop down end -->
            </div>

        </div>

    </div>
    <style>
        .set-header {
            position: fixed;
            padding-right:50px !important;
            height: 100px;
            z-index:99999;
            background : #f4f4f4 !important;
        }
    </style>