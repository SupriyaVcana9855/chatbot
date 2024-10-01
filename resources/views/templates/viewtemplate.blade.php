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
    <div class="col-12">
        <div class="row service-container mt-5">
            <div class="col-7 temp-text ">
                <h4>Digital Marketing</h4>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>

                <div class="card" style="width: 40rem;">
                    <div class="card-body">
                        <h5 class="card-title">Click Below to replicate & edit the bot.</h5>
                        <form id="add-bot-template" method="post" action="{{route('addbottemplate')}}">
                            <div class="form-group">
                                <label for="bot_name">Bot Name</label>
                                <input type="text" class="form-control" name="bot_name" id="bot_name" placeholder="Enter Your Bot Name">
                            </div>
                            <div class="form-group">
                                <label for="type">Select Bot Type</label>
                                <select class="form-select" name="type" id="type">
                                    <option selected disabled>select menu</option>
                                    <option value="lead">Lead</option>
                                    <option value="support">Support</option>
                                </select>
                            </div>

                            <a href="{{route('templates')}}"><button type="button" class="col-md-2 btn btn-secondary">Bcak</button></a>
                            <button type="submit" class="col-md-2 btn btn-primary">Submit</button>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-5 chat-section">
                <div class='chat-container-temp'>
                    <div class='chat-header'>
                        <div class="d-flex head-name">
                            <div class='chat-img'>
                                @if($templates->logo)
                                <img src="{{ $templates->logo ?? '' }}" alt="Company Logo">
                                @else
                                <img src="{{asset('assets/images/clientimg.png')}}" />
                                @endif

                            </div>
                            <div class='chat-title'>
                                <h3>{{$templates->name}}</h3>
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
@endsection

@section('java_scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        const temp = <?php echo json_encode($templates); ?>;
        // console.log(temp);

        // Apply the font, font size, and alignment to the chat container
        $('.chat-container-temp').css({
            'font-family': temp.font,
            'font-size': temp.font_size,
            'text-align': temp.text_alignment,
            'background-color': temp.text_color,
            'color': temp.text_color,
        });

        // Apply the styles for bot messages (question_color)
        $('.message.bot .text').css({
            'background-color': temp.question_color,
            'border-radius': temp.radius,
            'color': temp.text_color,
        });

        // Apply the styles for user messages (answer_color)
        $('.message.user .text').css({
            'background-color': temp.answer_color,
            'border-radius':  temp.radius,
            'color': temp.text_color,
        });

        // Apply border-radius to buttons
        $('.chat-btn button').css({
            'padding': '11px 21px',
            'border-radius': temp.radius,
            'border': '0px',
            'background': 'linear-gradient(90deg, #001A2B 0%, #005791 100%)',
            'color': 'white',
        });


        $('#add-bot-template').on('submit', function(e) {
            e.preventDefault(); // Prevent form submission
            const temp = <?php echo json_encode($templates); ?>;
        
            var bot_name = $('#bot_name').val();
            var type = $('#type').val();
            var isValid = true; // Flag to check if the form is valid

            // Clear previous error messages
            $('.error').remove(); // Remove all error elements

            // Validate bot_name
            if (bot_name === '') {
                $('#bot_name').after('<div class="error text-danger">Bot name field cannot be empty.</div>');
                isValid = false;
            }

            // Validate type (check if an option is selected)
            if (type === null || type === 'select menu') {
                $('#type').after('<div class="error text-danger">Bot type must be selected.</div>');
                isValid = false;
            }

            // If form is valid, proceed with the AJAX request
            if (isValid) {
                const formData = new FormData();
                formData.append('bot_name', bot_name);
                formData.append('type', type);
                formData.append('tempData', JSON.stringify(temp)); // Ensure tempData is serialized as JSON

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('addbottemplate') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                }).done(function(response) {
                    if (response.success === true) {
                        Swal.fire({
                            title: "Template added successfully. Please check bot in bot section",
                            icon: "success"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "{{route('templates')}}";
                                // window.location.reload(); // Reload the page
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong.",
                            icon: "error"
                        });
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: "Error!",
                        text: "Request failed: " + textStatus,
                        icon: "error"
                    });
                });
            }
        });


    });
</script>
@endsection