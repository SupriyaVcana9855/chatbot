@extends('layout.app')
@section('content')

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

<div class="boxpadding ">
    <div class="row searchi-section mt-4">
        <div class="col-2 set-boat-heading">
            <h6>AI Agents</h6>
        </div>

        <div class="col-1">
            <div class="search-container">
                <a href="{{route('addagentform')}}"><button class="btn">Add New Agent</button></a>
            </div>
        </div>
    </div>
    <div class="row bottable">
        @if (session('success'))
        <div class="col-sm-12">
            <div class="alert  alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="col-sm-12">
            <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        @endif
        <div class="col-12 bot-table mb-3">
            <table class="table table-striped">
                <tbody>
                    <div class="col-12 bot-table mb-3 bgwhiten">
                        <table class="table table-striped set-fonttable ai-table" id="agentTable">

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
                                            <button type="button" class="btn btn-success">Online</button>
                                            @else
                                            <button type="button" class="btn btn-danger">Offline</button>
                                            @endif
                                        </h3>
                                    </td>
                                    <td>
                                        <div class="dropdown set-menu-btn d-inline-flex ">

                                            <img class="file-img" src="{{asset('/assets/images/boat/file.png')}}">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                                <i class="fa-solid fa-ellipsis-vertical" style="color: #8b8b8b;"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li class="border-bottom"><a class="dropdown-item" href="{{route('addagentform',$details->id)}}">Edit <span><img src="{{asset('/assets/images/editicon.png')}}"></span></li></a>
                                                <li><a class="dropdown-item" id="deleteagent" data-value="{{$details->id}}" href="javascript:;">Delete <span><img src="{{asset('/assets/images/boat/Vector (6).png')}}"></span></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('java_scripts')
<script>
    $(document).ready(function() {

        // Initialize DataTable
        let table = new DataTable('#agentTable', {
            language: {
                emptyTable: "No data available" // Custom message
            }
        });

        // Send the AJAX request to change status in the backend
        $('#customSwitch1').change(function() {
            $('#switch-label').text(this.checked ? '1' : '0');
            var status = this.checked ? '1' : '0';

            // Send the AJAX request to change status in the backend
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/changeStatus',
                data: {
                    'status': status,
                    'user_id': user_id
                },
                success: function(data) {
                    console.log(data.success); // Handle success response
                }
            });
        });

        // Send the AJAX request to delete record in the backend


        $(document).on('click', '#deleteagent', function(event) {
            event.preventDefault(); // Prevent the default anchor behavior
            const agentId = $(this).data('value'); // Use 'this' to get the correct data-value


            Swal.fire({
                title: "Are you sure?",
                text: "You won't delete this agent!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send the AJAX request to change status in the backend
                    $.ajax({
                        type: "GET",
                        url: "{{route('deleteAgent')}}" + '/' + agentId,
                        success: function(data) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Agent has been deleted.",
                                icon: "success"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        }
                    });

                }
            });
        });

    });
</script>
@endsection