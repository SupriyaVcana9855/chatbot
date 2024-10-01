@extends('layout.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/setup.css') }}">

<div class="boxpadding">
    <div class="row searchi-section mt-4">
        <div class="col-7 set-boat-heading">
            <h6>Add New Template</h6>
        </div>
        <div class="accordion-body">
            <form id="template-form" action="{{ route('saveTemplate') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-6">
                        <div class="boxinner">
                            <input type="hidden" name="form_type" value="form1">
                            <input type="hidden" name="tempDataId" value="{{$tempData->id ?? ''}}">
                            <div class="textbox">
                                <h6>Text</h6>

                            </div>
                            <div class="input-group flex-nowrap mt-3">
                                <input type="text" class="form-control" placeholder="Bot Name"
                                    aria-label="Bot Name" name="name" value="{{$tempData->name ?? ''}}" aria-describedby="addon-wrapping">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon-wrapping"><i
                                            class="bi bi-info-circle-fill"></i>
                                        <div class="hideshowbox">
                                            <p>Here is a dummy content</p>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="input-group flex-nowrap mt-3">
                                <input type="text" class="form-control" value="{{$tempData->intro_message ?? ''}}" placeholder="Welcome Text"
                                    aria-label="Welcome Text" name="intro_message" aria-describedby="addon-wrapping">
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
                                <input type="text" class="form-control" placeholder="Bot Description"
                                    aria-label="Bot Description" value="{{$tempData->description ?? ''}}" name="description" aria-describedby="addon-wrapping">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="addon-wrapping"><i
                                            class="bi bi-info-circle-fill"></i>
                                        <div class="hideshowbox">
                                            <p>Here is a dummy content</p>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <select class="form-select mt-3" name="font" aria-label="Choose A Font Family">
    <option selected disabled>Choose A Font Family</option>
    <option disabled style="font-weight: bold; background-color: #EEEEEE">Serif Fonts</option>
    <option value="Georgia,serif" style="font-family: Georgia, serif;" 
        @selected(isset($tempData) && $tempData->font === 'Georgia,serif')>
        Georgia
    </option>
    <option value="Palatino Linotype,Book Antiqua,Palatino,serif" style="font-family: Palatino Linotype,Book Antiqua,Palatino,serif" 
        @selected(isset($tempData) && $tempData->font === 'Palatino Linotype,Book Antiqua,Palatino,serif')>
        Palatino Linotype
    </option>
    <option value="Times New Roman,Times,serif" style="font-family: Times New Roman,Times,serif" 
        @selected(isset($tempData) && $tempData->font === 'Times New Roman,Times,serif')>
        Times New Roman
    </option>
    <option disabled style="font-weight: bold; background-color: #EEEEEE">Sans-Serif Fonts</option>
    <option value="Arial,Helvetica,sans-serif" style="font-family: Arial,Helvetica,sans-serif" 
        @selected(isset($tempData) && $tempData->font === 'Arial,Helvetica,sans-serif')>
        Arial
    </option>
    <option value="Arial Black,Gadget,sans-serif" style="font-family: Arial Black,Gadget,sans-serif" 
        @selected(isset($tempData) && $tempData->font === 'Arial Black,Gadget,sans-serif')>
        Arial Black
    </option>
    <option value="Comic Sans MS,cursive,sans-serif" style="font-family: Comic Sans MS,cursive,sans-serif" 
        @selected(isset($tempData) && $tempData->font === 'Comic Sans MS,cursive,sans-serif')>
        Comic Sans MS
    </option>
    <option value="Impact,Charcoal,sans-serif" style="font-family: Impact,Charcoal,sans-serif" 
        @selected(isset($tempData) && $tempData->font === 'Impact,Charcoal,sans-serif')>
        Impact
    </option>
    <option value="Lucida Sans Unicode,Lucida Grande,sans-serif" style="font-family: Lucida Sans Unicode,Lucida Grande,sans-serif" 
        @selected(isset($tempData) && $tempData->font === 'Lucida Sans Unicode,Lucida Grande,sans-serif')>
        Lucida Sans Unicode
    </option>
    <option value="Tahoma,Geneva,sans-serif" style="font-family: Tahoma,Geneva,sans-serif" 
        @selected(isset($tempData) && $tempData->font === 'Tahoma,Geneva,sans-serif')>
        Tahoma
    </option>
    <option value="Trebuchet MS,Helvetica,sans-serif" style="font-family: Trebuchet MS,Helvetica,sans-serif" 
        @selected(isset($tempData) && $tempData->font === 'Trebuchet MS,Helvetica,sans-serif')>
        Trebuchet MS
    </option>
    <option value="Verdana,Geneva,sans-serif" style="font-family: Verdana,Geneva,sans-serif" 
        @selected(isset($tempData) && $tempData->font === 'Verdana,Geneva,sans-serif')>
        Verdana
    </option>
    <option disabled style="font-weight: bold; background-color: #EEEEEE">Monospace Fonts</option>
    <option value="Courier New,Courier,monospace" style="font-family: Courier New,Courier,monospace" 
        @selected(isset($tempData) && $tempData->font === 'Courier New,Courier,monospace')>
        Courier New
    </option>
    <option value="Lucida Console,Monaco,monospace" style="font-family: Lucida Console,Monaco,monospace" 
        @selected(isset($tempData) && $tempData->font === 'Lucida Console,Monaco,monospace')>
        Lucida Console
    </option>
