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
                                                        aria-label="Bot Name" name="name"
                                                        aria-describedby="addon-wrapping">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="addon-wrapping"><i
                                                                class="bi bi-info-circle-fill"></i>
                                                            <div class="hideshowbox">
                                                                <p>Here is a dummy content</p>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="input-group flex-nowrap mt-3">
                                                    <input type="text" class="form-control" placeholder="Welcome Text"
                                                        aria-label="Welcome Text" name="intro_message"
                                                        aria-describedby="addon-wrapping">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="addon-wrapping"><i
                                                                class="bi bi-info-circle-fill"></i>
                                                            <div class="hideshowbox">
                                                                <p>Here is a dummy content</p>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="input-group flex-nowrap mt-3">
                                                    <input type="text" class="form-control"
                                                        placeholder="Bot Description" aria-label="Bot Description"
                                                        name="description" aria-describedby="addon-wrapping">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="addon-wrapping"><i
                                                                class="bi bi-info-circle-fill"></i>
                                                            <div class="hideshowbox">
                                                                <p>Here is a dummy content</p>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>

                                                <select class="form-select mt-3" name="font" aria-label="Choose A Font">
                                                    <option selected>Choose A Font</option>
                                                    <option value="1">Font 1</option>
                                                    <option value="2">Font 2</option>
                                                    <option value="3">Font 3</option>
                                                </select>
                                                <select class="form-select mt-3" name="font_size"
                                                    aria-label="Font Size">
                                                    <option selected>Font Size</option>
                                                    <option value="1">Size 1</option>
                                                    <option value="2">Size 2</option>
                                                    <option value="3">Size 3</option>
                                                </select>

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
                                                        <div class="bottonbg innerflxbox2">
                                                            <input class="activein bot_position" data-attr="left">
                                                        </div>
                                                        <div class="innerflxbox bottonbg">
                                                            <input class="activein bot_position" data-attr="center">
                                                        </div>
                                                        <div class=" bottonbg">
                                                            <input class="activein bot_position" data-attr="right">
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
                        <div id="hubspot" class="tab-pane fade in active">
                            <div class="accordinbox">
                                <p>Copy this code snippet ... </p>
                                <h6><span>Note:-</span>Paste it in your Website / Blog (In the head tag)</h6>
                                <div class="acccorboxbg">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book. It has survived not only five centuries, but also the leap into electronic
                                        typesetting, remaining essentially unchanged. It was popularised in the 1960s
                                        with the release of Letraset sheets containing Lorem Ipsum passages, and more
                                        recently with desktop publishing software like Aldus PageMaker including
                                        versions of Lorem Ipsum.</p>
                                </div>
                            </div>
                        </div>
                        <div id="chatbot" class="tab-pane fade in ">
                            <div class="accordinbox">
                                <p>Copy this code snippet ...  2</p>
                                <h6><span>Note:-</span>Paste it in your Website / Blog (In the head tag)</h6>
                                <div class="acccorboxbg">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book. It has survived not only five centuries, but also the leap into electronic
                                        typesetting, remaining essentially unchanged. It was popularised in the 1960s
                                        with the release of Letraset sheets containing Lorem Ipsum passages, and more
                                        recently with desktop publishing software like Aldus PageMaker including
                                        versions of Lorem Ipsum.</p>
                                </div>
                            </div>
                        </div>

                        <div id="airtable" class="tab-pane fade in ">
                            <div class="accordinbox">
                                <p>Copy this code snippet ...  3</p>
                                <h6><span>Note:-</span>Paste it in your Website / Blog (In the head tag)</h6>
                                <div class="acccorboxbg">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book. It has survived not only five centuries, but also the leap into electronic
                                        typesetting, remaining essentially unchanged. It was popularised in the 1960s
                                        with the release of Letraset sheets containing Lorem Ipsum passages, and more
                                        recently with desktop publishing software like Aldus PageMaker including
                                        versions of Lorem Ipsum.</p>
                                </div>
                            </div>
                        </div>


                        <div id="celendly" class="tab-pane fade in ">
                            <div class="accordinbox">
                                <p>Copy this code snippet ...  4</p>
                                <h6><span>Note:-</span>Paste it in your Website / Blog (In the head tag)</h6>
                                <div class="acccorboxbg">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book. It has survived not only five centuries, but also the leap into electronic
                                        typesetting, remaining essentially unchanged. It was popularised in the 1960s
                                        with the release of Letraset sheets containing Lorem Ipsum passages, and more
                                        recently with desktop publishing software like Aldus PageMaker including
                                        versions of Lorem Ipsum.</p>
                                </div>
                            </div>
                        </div>

                        <div id="dialogflow" class="tab-pane fade in ">
                            <div class="accordinbox">
                                <p>Copy this code snippet ... </p>
                                <h6><span>Note:-</span>Paste it in your Website / Blog (In the head tag)</h6>
                                <div class="acccorboxbg">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen
                                        book. It has survived not only five centuries, but also the leap into electronic
                                        typesetting, remaining essentially unchanged. It was popularised in the 1960s
                                        with the release of Letraset sheets containing Lorem Ipsum passages, and more
                                        recently with desktop publishing software like Aldus PageMaker including
                                        versions of Lorem Ipsum.</p>
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
    $('.bot_position').on('click', function() {
        var bot_position = $(this).attr('data-attr');
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