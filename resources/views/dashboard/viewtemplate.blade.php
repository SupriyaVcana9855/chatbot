@extends('layout.app')

@section('content')
<link rel="stylesheet" href="{{asset('/css/detialsTemplate.css')}}">
<style>

.chat-container {
    width: 500px; 
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    position:absolute;

}

.chat-boat-position{
    bottom: 20px;
    right: 20px;
  }
.chat-section{
   position: relative!important;
}

.chat-toggle {
    position:absolute;
    width: 50px;
    height: 50px;
    cursor: pointer;
}

.chat-toggle img {
    width: 100%;
    height: 100%;
}


.icon-head {
    display: flex;
    justify-content: flex-end;
    width: 61%;
}
.closeicon {
    margin-left: 10px;
    cursor: pointer;
}

.chat-header {
    background: linear-gradient(90deg, #001A2B 0%, #005791 100%);
    color: white;
    padding: 10px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    text-align: left;
    display: flex;
    align-items: center;
}
.chat-img {
    margin-right: 15px;
}
.chat-title {
    font-weight: bold;
}

.chat-subtitle {
    font-size: 0.8em;
}

.chat-body {
    padding: 10px;
    flex-grow: 1;
    overflow-y: auto;
    height: 335px;
}
</style>


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
                    <form class="content-search">
                        <input type="search" class="" name="" placeholder="Enter Your Bot Name">
                        <div class="form-btn">
                            <button class="form-frist-btn" type="submit">Use This Template</button>
                            <button class="form-second-btn" type="submit">Back</button>
                        </div>
                    </form>
                    <div>

                    </div>
                </div>

                <div class="col-5 chat-section">
                    <div class='chat-container chat-boat-position' id='chat-container'>
                       
                           <div class='chat-header'>
                                        
                                        <div class="d-flex head-name">
                                            <div class='chat-img'>
                                              <img src="{{asset('assets/images/clientimg.png')}}" />
                                            </div>
                                               <div class='chat-title'><h3>Lorem Ipsum</h3>
                                                 <p>Support</p>
                                               </div>
                                           
                                        </div>
                                         
                                        <div class='icon-head'>  
                                            <div >
                                                <img src="{{asset('assets/images/reload.png')}}">
                                            </div>
                                            <div class='closeicon'>
                                                <img src= "{{asset('assets/images/colse.png')}}"id='close-chat-icon'>
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
                            <input type='text' placeholder='Enter your message...'>
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
$(document).ready(function(){
    const temp = <?php echo json_encode($templates); ?>;

    console.log(temp.button_type);

    // Apply the font, font size, and alignment to the chat container
    $('#chat-container').css({
        'font-family': temp.font,
        'font-size': temp.font_size,
        'text-align': temp.text_alignment,
        'background-color': temp.bubble_background
    });

    // Apply the styles for bot messages (question_color)
    $('.message.bot .text').css({
        'background-color': temp.question_color,
        'border-radius': temp.radius
    });

    // Apply the styles for user messages (answer_color)
    $('.message.user .text').css({
         'background-color': temp.answer_color,
        'border-radius': temp.radius
    });

   // Apply border-radius to buttons
    $('.chat-btn button').css({
        'border-radius': temp.button_type // Apply the extracted value
    });
 
});


</script>
@endsection