</select>

<select class="form-select mt-3" name="font_size" aria-label="Font Size">
    <option selected disabled>Font Size</option>
    <option value="8px" @selected(isset($tempData) && $tempData->font_size === '8px')> 8px</option>
    <option value="10px" @selected(isset($tempData) && $tempData->font_size === '10px')> 10px</option>
    <option value="12px" @selected(isset($tempData) && $tempData->font_size === '12px')> 12px</option>
    <option value="14px" @selected(isset($tempData) && $tempData->font_size === '14px')> 14px </option>
    <option value="16px" @selected(isset($tempData) && $tempData->font_size === '16px')> 16px </option>
    <option value="20px" @selected(isset($tempData) && $tempData->font_size === '20px')> 20px </option>
    <option value="24px" @selected(isset($tempData) && $tempData->font_size === '24px')> 24px </option>
    <option value="28px" @selected(isset($tempData) && $tempData->font_size === '28px')> 28px </option>
    <option value="34px" @selected(isset($tempData) && $tempData->font_size === '34px')> 34px </option>
    <option value="40px" @selected(isset($tempData) && $tempData->font_size === '40px')> 40px </option>
    <option value="46px" @selected(isset($tempData) && $tempData->font_size === '46px')> 46px </option>
    <option value="44px" @selected(isset($tempData) && $tempData->font_size === '44px')> 44px </option>
    <option value="66px" @selected(isset($tempData) && $tempData->font_size === '66px')> 66px </option>
    <option value="88px" @selected(isset($tempData) && $tempData->font_size === '88px')> 88px </option>
