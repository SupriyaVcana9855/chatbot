@extends('layout.app')
@section('content')

<div class="col-lg-9 col-md-9 col-sm-8">
    <div class="row searchi-section mt-4">
        <div class="col-2 set-boat-heading">
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
    <div class="row bottable">
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
                            <img src="./assets/images/boat/Setup.png">
                        </button></td>
                        <td><button class="btn btn-outline-secondary">
                            <img src="./assets/images/boat/Triggers.png">
                        </button></td>
                        <td><button class="btn btn-outline-secondary">
                            <img src="./assets/images/boat/Bot Chats.png">
                        </button></td>
                        <td><button class="btn btn-outline-secondary">
                            <img src="./assets/images/boat/Live Chat.png">
                        </button></td>
                        <td><button class="btn btn-outline-secondary">
                            <img src="./assets/images/boat/Analytics.png">
                        </button></td>
                        <td><button class="btn btn-outline-secondary">
                            <img src="./assets/images/boat/Settings.png">
                        </button></td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1"></label>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown set-menu-btn">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical" style="color: #b4b4b4;"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="border-bottom"><a class="dropdown-item" href="#">Landing Page <span><img src="./assets/images/boat/web-traffic 1.png"></span></a></li>
                                    <li class="border-bottom"><a class="dropdown-item" href="#">Duplicate <span><img src="./assets/images/boat/Group.png"></span></a></li>
                                    <li><a class="dropdown-item" href="#">Delete <span><img src="./assets/images/boat/Vector (6).png"></span></a></li>
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
                        <span>Lead Generation Bot (or) Any Data Collection Bot</span>
                    </div>
                </div>
                <div class="bot-option">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-headphones"></i>
                        <span style="float: right; line-height: 1;color: #000;" data-dismiss="modal" data-toggle="modal"  aria-label="Close" data-target="#createCustomerSupportBotModal">Customer Support Bot</span>
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
                <button type="button" class="btn btn-primary btn-template">Pick From Templates</button>
                <div>OR</div>
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createCustomBotModal" >Create Your Own Bot</button>
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
                <form>
                    <div class="form-group w-80 pt-3">
                        <input type="text" class="form-control" id="botName" placeholder="Enter bot name">
                    </div>
                    <button type="submit" class="btn center btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
