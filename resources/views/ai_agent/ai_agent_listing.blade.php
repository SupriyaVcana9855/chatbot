@extends('layout.app')
@section('content')

<div class="boxpadding ">
    <div class="row searchi-section mt-4">
        <div class="col-2 set-boat-heading">
            <h6>AI Agents</h6>
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
                <a href="{{route('addagentform')}}"><button class="btn">Add New Agent</button></a>
            </div>
        </div>
        @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif
    </div>
    <div class="row bottable">
        <div class="col-12 bot-table mb-3">
            <table class="table table-striped">
                <tbody>
                    <div class="col-12 bot-table mb-3 bgwhiten">
                        <table class="table table-striped set-fonttable ai-table ">

                            <thead class="sethead ">
                                <tr>
                                    <th>All</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                @if(count($agents) > 0)
                                @foreach ($agents as $key => $details)
                                <tr>
                                    <td class="first-td set-fonttable">
                                        <div class="d-flex align-items-center">
                                            <div class="set-boat-heading Ai-nname">
                                                <img src="./assets/images/vgbot.png" alt="Avatar" class="" width="40" height="40">
                                                <span class="ml-2">VG Talk {{$key+1}}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h3>{{$details->name}}</h3>
                                    </td>
                                    <td>
                                        <h3>{{$details->email}}</h3>
                                    </td>
                                    <td>
                                        <h3>{{$details->phone_number}}</h3>
                                    </td>
                                    <td>
                                        <h3>@if ($details->status == 1)
                                            <span>Active</span>
                                            @else
                                            <span>InActive</span>
                                            @endif
                                        </h3>
                                    </td>
                                    <td>
                                        <div class="dropdown set-menu-btn d-inline-flex ">

                                            <img class="file-img" src="./assets/images/boat/file.png">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                                <i class="fa-solid fa-ellipsis-vertical" style="color: #8b8b8b;"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li class="border-bottom"><a class="dropdown-item" href="{{route('addagentform',$details->id)}}">Edit <span><img src="./assets/images/editicon.png"></span></li></a>
                                                <li><a class="dropdown-item" href="{{route('deleteagent',$details->id)}}">Delete <span><img src="./assets/images/boat/Vector (6).png"></span></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="12" style="color:#777777; text-align: center;">No agents found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </tbody>
            </table>
        </div>
    </div>
</div>


<style>
    a {
        text-decoration: none;
    }

    button.btn {
        width: max-content;
    }

    .bot-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 10px;
        cursor: pointer;
    }

    .bot-option:hover {
        background-color: #f8f9fa;
    }

    .bot-option i {
        height: 24px;
        margin-right: 15px;
    }

    .modal-body {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .modal-body {
        text-align: center;
    }

    .modal-body p {
        margin-bottom: 20px;
    }

    .btn-template {
        margin-bottom: 10px;
    }

    .modal-dialog {
        top: 151px;
    }
</style>
@endsection