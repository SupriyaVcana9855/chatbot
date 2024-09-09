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

                                    <div class="mb-3">
                                        <label for="question-type" class="form-label">Question Type</label>
                                        <select class="form-select selctQuestion" name="questions[0][type]" aria-label="Choose A Question Type">
                                            <option selected>Choose A Question Type</option>
                                            <option value="option">MCQ</option>
                                            <option value="single">Single</option>
                                        </select>
                                    </div>

                                    <input type="hidden" name="questions[0][bot_id]" value="{{ $id }}">

                                    <div class="mb-3">
                                        <label for="question-input" class="form-label">Question</label>
                                        <input type="text" class="form-control" placeholder="Enter Question?" name="questions[0][question]">
                                    </div>

                                    <!-- Option fields for MCQ -->
                                    <div class="optionClass" style="display:none">
                                        <div class="mb-3 option-item">
                                            <label for="option1" class="form-label">Option 1</label>
                                            <input type="text" class="form-control" name="questions[0][options][]">
                                        </div>
                                        <div class="mb-3 option-item">
                                            <label for="option2" class="form-label">Option 2</label>
                                            <input type="text" class="form-control" name="questions[0][options][]">
                                        </div>
                                    </div>

                                    <!-- Answer input for single questions -->
                                    {{-- <div class="mb-3 answer" style="display:none">
                                        <label for="answer" class="form-label">Answer</label>
                                        <input type="text" class="form-control" placeholder="Answer" name="questions[0][answer]">
                                    </div> --}}

                                    <hr> <!-- Separator between questions -->
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
            let questionIndex = 1;

            // Function to add more questions
            $('.add-question').click(function() {
                let newQuestionBlock = `
                    <div class="question-block">
                        <div class="textbox">
                            <h6>Text</h6>
                        </div>

                        <div class="mb-3">
                            <label for="question-type" class="form-label">Question Type</label>
                            <select class="form-select selctQuestion" name="questions[${questionIndex}][type]" aria-label="Choose A Question Type">
                                <option selected>Choose A Question Type</option>
                                <option value="option">MCQ</option>
                                <option value="single">Single</option>
                            </select>
                        </div>

                        <input type="hidden" name="questions[${questionIndex}][bot_id]" value="{{ $id }}">

                        <div class="mb-3">
                            <label for="question-input" class="form-label">Question</label>
                            <input type="text" class="form-control" placeholder="Enter Question?" name="questions[${questionIndex}][question]">
                        </div>

                        <!-- Option fields for MCQ -->
                        <div class="optionClass" style="display:none">
                            <div class="mb-3 option-item">
                                <label for="option1" class="form-label">Option 1</label>
                                <input type="text" class="form-control" name="questions[${questionIndex}][options][]">
                            </div>
                            <div class="mb-3 option-item">
                                <label for="option2" class="form-label">Option 2</label>
                                <input type="text" class="form-control" name="questions[${questionIndex}][options][]">
                            </div>
                        </div>

                        <!-- Answer input for single questions -->
                        <div class="mb-3 answer" style="display:none">
                            <label for="answer" class="form-label">Answer</label>
                            
                        </div>

                        <hr> <!-- Separator between questions -->
                    </div>
                `;
                $('#questions-container').append(newQuestionBlock);
                questionIndex++;
            });

            // Handle change of question type dynamically for each block
            $(document).on('change', '.selctQuestion', function() {
                var val = $(this).val();
                var parentBlock = $(this).closest('.question-block');
                if (val === 'option') {
                    parentBlock.find('.optionClass').show();
                    parentBlock.find('.answer').hide();
                } else {
                    parentBlock.find('.optionClass').hide();
                    parentBlock.find('.answer').show();
                }
            });
        });
    </script>
@endsection
