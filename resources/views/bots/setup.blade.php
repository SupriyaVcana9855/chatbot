@extends('layout.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/setup.css') }}">

<div class="boxpadding">

    <div>

        <div class="row searchi-section mt-4">
            <div class="col-8 set-boat-heading">
                <h6>Setup</h6>
            </div>
            <div class="col-4">
                <div class="nav nav-tabs  search-container" id="nav-tab" role="tablist">


                    <button class="nav-link btn me-3 active" id="nav-home-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                        aria-selected="true">View Setup</button>
                    <button class="nav-link btn2" id="nav-profile-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                        aria-selected="false">Install</button>

                </div>
            </div>
        </div>


        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#About" aria-expanded="true" aria-controls="collapseOne">
                                Website Bot
                            </button>
                        </h2>
                        <div id="About" class="accordion-collapse collapse" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="boxinner">
                                            <form action="{{route('updateBot')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="form_type" value="form1">

                                                <input type="hidden" name="bot_id" value="{{$id}}">
                                                <div class="textbox">
                                                    <h6>Text</h6>
                                                    <div class="imgboxhead">
                                                        <button type="submit" class="btn btn-primary"
                                                            style="width: 86px;">Save</button>
                                                    </div>
                                                </div>
                                                <div class="input-group flex-nowrap mt-3">
                                                    <input type="text" class="form-control" placeholder="Bot Name"
                                                        aria-label="Bot Name" name="name" value="{{$bot->name ?? ''}}"
                                                        aria-describedby="addon-wrapping">
                                                </div>
                                                <!-- @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror -->
                                                <div class="input-group flex-nowrap mt-3">
                                                    <input type="text" class="form-control" placeholder="Welcome Text"
                                                        aria-label="Welcome Text" name="intro_message" value="{{$bot->intro_message ?? ''}}"
                                                        aria-describedby="addon-wrapping">
                                                  
                                                </div>
                                                <div class="input-group flex-nowrap mt-3">
                                                    <input type="text" class="form-control"
                                                        placeholder="Bot Description" aria-label="Bot Description"
                                                        name="description" value="{{$bot->description ?? ''}}" aria-describedby="addon-wrapping">
                                                    
                                                </div>
                                                <div class="row">

                                                    <select class="form-select mt-3" name="font" aria-label="Choose A Font">
                                                        <option selected disabled>Choose A Font Family</option>
                                                        <option disabled style="font-weight: bold; background-color: #EEEEEE">Serif Fonts</option>
                                                        <option value="Georgia,serif" style="font-family: Georgia, serif;"
                                                            @selected(isset($bot) && $bot->font === 'Georgia,serif')>
                                                            Georgia
                                                        </option>
                                                        <option value="Palatino Linotype,Book Antiqua,Palatino,serif" style="font-family: Palatino Linotype,Book Antiqua,Palatino,serif"
                                                            @selected(isset($bot) && $bot->font === 'Palatino Linotype,Book Antiqua,Palatino,serif')>
                                                            Palatino Linotype
                                                        </option>
                                                        <option value="Times New Roman,Times,serif" style="font-family: Times New Roman,Times,serif"
                                                            @selected(isset($bot) && $bot->font === 'Times New Roman,Times,serif')>
                                                            Times New Roman
                                                        </option>
                                                        <option disabled style="font-weight: bold; background-color: #EEEEEE">Sans-Serif Fonts</option>
                                                        <option value="Arial,Helvetica,sans-serif" style="font-family: Arial,Helvetica,sans-serif"
                                                            @selected(isset($bot) && $bot->font === 'Arial,Helvetica,sans-serif')>
                                                            Arial
                                                        </option>
                                                        <option value="Arial Black,Gadget,sans-serif" style="font-family: Arial Black,Gadget,sans-serif"
                                                            @selected(isset($bot) && $bot->font === 'Arial Black,Gadget,sans-serif')>
                                                            Arial Black
                                                        </option>
                                                        <option value="Comic Sans MS,cursive,sans-serif" style="font-family: Comic Sans MS,cursive,sans-serif"
                                                            @selected(isset($bot) && $bot->font === 'Comic Sans MS,cursive,sans-serif')>
                                                            Comic Sans MS
                                                        </option>
                                                        <option value="Impact,Charcoal,sans-serif" style="font-family: Impact,Charcoal,sans-serif"
                                                            @selected(isset($bot) && $bot->font === 'Impact,Charcoal,sans-serif')>
                                                            Impact
                                                        </option>
                                                        <option value="Lucida Sans Unicode,Lucida Grande,sans-serif" style="font-family: Lucida Sans Unicode,Lucida Grande,sans-serif"
                                                            @selected(isset($bot) && $bot->font === 'Lucida Sans Unicode,Lucida Grande,sans-serif')>
                                                            Lucida Sans Unicode
                                                        </option>
                                                        <option value="Tahoma,Geneva,sans-serif" style="font-family: Tahoma,Geneva,sans-serif"
                                                            @selected(isset($bot) && $bot->font === 'Tahoma,Geneva,sans-serif')>
                                                            Tahoma
                                                        </option>
                                                        <option value="Trebuchet MS,Helvetica,sans-serif" style="font-family: Trebuchet MS,Helvetica,sans-serif"
                                                            @selected(isset($bot) && $bot->font === 'Trebuchet MS,Helvetica,sans-serif')>
                                                            Trebuchet MS
                                                        </option>
                                                        <option value="Verdana,Geneva,sans-serif" style="font-family: Verdana,Geneva,sans-serif"
                                                            @selected(isset($bot) && $bot->font === 'Verdana,Geneva,sans-serif')>
                                                            Verdana
                                                        </option>
                                                        <option disabled style="font-weight: bold; background-color: #EEEEEE">Monospace Fonts</option>
                                                        <option value="Courier New,Courier,monospace" style="font-family: Courier New,Courier,monospace"
                                                            @selected(isset($bot) && $bot->font === 'Courier New,Courier,monospace')>
                                                            Courier New
                                                        </option>
                                                        <option value="Lucida Console,Monaco,monospace" style="font-family: Lucida Console,Monaco,monospace"
                                                            @selected(isset($bot) && $bot->font === 'Lucida Console,Monaco,monospace')>
                                                            Lucida Console
                                                        </option>

                                                    </select>
                                                    <!-- @error('font')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror -->
                                                </div>
                                                <div class="row">
                                                    <select class="form-select mt-3" name="font_size"
                                                        aria-label="Font Size">
                                                        <option selected disabled>Font Size</option>
                                                        <option value="8px" @selected(isset($bot) && $bot->font_size === '8px')> 8px</option>
                                                        <option value="10px" @selected(isset($bot) && $bot->font_size === '10px')> 10px</option>
                                                        <option value="12px" @selected(isset($bot) && $bot->font_size === '12px')> 12px</option>
                                                        <option value="14px" @selected(isset($bot) && $bot->font_size === '14px')> 14px </option>
                                                        <option value="16px" @selected(isset($bot) && $bot->font_size === '16px')> 16px </option>
                                                        <option value="20px" @selected(isset($bot) && $bot->font_size === '20px')> 20px </option>
                                                        <option value="24px" @selected(isset($bot) && $bot->font_size === '24px')> 24px </option>
                                                        <option value="28px" @selected(isset($bot) && $bot->font_size === '28px')> 28px </option>
                                                        <option value="34px" @selected(isset($bot) && $bot->font_size === '34px')> 34px </option>
                                                        <option value="40px" @selected(isset($bot) && $bot->font_size === '40px')> 40px </option>
                                                        <option value="46px" @selected(isset($bot) && $bot->font_size === '46px')> 46px </option>
                                                        <option value="44px" @selected(isset($bot) && $bot->font_size === '44px')> 44px </option>
                                                        <option value="66px" @selected(isset($bot) && $bot->font_size === '66px')> 66px </option>
                                                        <option value="88px" @selected(isset($bot) && $bot->font_size === '88px')> 88px </option>
                                                    </select>
                                                    <!-- @error('font_size')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror -->
                                                </div>


                                            </form>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="boxinner">
                                            <form action="{{ route('updateBot') }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="form_type" value="form2">

                                                <input type="hidden" name="bot_id" value="{{$id}}">
                                                <div class="textbox">
                                                    <h6>Logo</h6>
                                                    <div class="imgboxhead">
                                                        <button type="submit" class="btn btn-primary"
                                                            style="width: 86px;">Save</button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-4 mt-3">
                                                        <div class="imglogobox">

                                                            @if(str_starts_with($bot->logo, 'public/'))
                                                            <img id="companyLogo" src="{{ Storage::url($bot->logo) }}"
                                                                alt="Company Logo">
                                                            @else
                                                            <img id="companyLogo" src="{{ asset($bot->logo) }}"
                                                                alt="Company Logo">
                                                            @endif



                                                        </div>
                                                    </div>
                                                    <div class="col-xl-8 mt-3">
                                                        <div class="logobox">
                                                            <h4>Avatar</h4>
                                                            <div class="imgarea mt-1">
                                                                <input class="clickImage" type="file" name="image"
                                                                    style="display:none;">
                                                                <input type="hidden" id="selectedAvatar"
                                                                    name="selected_avatar" value="">

                                                                <img class="selectImg"
                                                                    src="{{ asset('assets/images/setup/fistic.png') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/1.avif') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/2.avif') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/3.avif') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/4.avif') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/5.avif') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/6.avif') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/7.avif') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/8.avif') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/9.avif') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/10.avif') }}"
                                                                    alt="">
                                                                <img src="{{ asset('assets/images/setup/11.avif') }}"
                                                                    alt="">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
















                                    <div class="col-xl-6">
                                        <div class="boxinner">
                                            <form action="{{route('updateBot')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="form_type" value="form3">
                                                <input type="hidden" id="button_design" name="button_design" value="">
                                                <input type="hidden" id="text_alignment" name="text_alignment" value="">
                                                <input type="hidden" id="bot_position" name="position" data-attr="left"
                                                    value="">
                                                <input type="hidden" name="bot_id" value="{{$id}}">
                                                <div class="textbox">
                                                    <h6>Text</h6>
                                                    <div class="imgboxhead">
                                                        <button type="submit" class="btn btn-primary"
                                                            style="width: 86px;">Save</button>
                                                    </div>
                                                </div>
                                                <div class="border-r mt-3 mb-3">
                                                    <h6 class="mb-3">Bot Position
                                                        <i class="bi bi-info-circle-fill iconhide ms-4">

                                                            <div class="boxhede">
                                                                <p>here is the main content</p>
                                                            </div>
                                                        </i>
                                                    </h6>
                                                    <div class="flxbox">
                                                        <div class="bottonbg innerflxbox2 ">
                                                            <input class="activein bot_position" data-attr="right">
                                                        </div>
                                                        <div class="innerflxbox bottonbg">
                                                            <input class="activein bot_position" data-attr="center">
                                                        </div>
                                                        <div class=" bottonbg">
                                                            <input class="activein bot_position" data-attr="left">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="border-r mt-3 mb-3">
                                                    <h6 class="mb-3">Message Bubbles <i
                                                            class="bi bi-info-circle-fill iconhide ms-4">

                                                            <div class="boxhede">
                                                                <p>here is the main content</p>
                                                            </div>
                                                        </i></h6>
                                                    <div class="row">
                                                        <div class="col-xl-4">
                                                            <div class="butoonbox">
                                                                <button type="button" class="button_design"
                                                                    name="button_design" value="1">
                                                                    Hello !
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4">
                                                            <div class="butoonboxes">
                                                                <button type="button" class="button_design"
                                                                    name="button_design" value="2">
                                                                    Hello !
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4">
                                                            <div class="butoonbox3">
                                                                <button type="button" class="button_design"
                                                                    name="button_design" value="3">
                                                                    Hello !
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="border-r  mt-5 mb-3">
                                                    <h6>Option Border Radius <i
                                                            class="bi bi-info-circle-fill iconhide ms-4">

                                                            <div class="boxhede">
                                                                <p>here is the main content</p>
                                                            </div>
                                                        </i></h6>
                                                    <div class="progress-bar-container">
                                                        <input type="range" id="progressBar" name="radius" value="0"
                                                            max="100">
                                                        <div class="button-container">
                                                            <button id="dynamicButton" type="button"></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="border-r mt-3 mb-3">
                                                    <h6 class="mb-3">Button Text Alignment<i
                                                            class="bi bi-info-circle-fill iconhide ms-4">

                                                            <div class="boxhede">
                                                                <p>here is the main content</p>
                                                            </div>
                                                        </i>

                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-xl-4">
                                                            <div class="butoonbox1">
                                                                <button type="button" class="text_alignment"
                                                                    name="text_alignment" value="left">
                                                                    Hello !
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4">
                                                            <div class="butoonbox2"><button class="text_alignment"
                                                                    type="button" name="text_alignment" value="center">
                                                                    Hello !
                                                                </button></div>
                                                        </div>
                                                        <div class="col-xl-4">
                                                            <div class="butoonbox3"><button class="text_alignment"
                                                                    type="button" name="text_alignment" value="right">
                                                                    Hello !
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="boxinner">
                                            <form action="{{route('updateBot')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="form_type" value="form4">

                                                <input type="hidden" name="bot_id" value="{{$id}}">
                                                <div class="textbox">
                                                    <h6>Text</h6>
                                                    <div class="imgboxhead">
                                                        <button type="submit" class="btn btn-primary"
                                                            style="width: 86px;">Save</button>
                                                    </div>
                                                </div>
                                                <div class="mt-3 colorbox">
                                                    <div class="textbox details">
                                                        <h2>Choose a theme <i
                                                                class="bi bi-info-circle-fill iconhide ms-4">
                                                                <div class="boxhede">
                                                                    <p>here is the main content</p>
                                                                </div>
                                                            </i></h2>
                                                        <input type="color" name="header_color" class="box1"
                                                            value="#75BB3F">
                                                    </div>

                                                    <div class="textbox details">
                                                        <h3>Header Background</h3>
                                                        <input type="color" name="background_color" class="box2"
                                                            value="#D29D03">
                                                    </div>

                                                    <div class="textbox details">
                                                        <h3>Question Background</h3>
                                                        <input type="color" name="question_color" class="box3"
                                                            value="#79193E">
                                                    </div>

                                                    <div class="textbox details">
                                                        <h3>Answer Background</h3>
                                                        <input type="color" name="answer_color" class="box4"
                                                            value="#03C7FB">
                                                    </div>

                                                    <div class="textbox details">
                                                        <h3>Options Background</h3>
                                                        <input type="color" name="option_color" class="box5"
                                                            value="#B51900">
                                                    </div>

                                                    <div class="textbox details">
                                                        <h3>Option Border Color</h3>
                                                        <input type="color" name="option_border_color" class="box6"
                                                            value="#BE37F3">
                                                    </div>

                                                    <div class="textbox details">
                                                        <h2>Chat background <i
                                                                class="bi bi-info-circle-fill iconhide ms-4">
                                                                <div class="boxhede">
                                                                    <p>here is the main content</p>
                                                                </div>
                                                            </i>
                                                        </h2>

                                                        <input type="color" class="box7" value="#FDFC42">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="headtext">
                    <h4 class="mt-5 mb-5 favapp">Connect WhizBot To Your Favourite Apps</h4>

                    <div class="websiteheight">
                        <ul class="nav nav-tabs favappflex mt-3 row">
                            <li class=" col icontextbox"><a data-toggle="tab" href="#hubspot">
                                    <img class="d-block" src="{{ asset('assets/images/setup/Vectoric.png') }}" alt="">
                                    HUBSPOT</a>
                            </li>
                            <li class=" col icontextbox"><a data-toggle="tab" href="#chatbot">
                                    <img class="d-block" src="{{ asset('assets/images/setup/Vectoric.png') }}" alt="">
                                    API CHATBOT</a></li>
                            <li class="col icontextbox"><a data-toggle="tab" href="#airtable">
                                    <img class="d-block" src="{{ asset('assets/images/setup/Vectoric.png') }}" alt="">
                                    AIRTABLE</a></li>
                            <li class="col icontextbox"><a data-toggle="tab" href="#celendly">
                                    <img class="d-block" src="{{ asset('assets/images/setup/Vectoric.png') }}" alt="">
                                    CALENDLY</a></li>
                            <li class="col icontextbox"><a data-toggle="tab" href="#dialogflow">
                                    <img class="d-block" src="{{ asset('assets/images/setup/Vectoric.png') }}" alt="">
                                    DIALOGFLOW</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div>
                            <div class="accordinbox">
                                <p>Copy this code snippet ...</p>
                                <h6><span>Note:-</span>Paste it in your Website / Blog (In the head tag)</h6>
                                <div id="hubspot" class="acccorboxbg tab-pane fade in active">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book. It has survived not only five centuries, but also the leap into electronic
                                        typesetting, remaining essentially unchanged. It was popularised in the 1960s
                                        with the release of Letraset sheets containing Lorem Ipsum passages, and more
                                        recently with desktop publishing software like Aldus PageMaker including
                                        versions of Lorem Ipsum.</p>
                                </div>

                                <div id="chatbot" class="acccorboxbg tab-pane fade in active">
                                    <p>An API chatbot is a conversational tool that leverages natural language
                                        processing (NLP) to interpret user inputs and interacts with external services
                                        through APIs. It provides dynamic, real-time responses by retrieving or sending
                                        data, making it ideal for tasks like customer support, product recommendations
                                        bookings, and data retrieval. This enables businesses to automate interactions,
                                        improve user engagement, and deliver personalized experiences efficiently across
                                        various platforms such as websites and messaging apps.</p>
                                </div>
                                <div id="airtable" class="acccorboxbg tab-pane fade in active">
                                    <p>
                                        Airtable is a cloud-based collaboration platform that combines the functionality
                                        of spreadsheets and databases. It allows users to organize data, create custom
                                        workflows, and collaborate in real-time. With its easy-to-use interface,
                                        Airtable is ideal for project management, content planning, and data tracking.
                                        It integrates with various apps and services via APIs, enabling automation and
                                        synchronization of data across platforms, making it a versatile tool for teams
                                        to manage and visualize information efficiently.</p>
                                </div>

                                <div id="celendly" class="acccorboxbg tab-pane fade in active">
                                    <p>
                                        Calendly is a scheduling tool that simplifies meeting bookings by allowing users
                                        to set their availability and share a personalized link. Invitees can choose
                                        from available time slots, eliminating back-and-forth emails. It integrates with
                                        calendars like Google, Outlook, and Office 365, and supports video conferencing
                                        tools like Zoom. Calendly also offers automated reminders and follow-ups, making
                                        it ideal for professionals to manage appointments, interviews, or consultations
                                        efficiently across various industries.</p>
                                </div>

                                <div id="dialogflow" class="acccorboxbg tab-pane fade in active">
                                    <p>Dialogflow is a natural language understanding (NLU) platform by Google that
                                        enables the creation of conversational agents, such as chatbots and voice
                                        assistants. It uses machine learning to process user inputs, identify intent,
                                        and provide appropriate responses. Dialogflow supports multiple languages and
                                        integrates with various platforms like Google Assistant, Slack, and Facebook
                                        Messenger. Its API allows developers to build custom integrations, making it
                                        ideal for creating dynamic, AI-driven conversations for customer support,
                                        automation, and other business applications.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('.button_design').on('click', function() {
            var design = $(this).val();
            $('#button_design').val(design);
        });
        $('.text_alignment').on('click', function() {
            var alignment = $(this).val();
            $('#text_alignment').val(alignment);
        });

        $('.bottonbg').on('click', function() {
            $('.bottonbg').removeClass('active');
            $(this).addClass('active');
            $('.bottonbg').css('border', 'none'); // Remove borders from all
            $(this).css('border', '4px solid #03c7fb'); // Add border to the clicked element
            var bot_position = $(this).find('.bot_position').attr('data-attr');
            $('#bot_position').val(bot_position);
        });

        $('.selectImg').on('click', function() {
            $('.clickImage').click(); // Simulate a click on the element with class 'clickImage'
        });
        $('.imgarea img').on('click', function() {
            var newSrc = $(this).attr('src');
            $('#companyLogo').attr('src', newSrc);
            $('#selectedAvatar').val(newSrc);
        });
        $('.clickImage').on('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#companyLogo').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]); // Convert the file to a data URL
            }
        });
    });

    $('#progressBar').on('change', function() {
        var radius = $(this).val();
        $('#dynamicButton').css('border-radius', radius + '%');
    })
</script>
@endsection