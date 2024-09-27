@extends('layout.app')
@section('content')

<div class="boxpadding ">
    <div class="row searchi-section mt-4">
        <div class="col-2 set-boat-heading">
            <h6>Bots</h6>
        </div>
        <div class="col-10">
            <div class="search-container">
                <select class="form-control form-select mr-2 filter">
                    <option value="all">All</option>
                    <option value="lead">Lead Generation Bot</option>
                    <option value="support">Customer Support Bot</option>
                    <!-- Add more options as needed -->
                </select>
                <div class="col-md-5 mr-2">
                    <form id="searchForm" action="{{ route('bots') }}" method="GET">
                        <div class="input-group set-select">
                            <input type="text" id="searchInput" name="search" value="{{ request()->input('search', old('search')) }}" class="form-control" placeholder="Search Here For templates">
                            <div class="input-group-prepend">
                            <button type="button" id="clearSearch" class="input-group-text">
                                    <i class="fas fa-times"></i> <!-- Cross icon -->
                                </button>
                                <button type="submit" class="input-group-text"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <button class="btn" data-toggle="modal" data-target="#createBotModal">Built A WhizBot</button>
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
                    @foreach($bots as $bot)
                    <tr>
                        <td class="first-td">
                            <div class="d-flex align-items-center bots-icon">
                                <div class="set-boat-heading-icon">
                                    <img src="./assets/images/vgbot.png" alt="Avatar" class="" width="40" height="40">
                                    <span class="ml-2">{{$bot->name}}</span>
                                </div>
                            </div>
                        </td>
                        <td><button class="btn btn-outline-secondary">
                                <img src="./assets/images/boat/Setup.png" title="Setup Bot">
                            </button></td>
                        <td><button class="btn btn-outline-secondary">
                                <img src="./assets/images/boat/Triggers.png" title="Bot Notifications">
                            </button></td>
                        <td><button class="btn btn-outline-secondary">
                                <a href="{{ route('botChat',$bot->id) }}"> <img src="./assets/images/boat/Bot Chats.png" title="Bot Chat">
                                </a></button></td>

                        @if($bot->type == 'support')
                        <td><button class="btn btn-outline-secondary">
                                <a href="{{ route('singleBotFaqListing',$bot->id) }}"> <img src="./assets/images/totalchat.png" title="Bot faq">
                                    @else
                                </a></button></td>
                        <td><button class="btn btn-outline-secondary">
                                <a href="{{ route('singleBotListing', $bot->id) }}">
                                    <img src="./assets/images/boat/Live Chat.png" title="Live Chat"></a>
                            </button></td>
                        @endif
                        <td><button class="btn btn-outline-secondary">
                                <a href="{{ route('chatanalytics') }}"><img src="./assets/images/boat/Analytics.png" title="Chat Analytics">
                                </a></button></td>
                        <td><button class="btn btn-outline-secondary">
                                <a href="{{ route('setup', $bot->id) }}">
                                    <img src="./assets/images/boat/Settings.png" title="Bot Settings"></a>
                            </button></td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1" title="Bot Enable/Disable"></label>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown set-menu-btn">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical" style="color: #b4b4b4;"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="border-bottom">
                                        <a class="dropdown-item" href="{{ route('setup', $bot->id) }}">
                                            View <span><img src="./assets/images/BOTS.png"></span>
                                        </a>
                                    </li>
                                    <li class="border-bottom">
                                        <a class="dropdown-item" href="{{ route('setup', $bot->id) }}">
                                            Edit <span><img src="./assets/images/editicon.png"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item delete-bot" data-value="{{ $bot->id }}" href="#">
                                            Delete <span><img src="./assets/images/boat/Vector (6).png"></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal 1: Create Bot Modal -->
<div class="modal fade" id="createBotModal" tabindex="-1" role="dialog" aria-labelledby="createBotModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBotModalLabel">Create Bot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="bot-option">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-database"></i>
                        <span class="lead" data-dismiss="modal" data-toggle="modal" aria-label="Close" data-target="#createCustomerSupportBotModal" class="lead" onclick="dataType('lead')">Lead Generation Bot (or) Any Data Collection Bot</span>
                    </div>
                </div>
                <div class="bot-option">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-headphones"></i>
                        <span style="float: right; line-height: 1;color: #000;" data-dismiss="modal" data-toggle="modal" aria-label="Close" data-target="#createCustomerSupportBotModal" class="lead" onclick="dataType('support')">Customer Support Bot</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Create Customer Support Bot Modal -->
<div class="modal fade" id="createCustomerSupportBotModal" tabindex="-1" role="dialog" aria-labelledby="createCustomerSupportBotModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCustomerSupportBotModalLabel">Create Bot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Creating a <strong>Customer Support Bot</strong> is just a matter of seconds now.</p>
                <a id="template-link" href="{{ route('templates') }}">
                    <button style="margin-left: 74px;" type="button" class="btn btn-primary btn-template">
                        Pick From Templates
                    </button>
                </a>
                <div>OR</div>
                <button style="margin-left: 74px;" type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createCustomBotModal" data-dismiss="modal" aria-label="Close">Create Your Own Bot</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3: Create Custom Bot Modal -->
<div class="modal fade" id="createCustomBotModal" tabindex="-1" role="dialog" aria-labelledby="createCustomBotModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-3" id="createCustomBotModalLabel">Create New Bot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form action="{{route('savebot')}}" method="post">
                    @csrf
                    <div class="form-group w-80 pt-3">
                        <input type="hidden" class="form-control" id="bottype" name="type" value="">

                        <input type="text" class="form-control" id="botName" name="name" placeholder="Enter bot name">
                    </div>
                    <button type="submit" class="btn center btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
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
@section('java_scripts')
<script>

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('clearSearch').addEventListener('click', clearSearch);
});

function clearSearch() {
    // Clear the input value
    document.getElementById('searchInput').value = '';
    
    // Create a new URL without the search query
    const url = new URL(window.location.href);
    url.searchParams.delete('search'); // Remove 'search' parameter
    
    // Redirect to the new URL
    window.location.href = url.toString(); // Navigate to the updated URL
}


    $(document).ready(function() {
        // Use a class selector to target delete links
        $(document).on('click', '.delete-bot', function(event) {
            event.preventDefault(); // Prevent the default anchor behavior

            const botId = $(this).data('value'); // Use 'this' to get the current bot ID

            Swal.fire({
                title: "Are you sure?",
                text: "You won't to delete this bot!",
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
                        url: "{{ route('deleteBot', '') }}" + '/' + botId, // Ensure proper URL formation
                        success: function(data) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "The bot has been deleted.",
                                icon: "success"
                            }).then(() => {
                                window.location.reload(); // Reload the page after deletion
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error!",
                                text: "There was a problem deleting the bot.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    });

    function dataType(data) {
        // Set the value of the input
        $('#bottype').val(data);

        // Update the URL in the anchor tag dynamically
        const link = document.getElementById('template-links');
        const newUrl = `{{ route('templates', ['type' => '__data__']) }}`.replace('__data__', data);
        link.href = newUrl;
    }
</script>
@endsection