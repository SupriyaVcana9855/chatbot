@extends('layout.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/setup.css') }}">
<div class="boxpadding">
    <div class="accordion-body">
        <div class="row">
            <div class="col-xl-12">
                <div class="boxinner">

                    <h1>Please add question sequence wise.</h1>

                    <form action="{{ route('addQuestion') }}" method="post">
                        @csrf
                        <div id="questions-container">
                            <!-- Question block -->
                            <div class="question-block">
                                <div class="textbox">
                                    <h6>Text</h6>
                                </div>
                                <input type="hidden" name="questions[0][bot_id]" value="{{ $id }}">
                                <!-- <input type="hidden" name="questions[0][bot_id]" value="Question"> -->
                                <div class="mb-3">
                                    <label for="question-input" class="form-label">Question 1</label>
                                    <input type="text" class="form-control" placeholder="Enter Question?" name="questions[0][question]">
                                </div>

                                <div class="row addMoreOptions">
                                    <div class="col-md-8">
                                        <label for="option-input" class="form-label">Option 1</label>
                                        <input type="text" class="form-control" placeholder="Enter option1" name="questions[0][options][0]">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success add-more-options">Add Option</button>
                                </div>
                                <hr> 
                            </div>
                        </div>

                        <!-- Button to add more questions -->
                        <button type="button" class="btn btn-success add-question">Add Another Question</button>
                        <button type="submit" class="btn btn-primary" style="width: 86px;">Save All</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
   $(document).ready(function() {
    let questionIndex = 1; // Start questionIndex at 1
    let optionIndexes = {}; // Track option indexes for each question

    // Event delegation for adding more options dynamically
    $(document).on('click', '.add-more-options', function() {
        let currentQuestionIndex = $(this).closest('.question-block').data('question-index'); // Get the current question index
        
        // Initialize optionIndexes for this question if it doesn't exist
        if (typeof optionIndexes[currentQuestionIndex] === 'undefined') {
            optionIndexes[currentQuestionIndex] = 1;
        }

        optionIndexes[currentQuestionIndex]++; // Increment the option index for this question

        let newOptionsBlock = `
            <div class="row option-row">
                <div class="col-md-8">
                    <label for="option-input" class="form-label">Option ${optionIndexes[currentQuestionIndex]}</label>
                    <input type="text" class="form-control" placeholder="Enter option ${optionIndexes[currentQuestionIndex]}" name="questions[${currentQuestionIndex}][options][${optionIndexes[currentQuestionIndex] - 1}]">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger" onclick="removeRow(this)">Delete</button>
                </div>
            </div>`;

        $(this).closest('.question-block').find('.addMoreOptions').append(newOptionsBlock);
    });

    // Function to add more questions dynamically
    $('.add-question').click(function() {
        optionIndexes[questionIndex] = 1; // Initialize the option index for the new question

        let newQuestionBlock = `
            <div class="question-block" data-question-index="${questionIndex}">
                <div class="textbox">
                    <h6>Text</h6>
                </div>
                <input type="hidden" name="questions[${questionIndex}][bot_id]" value="{{ $id }}">
                <div class="col-md-2 mt-2">
                    <button type="button" class="btn btn-danger remove-question" onclick="removeQuestion(this)">Remove Question ${questionIndex + 1}</button>
                </div>
                <div class="mb-3">
                    <label for="question-input" class="form-label">Question ${questionIndex + 1}</label>
                    <input type="text" class="form-control" placeholder="Enter Question?" name="questions[${questionIndex}][question]">
                </div>

                <div class="row addMoreOptions">
                    <div class="col-md-8">
                        <label for="option-input" class="form-label">Option 1</label>
                        <input type="text" class="form-control" placeholder="Enter option1" name="questions[${questionIndex}][options][0]">
                    </div>
                </div>

                <div class="col-md-2 mt-2">
                    <button type="button" class="btn btn-success add-more-options">Add More</button>
                </div>
              
                <hr> 
            </div>`;

        $('#questions-container').append(newQuestionBlock);
        questionIndex++; // Increment the question index for the next question
    });
});

// Function to remove a row (option)
function removeRow(element) {
    $(element).closest('.row').remove();
}

// Function to remove a question block
function removeQuestion(element) {
    $(element).closest('.question-block').remove();
}

</script>
@endsection