@extends('layout.app')
@section('content')

<div class="boxpadding ">
    <div class="row searchi-section mt-4">
        <div class="col-2 set-boat-heading">
            <h6 style="    margin-top: 40px;">Bot Questions</h6>
        </div>
      
          <div class="col-4 set-boat-heading">
            <button class="btn btn-primary" style="margin-top: 40px; margin-left : 950px"><a style="color: black;" href="{{ route('botQuestion', ['id' => $id]) }}">+Add</a></button>
        </div>
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="col-10">
 
    </div>
    <div class="row bottable">
        <div class="col-12 bot-table mb-3">
        <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                        {{-- <th>Prefrence</th> --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bots as $bot)
                    <tr >
                      <td  style="color: black;">
                            {{ $bot->question ?? 'No question available' }}
                            </td>
                            <td  style="color: black;">
                                {{ $bot->answer ? $bot->answer : ($bot->option1 ? $bot->option1 . ', ' . $bot->option2 : 'N/A') }}
                            </td>   
                            {{-- <td  style="color: black;"> --}}
                            {{-- <input type ="hidden" class="question1" name="question1" value="{{ $bot->id }}"> --}}
                                {{-- <input type ="hidden" id="bot_id" name="bot_id" value="{{ $id }}">

                                <select class="form-select selctQuestion" name="question2"   aria-label="Choose A Question Type" data-question1-id="{{ $bot->id }}">
                                        <option selected>Choose A Question</option>
                                        @foreach($questionsNotInFlow as $prefrence)
                                        @if($prefrence->id != $bot->id)
                                            <option value="{{ $prefrence->id }}">{{ $prefrence->question }}</option>
                                        @endif
                                        @endforeach
                                </select> --}}
                            {{-- </td>        --}}
                            <td  style="color: black;">
                                <a href="{{ route('editPrefrence') }}">Edit</a>
                            </td>   
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
    
    </style>
{{-- editPrefrence --}}
    <script>
    
    
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
   $('.selctQuestion').on('change', function () {
    var question1 = $(this).attr('data-question1-id');
    var question2 = $(this).val();
    var bot_id = $('#bot_id').val();
    $.ajax({
        url: "{{ route('addQuestionFlow') }}",
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",  // CSRF token for security
            question_1: question1,
            question_2: question2,
            bot_id: bot_id,
        },
        success: function (data) {
            alert("Sequence Saved successfully.");

            // Clear existing options in select (except the first default option)
            $('.selctQuestion').empty().append('<option selected>Choose A Question</option>');

            // Loop through the data and append new options
            data.forEach(function (item) {
                $('.selctQuestion').append('<option value="' + item.id + '">' + item.question + '</option>');
            });
        },
        error: function (error) {
alert("sscs");

            console.error('Error:', error);
        }
    });
});


       function dataType(data)
      {
        $('#bottype').val(data);
      }
     
    </script>
@endsection

