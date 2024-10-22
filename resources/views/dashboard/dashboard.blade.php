@extends('layout.app')

@section('content')

<div class="bashboard">
        <div class="row">
          <div class="col-lg-12 set-das-heading">
            <h2>Dashboard</h2>
          </div>
          <div class="col-lg-3 col-md-6 mt-3 mb-3">
            <div class="set-total">
              <div class="set-inner-content">
                <h6>TOTAL BOTS</h6>
                <p>{{$getBotCount}}</p>
              </div>
              <div class="">
                <img src="{{asset('/assets/images/BOTS.png')}}" >
              </div>
          </div>
        </div>
          <div class="col-lg-3 col-md-6 mt-3 mb-3">
            <div class="set-total">
              <div class="set-inner-content">
                <h6>TOTAL CHATS</h6>
                <p>{{$getChatCount}}</p>
              </div>
             
              <div class="">
                <img src="{{asset('/assets/images/TOTAL MESSAGES.png')}}" >
              </div>
          </div>
          </div>
          <div class="col-lg-3 col-md-6 mt-3 mb-3">
            <div class="set-total">
              <div class="set-inner-content">
                <h6>TOTAL USERS</h6>
                <p>{{$getUserCount}}</p>
              </div>
             
              <div class="">
                <img src="{{asset('/assets/images/TOTAL FEEDBACKS.png')}}" >
              </div>
          </div>
          </div>
          <div class="col-lg-3 col-md-6 mt-3 mb-3">
            <div class="set-total">
              <div class="set-inner-content">
                <h6>TOTAL VISITORS</h6>
                <p>{{$getVisitorCount}}</p>
              </div>
              <div class="">
                <img src="{{asset('/assets/images/TOTAL VISITORS.png')}}" >
              </div>
          </div>
          </div>
        
      </div>
    </div>
@endsection