</select>

                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="boxinner">

                            <input type="hidden" name="form_type" value="form2">
                            
                            <div class="textbox">
                                <h6>Logo</h6>

                            </div>
                            <div class="row">
                                <div class="col-xl-4 mt-3">
                                    <div class="imglogobox">
                                        <img id="companyLogo" name="logo" src="{{ asset('assets/images/setup/1.avif') }}" alt="Company Logo">
                                    </div>
                                </div>
                                <div class="col-xl-8 mt-3">
                                    <div class="logobox">
                                        <h4>Avatar</h4>
                                        <div class="imgarea mt-1">
                                            <input class="clickImage" type="file" name="logo" style="display:none;">
                                            <input type="hidden" id="selectedAvatar" name="selected_avatar" value="">
                                            <img class="selectImg" src="{{ asset('assets/images/setup/fistic.png') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/1.avif') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/2.avif') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/3.avif') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/4.avif') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/5.avif') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/6.avif') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/7.avif') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/8.avif') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/9.avif') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/10.avif') }}" alt="">
                                            <img src="{{ asset('assets/images/setup/11.avif') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-xl-6">
                        <div class="boxinner">
                            <input type="hidden" name="form_type" value="form3">
                            <input type="hidden" id="button_design" name="button_design" value="">
                            <input type="hidden" id="text_alignment" name="text_alignment" value="">
                            <input type="hidden" id="bot_position" name="position" data-attr="left" value="">
                            <div class="textbox">
                                <h6>Text</h6>
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
                                            <button type="button" class="button_design" name="button_design" value="1">
                                                Hello !
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="butoonboxes">
                                            <button type="button" class="button_design" name="button_design" value="2">
                                                Hello !
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="butoonbox3">
                                            <button type="button" class="button_design" name="button_design" value="3">
                                                Hello !
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-r  mt-5 mb-3">
                                <h6>Option Border Radius <i class="bi bi-info-circle-fill iconhide ms-4">

                                        <div class="boxhede">
                                            <p>here is the main content</p>
                                        </div>
                                    </i></h6>
                                <div class="progress-bar-container">
                                    <input type="range" id="progressBar" name="radius" value="0" max="100">
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
                                            <button type="button" class="text_alignment" name="text_alignment" value="left">
                                                Hello !
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="butoonbox2"><button class="text_alignment" type="button" name="text_alignment" value="center">
                                                Hello !
                                            </button></div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="butoonbox3"><button class="text_alignment" type="button" name="text_alignment" value="right">
                                                Hello !
                                            </button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="boxinner">
                            <div class="textbox">
                                <h6>Text</h6>
                            </div>
                            <div class="mt-3 colorbox">
                                <div class="textbox details">
                                    <h2>Choose a theme <i class="bi bi-info-circle-fill iconhide ms-4">
                                            <div class="boxhede">
                                                <p>here is the main content</p>
                                            </div>
                                        </i></h2>
                                    <input type="color" name="header_color" class="box1" value="#75BB3F">
                                </div>

                                <div class="textbox details">
                                    <h3>Header Background</h3>
                                    <input type="color" name="background_color" class="box2" value="#D29D03">
                                </div>

                                <div class="textbox details">
                                    <h3>Question Background</h3>
                                    <input type="color" name="question_color" class="box3" value="#79193E">
                                </div>

                                <div class="textbox details">
                                    <h3>Answer Background</h3>
                                    <input type="color" name="answer_color" class="box4" value="#03C7FB">
                                </div>

                                <div class="textbox details">
                                    <h3>Options Background</h3>
                                    <input type="color" name="option_color" class="box5" value="#B51900">
                                </div>

                                <div class="textbox details">
                                    <h3>Option Border Color</h3>
                                    <input type="color" name="option_border_color" class="box6" value="#BE37F3">
                                </div>

                                <div class="textbox details">
                                    <h2>Chat background <i class="bi bi-info-circle-fill iconhide ms-4">
                                            <div class="boxhede">
                                                <p>here is the main content</p>
                                            </div>
                                        </i>
                                    </h2>
                                    <input type="color" class="box7" value="#FDFC42">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-xl-12">
                        <div class="boxinner">
                            <input type="hidden" name="form_type" value="form1">
                            <div class="textbox">
                                <h6>Template</h6>
                            </div>
                            <div class="row">
                                <div class="input-group flex-nowrap mt-3">
                                    <label for="">Title</label>
                                </div>
                                <input type="text" value="{{$tempData->temp_title ?? ''}}" class="form-control" placeholder="title" name="temp_title">
                                @error('temp_title')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="input-group flex-nowrap mt-3">
                                    <label for="">Description</label>
                                </div>
                                <input type="text" class="form-control" value="{{$tempData->temp_description ?? ''}}" placeholder="Description"
                                    name="temp_description">
                                @error('temp_description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="input-group flex-nowrap mt-3">
                                    <label for="">Images</label>
                                </div>
                                <input type="file" class="form-control" placeholder="Bot Description"
                                    aria-label="Bot Description" name="temp_img" aria-describedby="addon-wrapping">

                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{route('templates')}}"><button type="button" class="col-md-2 btn btn-secondary">Back</button></a>
                <button type="submit" class="col-md-2 btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>



<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

@endsection
@section('java_scripts')
<script>
    document.querySelectorAll('.selectImg').forEach(img => {
        img.addEventListener('click', function() {
            const selectedSrc = this.src;
            const fileName = selectedSrc.split('/').pop(); // Get the file name
            document.getElementById('companyLogo').src = selectedSrc;
            document.getElementById('selectedAvatar').value = fileName; // Set hidden input value
        });
    });

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
            // alert('okkk');
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