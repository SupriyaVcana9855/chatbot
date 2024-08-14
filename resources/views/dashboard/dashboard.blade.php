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
                <h6>TOTAL CHATS</h6>
                <p>1</p>
              </div>
              <div class="">
                <img src="{{asset('/assets/images/totalchat.png')}}" >
              </div>
          </div>
        </div>
          <div class="col-lg-3 col-md-6 mt-3 mb-3">
            <div class="set-total">
              <div class="set-inner-content">
                <h6>TOTAL MESSAGES</h6>
                <p>59</p>
              </div>
             
              <div class="">
                <img src="{{asset('/assets/images/TOTAL MESSAGES.png')}}" >
              </div>
          </div>
          </div>
          <div class="col-lg-3 col-md-6 mt-3 mb-3">
            <div class="set-total">
              <div class="set-inner-content">
                <h6>TOTAL FEEDBACKS</h6>
                <p>0</p>
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
                <p>189</p>
              </div>
              <div class="">
                <img src="{{asset('/assets/images/TOTAL VISITORS.png')}}" >
              </div>
          </div>
          </div>
        
      </div>
    </div>
@endsection
