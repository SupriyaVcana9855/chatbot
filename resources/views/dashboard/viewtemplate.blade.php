@extends('layout.app')

@section('content')
<link rel="stylesheet" href="{{asset('/css/detialsTemplate.css')}}">

        <div>
        <div class="row search-section-modified mt-4">
    <div class="col-2 set-heading-modified">
        <h6>Templates</h6>
    </div>
    <div class="col-10">
        <div class="search-container-modified">
            <select class="form-control-modified form-select-modified mr-2">
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
            <div class="input-group-modified set-select-modified mr-2">
                <input type="text" class="form-control-modified" placeholder="Search Here For Bot">
                <div class="input-group-prepend-modified">
                    <span class="input-group-text-modified"><i class="fas fa-search"></i></span>
                </div>
            </div>
            <button class="btn-modified">Built A WhizBot</button>
        </div>
    </div>
</div>
<div class="row mb-5">
    <div class="col-md-6 col-lg-6 col-sm-12 main-heading-modified">
         <h4>Template</h4>
    </div>
    <div class="col-md-6 col-lg-6 col-sm-12">
    <div class="search-section-modified">
    <div class="select-temp-modified">
    <select name="All" id="">
    <option value="">All</option>
    <option value="">option1</option>
    <option value="">option2</option>
    <option value="">option3</option>
    <option value="">option4</option>
    </select>
    </div>
    <div>
    <form class="search-sec-modified">
    <input type="search" class="gsearch-modified" name="gsearch">
    <button type="submit"><i class="fas fa-search"></i></button>
    </form>
    </div>
    </div> 
    </div>

    <div class="col-12">
        <div class="row service-container-modified mt-5">
            <div class="col-7 temp-text-modified ">
              <h4>Digital Marketing</h4>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
              <h5>Click Below to replicate & edit the bot.</h5>
              <form class="content-search-modified">
                <input type="search" class="" name="" placeholder="Enter Your Bot Name">
                <div class="form-btn-modified">
                <button class="form-first-btn-modified" type="submit">Use This Template</button>
                <button class="form-second-btn-modified" type="submit">Back</button>
                </div>
              </form>
            </div>

            <div class="col-5 chat-section-modified">
                <div class="chat-container-modified chat-position-modified" id="chat-container-modified">
                    <div class="chat-header-modified">
                        <div class="d-flex-modified head-name-modified">
                            <div class="chat-img-modified">
                              <img src="clientimg.png" />
                            </div>
                               <div class="chat-title-modified"><h3>Lorem Ipsum</h3>
                                 <p>Support</p>
                               </div>
                        </div>
                        <div class="icon-head-modified">  
                            <div >
                                <img src="reload.png">
                            </div>
                            <div class="close-icon-modified">
                                <img src="close.png" id="">
                            </div>
                        </div>
                    </div>
                    <div class="chat-body-modified">
                        <div class="message-modified bot-modified">
                            <div class="text-modified">Hello! Welcome to our chatbot.</div>
                        </div>
                        <div class="message-modified user-modified">
                            <div class="text-modified">Hi there! I'm interested in building an e-commerce website.</div>
                        </div>
                        <div class="message-modified bot-modified">
                            <div class="text-modified">That's great to hear! What kind of products do you plan to sell?</div>
                        </div>
                    </div>
                    <div class="chat-footer-modified">
                        <input type="text" placeholder="Enter your message...">
                        <button><img src="fileupload.png"></button>
                        <button id="sendButton-modified"><img src="send.png"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
   
@endsection
