@extends('layout.app')

@section('content')
<style>

.boxpadding {
    position: relative;
    top: 100px;
    height: 85vh;
    margin-top: 50px;
    margin-top: 30px;
    overflow-x: scroll;
    margin-right: 20px;
    padding-right: 25px !important;
}
  .searchi-section {    
    margin-bottom:40px !important;     
  }
  .accordion-item h2 button {
    font-size: 20px;
    box-shadow: 0 2px 7px #33333340!important;
    font-weight: 600;
    border-radius: 5px;
    line-height: 30px;
    text-align: left;
    color: #04498c;
}
 .accordion-item {
    border-radius: 5px;
    border: none!important;
    margin-bottom: 30px;
} .searchi-section h6 {
    margin:10px 0px 0px !important;
}
.accordion-body {
  background :transparent;
  padding:40px 5px 40px !important;
}
.accordion-item {
    background: none !important;
}

.accordion-button:not(.collapsed) {
    background-color: #fff!important;
}
.boxinner {
  box-shadow: 0px 1px 17.1px 0px #6565651C;
  padding:35px !important;
  margin-bottom:25px;
}
</style>
      <div class=" boxpadding">
        <div class="row searchi-section mt-4">
          <div class="col-2 set-boat-heading">
            <h6>AI Agents</h6>
          </div>
          <div class="col-10">
            <div class="search-container">
              <select class="form-control form-select  mr-2">
                <option>All</option>
                <option>Lead Generation Bot</option>
                <option>Customer Support Bot</option>
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
        <div class="accordion" id="accordionExample">
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button
            class="accordion-button"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#About"
            aria-expanded="true"
            aria-controls="collapseOne"
          >
          Website Bot
          </button>
        </h2>
        <div
          id="About"
          class="accordion-collapse collapse"
          aria-labelledby="headingOne"
          data-bs-parent="#accordionExample"
        >
          <div class="accordion-body">
          <div class="row">
            <div class="col-xl-6">
              <div class="boxinner">
                  sdfgthyu
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
           
    </div>
      </div>
    </div>
 @endsection
