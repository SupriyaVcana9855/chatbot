@extends('layout.app')
@section('content')
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
</style>

<div class="boxpadding">
    <div class="row searchi-section mt-4">
        <div class="col-2 set-boat-heading">
            <h6 style="margin-top: 40px;">Bot Questions</h6>
        </div>

        <div class="col-4 set-boat-heading">
            <!-- Corrected button for adding question -->
            <a class="btn btn-primary" style="margin-top: 40px; margin-left: 950px; color: black;" href="{{ route('botQuestion', ['id' => $id ?? '']) }}">+Add</a>
        </div>

        <div class="col-10"></div>

        <div class="row bottable">
            @if (session('success'))
            <div class="col-sm-12">
                <!-- Corrected Bootstrap 5 close button -->
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="col-sm-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            <div class="col-12 bot-table mb-3">
                <table id="myTable" class="display">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bots as $bot)
                        <tr>
                            <td style="color: black;">
                                {{ $bot->question ?? 'No question available' }}
                            </td>
                            <td style="color: black;">
                                {{ $bot->answer ? $bot->answer : ($bot->option1 ? $bot->option1 . ', ' . $bot->option2 : 'N/A') }}
                            </td>
                            <td>
                                <div class="dropdown set-menu-btn d-inline-flex">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical" style="color: #8b8b8b;"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="border-bottom">
                                            <a class="dropdown-item" href="{{ route('botQuestion', ['bot_id' => $bot->chat_bot_id, 'questionId' => $bot->id]) }}">
                                                Edit <span><img src="{{asset('/assets/images/editicon.png')}}"></span>
                                            </a>
                                        </li>
                                        <!-- Added class and data-value to identify which item to delete -->
                                        <li><a class="dropdown-item delete-question" data-value="{{$bot->id}}" href="javascript:;">Delete <span><img src="{{asset('/assets/images/boat/Vector (6).png')}}"></span></a></li>
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
@endsection

@section('java_scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#myTable').DataTable();

        // Handle question selection
        $('.selctQuestion').on('change', function() {
            var question1 = $(this).attr('data-question1-id');
            var question2 = $(this).val();
            var bot_id = $('#bot_id').val();

            $.ajax({
                url: "{{ route('addQuestionFlow') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}", // CSRF token for security
                    question_1: question1,
                    question_2: question2,
                    bot_id: bot_id,
                },
                success: function(data) {
                    alert("Sequence Saved successfully.");

                    // Clear and populate new options
                    $('.selctQuestion').empty().append('<option selected>Choose A Question</option>');

                    data.forEach(function(item) {
                        $('.selctQuestion').append('<option value="' + item.id + '">' + item.question + '</option>');
                    });
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });

        // Handle delete question functionality
        $(document).on('click', '.delete-question', function(event) {
            event.preventDefault(); // Prevent default behavior

            // Get the question ID from data-value attribute
            const questionsDeleteId = $(this).data('value');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't delete this Question!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send the AJAX request to delete the question
                    $.ajax({
                        type: "GET",
                        url: "{{ route('questionsDelete') }}" + '/' + questionsDeleteId,
                        success: function(data) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Question has been deleted.",
                                icon: "success"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload(); // Reload the page
                                }
                            });
                        },
                        error: function(error) {
                            console.error('Error deleting:', error);
                        }
                    });
                }
            });
        });
    });

    // Set bot type
    function dataType(data) {
        $('#bottype').val(data);
    }
</script>
@endsection
