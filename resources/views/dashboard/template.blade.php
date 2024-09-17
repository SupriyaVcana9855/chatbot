@extends('layout.app')

@section('content')

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
            <div class="row set-template">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="template-boxsado">
                        <img src="./assets/images/wizboat template/temp1.png">
                        <div class="">
                            <div class="template-card">
                                <h3>Digital Marketing</h3>
                                <a href="{{route('templateview',1)}}">Use This</a>
                            </div>
                            <p>Easily collect product or service reviews from customers, then convert the feedback into actionable insights to improve quality.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="template-boxsado">
                        <img src="./assets/images/wizboat template/temp2.png">
                        <div class="">
                            <div class="template-card">
                                <h3>E-Commerce Website</h3>
                                <a href="{{route('templateview',2)}}">Use This</a>
                            </div>
                            <p>Help your customer solve their queries quickly and 24/7. Get their business requirements and suggest the respective themes and...</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="template-boxsado">
                        <img src="./assets/images/wizboat template/temp3.png">
                        <div class="">
                            <div class="template-card">
                                <h3>Feedback Bot</h3>
                                <a href="{{route('templateview',3)}}">Use This</a>
                            </div>
                            <p>Help the start-up entrepreneurs to get details of their eligibility, areas they can improve, documentation required, and other...</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="template-boxsado">
                        <img src="./assets/images/wizboat template/temp4.png">
                        <div class="">
                            <div class="template-card">
                                <h3>Lead Generation</h3>
                                <a href="{{route('templateview',4)}}">Use This</a>
                            </div>
                            <p>Our chatbot template captures leads and offers customer support. Discover activities available, book live/virtual tours, and find...</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="template-boxsado">
                        <img src="./assets/images/wizboat template/temp5.png">
                        <div class="">
                            <div class="template-card">
                                <h3>SaaS Enquiry</h3>
                                <a href="">Use This</a>
                            </div>
                            <p>Chatbot helps your customer know the loan schemes available to them based on their interest, eligibility, and other information.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="template-boxsado">
                        <img src="./assets/images/wizboat template/temp6.png">
                        <div class="">
                            <div class="template-card">
                                <h3>Webinar Registration</h3>
                                <a href="">Use This</a>
                            </div>
                            <p>Get quick and convenient assistance with this fitness chatbot, available 24/7 to answer any questions and provide support.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
@endsection
