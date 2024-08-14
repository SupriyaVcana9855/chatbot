@extends('layout.app')
@section('content')

<div>
    <div class="row searchi-section mt-4">
        <div class="col-2 set-boat-heading main-set">
            <h6>Bots</h6>
        </div>
        <div class="col-10">
            <div class="search-container">
                <select class="form-control form-select mr-2">
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
                <button class="btn" data-toggle="modal" data-target="#createBotModal">Built A WhizBot</button>
            </div>
        </div>
    </div>

        

</div>


@endsection
