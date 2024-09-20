@extends('layout.app')

@section('content')
<style>
    .option-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .add-question,
    .add-more-options {
        background: #146c43 !important;
        padding: 0.375rem 0.75rem;
        border: none !important;
        border-radius: 5px;
    }

    .question-block input {
        border-right: 1px solid #dee2e6 !important;
    }
</style>
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
                            <div class="question-block" data-question-index="0">
                                <input type="hidden" name="questions[0][bot_id]" value="{{ $id }}">
                                <div class="mb-3">
                                    <label for="question-input" class="form-label mb-3">Question 1</label>
                                    <input type="text" class="form-control" placeholder="Enter Question?" name="questions[0][text]">
                                </div>
                                <div class="row addMoreOptions">
                                    <div class="col-md-12 mb-3">
                                        <label for="option-input" class="form-label mb-3">Option 1</label>
                                        <input type="text" class="form-control" placeholder="Enter option 1" name="questions[0][options][]">
                                    </div>
                                </div>
                                <div>
                                    <div class="mt-4 ms-0 me-0 ps-0 pe-0">
                                        <button type="button" class="btn-success add-more-options">Add Option</button>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>

                        <div>
                            <button type="button" class="btn-success add-question">Add Another Question</button>
                            <button type="submit" class="btn btn-primary ms-3" style="width: 130px;">Save All</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let questionIndex = 1;
        let optionIndexes = {};

        // Add more options for a question
        $(document).on('click', '.add-more-options', function() {
            let currentQuestionIndex = $(this).closest('.question-block').data('question-index');

            if (typeof optionIndexes[currentQuestionIndex] === 'undefined') {
                optionIndexes[currentQuestionIndex] = 1;
            }

            optionIndexes[currentQuestionIndex]++;

            let newOptionsBlock = `
            <div class="row option-row">
                <div class="col-md-11 mt-2">
                    <label for="option-input" class="form-label">Option ${optionIndexes[currentQuestionIndex]}</label>
                    <input type="text" class="form-control" placeholder="Enter option ${optionIndexes[currentQuestionIndex]}" name="questions[${currentQuestionIndex}][options][]">
                </div>
                <div class="col-md-1 mt-2 mb-1 d-flex justify-content-end">
                    <button type="button" class="btn btn-danger" onclick="removeRow(this)">x</button>
                </div>
            </div>`;

            $(this).closest('.question-block').find('.addMoreOptions').append(newOptionsBlock);
        });

        // Add another question
        $('.add-question').click(function() {
            optionIndexes[questionIndex] = 1;

            let newQuestionBlock = `
            <div class="question-block" data-question-index="${questionIndex}">
                <input type="hidden" name="questions[${questionIndex}][bot_id]" value="{{ $id }}">
                <div class="mb-3">
                    <label for="question-input" class="form-label mb-3">Question ${questionIndex + 1}</label>
                    <input type="text" class="form-control" placeholder="Enter Question?" name="questions[${questionIndex}][text]">
                </div>
                <div class="row addMoreOptions">
                    <div class="col-md-12 mb-3">
                        <label for="option-input" class="form-label mb-3">Option 1</label>
                        <input type="text" class="form-control" placeholder="Enter option 1" name="questions[${questionIndex}][options][]">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" class="btn-success add-more-options">Add Option</button>
                </div>
                <hr>
            </div>`;

            $('#questions-container').append(newQuestionBlock);
            questionIndex++;
        });
    });

    function removeRow(element) {
        $(element).closest('.row').remove();
    }

    function removeQuestion(element) {
        $(element).closest('.question-block').remove();
    }


   // Form submission validation
$('form').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    // Validate question blocks
    const questionBlocks = document.getElementsByClassName('question-block');
    let isValid = true;

    for (let i = 0; i < questionBlocks.length; i++) {
        const questionBlock = questionBlocks[i];
        const questionInput = questionBlock.querySelector(`input[name="questions[${i}][text]"]`);
        const optionInputs = questionBlock.querySelectorAll(`input[name="questions[${i}][options][]"]`);

        // Validate the question input
        if (questionInput.value.trim() === '') {
            isValid = false;
            $(questionInput).addClass('is-invalid');
            $(questionInput).next('.invalid-feedback').remove();
            $(questionInput).after('<div class="invalid-feedback">This question is required.</div>');
        } else {
            $(questionInput).removeClass('is-invalid');
            $(questionInput).next('.invalid-feedback').remove();
        }

        // Validate each answer input
        optionInputs.forEach(function(optionInput) {
            if (optionInput.value.trim() === '') {
                isValid = false;
                $(optionInput).addClass('is-invalid');
                $(optionInput).next('.invalid-feedback').remove();
                $(optionInput).after('<div class="invalid-feedback">This option is required.</div>');
            } else {
                $(optionInput).removeClass('is-invalid');
                $(optionInput).next('.invalid-feedback').remove();
            }
        });
    }

    if (isValid) {
        // Submit the form if valid
        this.submit();
    }
});

</script>
@endsection