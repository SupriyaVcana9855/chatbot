@extends('layout.app')

@section('content')
<link rel="stylesheet" href="{{asset('/css/detialsTemplate.css')}}">

<div>
    <div class="row searchi-sectiont mt-4">
        <div class="col-2 set-boat-heading">
            <h6>Templates</h6>
        </div>
        <div class="col-10">
            <div class="search-container">
                <select class="form-control form-select mr-2">
                    <option>All</option>
                    <option>General</option>
                    <option>Education</option>
                    <option>Travel</option>
                    <option>Finance</option>
                    <option>Real Estate</option>
                    <option>Fitness</option>
                    <option>Software</option>
                    <option>HR</option>
                    <option>E-Commerce</option>
                    <option>Hotels & Restaurants</option>
                    <option>Entertainment</option>
                    <option>Healthcare</option>
                    <option>Automotive</option>
                    <option>Logistics</option>
                    <option>Manufacturing</option>
                    <option>Other Services</option>
                    <!-- Add more options as needed -->
                </select>
                <div class="input-group set-select mr-2">
                    <input type="text" class="form-control" placeholder="Search Here For Bot">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <button class="btn">Built A WhizBot</button>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-6 col-lg-6 col-sm-12 MainHeading">
            <h4>Template</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="search-section">
                <div class="select-temp">
                    <select name="All" id="">
                        <option value="">All</option>
                        <option value="">option1</option>
                        <option value="">option2</option>
                        <option value="">option3</option>
                        <option value="">option4</option>
                    </select>
                </div>
                <div>
                    <form class="search-sec">
                        <input type="search" class="gsearch" name="gsearch">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row service-container mt-5">
                <div class="col-7 temp-text ">
                    <h4>Digital Marketing</h4>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>

                    <h5>Click Below to replicate & edit the bot.</h5>
                    <form class="content-search" id="add-bot-template" method="post" action="{{route('addbottemplate')}}">
                        <input type="search" name="bot_name" id="bot_name" placeholder="Enter Your Bot Name">
                        <p class="error" style="color:red"></p>
                        <div class="form-btn">
                            <button class="form-frist-btn" type="submit">Submit</button>
                            <button class="form-second-btn" type="button">Back</button>
                        </div>
                    </form>
                </div>

                <div class="col-5 chat-section">
                    <div class='chat-container-temp chat-boat-position'>
                        <div class='chat-header'>
                            <div class="d-flex head-name">
                                <div class='chat-img'>
                                    <img src="{{asset('assets/images/clientimg.png')}}" />
                                </div>
                                <div class='chat-title'>
                                    <h3>Lorem Ipsum</h3>
                                    <p>Support</p>
                                </div>
                            </div>
                            <div class='icon-head'>
                                <div>
                                    <img src="{{asset('assets/images/reload.png')}}">
                                </div>
                                <div class='closeicon'>
                                    <img src="{{asset('assets/images/colse.png')}}" id=''>
                                </div>
                            </div>
                        </div>
                        <div class='chat-body'>
                            <div class='message bot'>
                                <div class='text'>Hello! Welcome to our chatbot. How can I assist you today?</div>
                            </div>
                            <div class='message user'>
                                <div class='text'>Hi there! I'm interested in building an e-commerce website for my business.</div>
                            </div>
                            <div class='message bot'>
                                <div class='text'>That's great to hear! We can definitely help you with that. What kind of products do you plan to sell on your e-commerce website?</div>
                            </div>
                            <div class='message user'>
                                <div class='text'>I sell handmade jewelry and accessories.</div>
                            </div>
                            <div class='message bot'>
                                <div class='text'>That's great to hear! We can definitely help you with that. What kind of products do you plan to sell on your e-commerce website?</div>
                            </div>
                            <div class='message user'>
                                <div class='text'>I sell handmade jewelry and accessories.</div>
                            </div>
                            <div class='message bot'>
                                <div class='text'>That's great to hear! We can definitely help you with that. What kind of products do you plan to sell on your e-commerce website?</div>
                            </div>
                            <div class="chat-btn">
                                <button>Yes</button>
                                <button>No</button>
                            </div>
                        </div>
                        <div class='chat-footer'>
                            <input type='text' placeholder='Enter your message...' readonly>
                            <button><img src="{{asset('assets/images/fileupload.png')}}"></button>
                            <button id='sendButton'><img src="{{asset('assets/images/Vector.png')}}"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('java_scripts')
<script>
    $(document).ready(function() {
        const temp = <?php echo json_encode($templates); ?>;
        console.log(temp);

        // Apply the font, font size, and alignment to the chat container
        $('.chat-container-temp').css({
            'font-family': temp.font,
            'font-size': temp.font_size,
            'text-align': temp.text_alignment,
            'background-color': temp.text_color,
            'color': temp.text_color,
            'width': temp.box_width,
        });

        // Apply the styles for bot messages (question_color)
        $('.message.bot .text').css({
            'background-color': temp.question_color,
            'border-radius': temp.question_radius,
            'color': temp.text_color,
        });

        // Apply the styles for user messages (answer_color)
        $('.message.user .text').css({
            'background-color': temp.answer_color,
            'border-radius': temp.answer_radius,
            'color': temp.text_color,
        });

        // Apply border-radius to buttons
        $('.chat-btn button').css({
            'padding': '11px 21px',
            'border-radius': temp.button_type,
            'border': '0px',
            'background': 'linear-gradient(90deg, #001A2B 0%, #005791 100%)',
            'color': temp.text_color,
        });


        $('#add-bot-template').on('submit', function(e) {
            e.preventDefault();

            var bot_name = $('#bot_name').val();
            if (bot_name === '') {
                $('.error').html('Bot name field can not be empty.');
            } else {
                $('.error').html('');
            }

            const formData = new FormData();
            formData.append('bot_name', bot_name);
            formData.append('tempData', JSON.stringify(temp));


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('addbottemplate')}}",
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
            }).done(function(response) {
                if(response.success == true){
                    window.location.reload();
                }
            });
        });

    });
</script>
@endsection