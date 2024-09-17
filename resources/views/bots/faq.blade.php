@extends('layout.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/setup.css') }}">
<div class="boxpadding">
    <div class="accordion-body">
        <div class="row">
            <div class="col-xl-12">
                <div class="boxinner">

                    <h1>Business Services FAQs</h1>

                    <form action="{{ route('addFaq') }}" method="post">
                        @csrf
                        <div id="questions-container">
                            <!-- Question block -->
                            <div class="question-block">
                                <input type="hidden" name="bot_id" value="{{$chatBot->id}}">
                                <div class="mb-3">
                                    <label for="question-input" class="form-label">Question 1</label>
                                    <input type="text" class="form-control" placeholder="Enter Question?" name="questions[]">
                                </div>

                                <div class="row addMoreOptions">
                                    <div class="col-md-12">
                                        <label for="answer-input" class="form-label">Answer</label>
                                        <input type="text" class="form-control" placeholder="Enter answer" name="answer[]">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button to add more questions -->
                        <div class="">
                            <button type="button" class="btn-success add-question">Add Another Question</button>
                            <button type="submit" class="btn btn-primary ms-3" style="width: 130px;">Save All</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .option-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .question-block {
        padding-bottom: 25px;
        margin-bottom: 25px;
        border-bottom: 1.5px solid #dee2e6;
    }

    .add-question,
    .add-more-options {
        background: #146c43 !important;
        padding: 0.375rem 0.75rem;
        border: none !important;
        border-radius: 5px;
    }

    .remove-question {
        padding: 0.375rem 0.75rem;
        border: none !important;
        border-radius: 5px;
    }

    .question-block input {
        border-right: 1px solid #dee2e6 !important;
    }
</style>
<script>
    $(document).ready(function() {
        let questionIndex = 1; // Start questionIndex at 1
        let optionIndexes = {}; // Track option indexes for each question

        // Event delegation for adding more options dynamically


        // Function to add more questions dynamically
        $('.add-question').click(function() {
            optionIndexes[questionIndex] = 1; // Initialize the option index for the new question

            let newQuestionBlock = `
            <div class="question-block" data-question-index="${questionIndex}">
                <div class="row">
                
                
                <div class="col-md-11 ">
                    <label for="question-input" class="form-label mt-3 mb-3">Question ${questionIndex + 1}</label>
                    <input type="text" class="form-control" placeholder="Enter Question?" name="questions[]">
                </div>
                <div class="col-md-1 mt-2 mb-2 d-flex justify-content-end align-items-end">
                    <button type="button" class="  btn-danger remove-question" onclick="removeQuestion(this)"> x </button>
                </div>
                </div>

                <div class="row addMoreOptions">
                                    <div class="col-md-12">
                                        <label for="answer-input" class="form-label mt-3 mb-3">Answer</label>
                                        <input type="text" class="form-control" placeholder="Enter answer" name="answer[]">
                                    </div>
                                </div>

       
              
                 
            </div>`;

            $('#questions-container').append(newQuestionBlock);
            questionIndex++; // Increment the question index for the next question
        });
    });

    // Function to remove a row (option)

    // Function to remove a question block
    function removeQuestion(element) {
        $(element).closest('.question-block').remove();
    }
</script>
@endsection