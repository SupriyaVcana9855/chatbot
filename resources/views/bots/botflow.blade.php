@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/setup.css') }}">

    <div class="boxpadding">
        <div class="accordion-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="boxinner">
                        <form action="{{ route('addQuestionFlow') }}" method="post">
                            @csrf
                            <div class="textbox">
                                <h6>Text</h6>
                                <div class="imgboxhead">
                                    <button type="submit" class="btn btn-primary" style="width: 86px;">Save</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="question-type" class="form-label">Question</label>
                                <select class="form-select selctQuestion" id="question_id1" name="question_1" aria-label="Choose A Question Type">
                                    <option selected>Choose A Question</option>
                                    @foreach($questionsNotInFlow as $question)
                                        <option value="{{ $question->id }}">{{ $question->question }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="bot_id" value="{{ $id }}">
                             <div class="mb-3">
                                <label for="question-type" class="form-label">Answer</label>
                                <select class="form-select selctQuestion" id="question-type" name="answer" aria-label="Choose A Question Type">
                                    <option selected>Choose A Question</option>
                                 
                                </select>
                            </div>
                             <div class="mb-3">
                                <label for="question-type" class="form-label">Next Question</label>
                                <select class="form-select selctQuestion" id="question_id2" name="question_2" aria-label="Choose A Question Type">
                                    <option selected>Choose A Question</option>
                                    @foreach($questionsNotInFlow as $question)
                                        <option value="{{ $question->id }}">{{ $question->question }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
jQuery(document).ready(function() {
    jQuery('#question_id1').on('change', function() {
        var val = jQuery(this).val();
        alert(val);
        jQuery.ajax({
            url: "{{ route('getAnswer') }}",
            method: 'GET',
            data: {
                id: val,
            },
            success: function(data) {
                // Clear the previous options
                jQuery('#question-type').empty();
                jQuery('#question-type').append('<option selected>Choose A Question</option>');
                    if (data.option1 != null && data.option2 != null) {
                        jQuery('#question-type').append('<option value="' + data.option1 + '">' + data.option1 + '</option>');
                        jQuery('#question-type').append('<option value="' + data.option2 + '">' + data.option2 + '</option>');
                    } else if (data.answer) {
                        jQuery('#question-type').append('<option value="' + data.answer + '">' + data.answer + '</option>');
                    } else {
                        jQuery('#question-type').append('<option value="no-data">No data available</option>');
                    }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });


    $('#question_id2').on('change',function(){
        var val = $('#question_id1').val();
        var val2 = $(this).val();
        if(val == val2)
        {
            alert("Please select another question as you already choose it.")
        }
    })
});
</script>

@endsection
