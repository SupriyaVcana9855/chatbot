@extends('layout.app')

@section('content')

<style>
    .question-block,
    .option-block,
    .sub-question-block {
        margin: 10px 0;
        padding: 10px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
    }

    button {
        margin-left: 10px;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/setup.css') }}">
<div class="boxpadding">
    <div class="accordion-body">
        <div class="row">
            <div class="col-xl-12">
                <div class="boxinner">
                    <h1>Please add questions in sequence.</h1>
                    <div id="questions-container">
                        <form method="post">
                            @csrf
                            <!-- Section to add the first question -->
                            <div id="first-question-section">
                                <label>First Question:</label>
                                <input type="text" id="first-question-input" name="question" placeholder="Enter the first question">
                                <button type="button" onclick="addFirstQuestion()">Add First Question</button>
                            </div>

                            <!-- Tree container for dynamically adding questions and options -->
                            <div id="tree-container"></div>

                            <!-- Template for sub-question (hidden by default) -->
                            <div id="sub-question-template">
                          
                            </div>

                            <button type="submit" class="btn-info">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('java_scripts')
<script>
    // Add the first question into the tree
    function addFirstQuestion() {
        let firstQuestionInput = document.getElementById('first-question-input').value;

        if (!firstQuestionInput) {
            alert('Please enter a question!');
            return;
        }

        let questionBlock = document.createElement('div');
        questionBlock.className = 'question-block';
        questionBlock.innerHTML = `
        <label>${firstQuestionInput}</label>
        <button type="button" onclick="addOption(this)">Add Option</button>
    `;

        document.getElementById('tree-container').appendChild(questionBlock);
        document.getElementById('first-question-section').style.display = 'none'; // Hide first question input after adding
    }

    // Add a new question with option button into the tree
    function addQuestion() {
        let questionBlock = document.createElement('div');
        questionBlock.className = 'question-block';
        questionBlock.innerHTML = `
        <label>New Question:</label>
        <input type="text" data-value="" name="question" placeholder="Enter question">
        <button type="button" onclick="addOption(this)">Add Option</button>
        <button type="button" onclick="removeQuestion(this)">Remove Question</button>
    `;
        document.getElementById('tree-container').appendChild(questionBlock);
    }

    // Add an option to a specific question block
    function addOption(button) {
        let questionBlock = button.parentElement;

        // Create a new option block element
        let optionBlock = document.createElement('div');
        optionBlock.classList.add('option-block');

        // Set the inner HTML of the new option block
        optionBlock.innerHTML = `
            <input type="text" data-value="" class="option-block-data" name="option" placeholder="Enter option text">
            <button type="button" onclick="addSubQuestion(this)">Add Sub-question</button>
            <button type="button" onclick="removeOption(this)">Remove Option</button>
        `;

        // Append the new option block to the question block
        questionBlock.appendChild(optionBlock);
    }

    function addSubQuestion(button) {
        // Get the parent element of the button clicked (the option block)
        let optionBlock = button.parentElement;
        let existingSubQuestion = optionBlock.querySelector('.sub-question-block');
        const chat_bot_id = new URLSearchParams(window.location.search).get('id');

        // Prevent adding multiple sub-questions to the same option block
        if (existingSubQuestion) {
            alert('You can only add one sub-question per option.');
            return;
        }

        // Fetch the first question input value
        let firstQuestionInput = document.getElementById('first-question-input').value;

        // Collect all values from elements with the class 'option-block-data'
        let optionBlockDataArray = [];
        document.querySelectorAll('.option-block-data').forEach(function(input) {
            optionBlockDataArray.push(input.value);
        });

        console.log(optionBlockDataArray);

        // Check if any option is empty
        let hasEmptyOption = optionBlockDataArray.some(function(option) {
            return option.trim() === ""; // Check if the option is an empty string
        });

        if (hasEmptyOption) {
            alert('Please fill all the options before adding a sub-question!');
            return; // Stop execution if any option is empty
        }

        if (!firstQuestionInput) {
            alert('Please enter a question!');
            return;
        }

        // Initialize FormData
        var formData = new FormData();

        // Set up CSRF token for Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Append the first question and all options (looped)
        formData.append('question', firstQuestionInput);
        formData.append('chat_bot_id', chat_bot_id);
        optionBlockDataArray.forEach(function(option, index) {
            formData.append('option[' + index + ']', option);  // Treat each option as part of an array
        });

        // AJAX request
        $.ajax({
            url: "{{ route('addQuestion') }}",  // Replace with your correct route
            type: "POST",
            cache: false,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var getData = response.data;

                $.each(getData.option_ids, function(key, val) {
                    console.log('okkkkk', val);
                    alert($(optionBlock).find('option-block-data'));
                    var $inputElement = $(optionBlock).find(`input[data-value=""]`); // Main option input
                    var $inputElementOption = $(optionBlock).find('.sub-question-block input[data-option=""]'); 
                    if ($inputElement.length) {
                        $inputElement.first().attr('data-value', val); 
                    }
                    if ($inputElementOption.length) {
                        $inputElementOption.first().attr('data-option', val);
                    }
                });
                alert('Sub-question added successfully!');
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText); // Log detailed error response
                alert('An error occurred while adding the question: ' + xhr.responseText);
            }
        });
        // Clone and append the sub-question block template
        let subQuestionTemplate = document.getElementById('sub-question-template').innerHTML;
        let subQuestionBlock = document.createElement('div');
        subQuestionBlock.classList.add('sub-question-block'); // Add a class to the sub-question block
         // Set the inner HTML of the new option block
         subQuestionBlock.innerHTML = `
            <label>Sub-question:</label>
                <input type="text" data-option="" name="sub-question" placeholder="Enter sub-question">
                <button type="button" onclick="addOption(this)">Add Option</button>
                <button type="button" onclick="removeSubQuestion(this)">Remove Sub-question</button>
        `;
        optionBlock.appendChild(subQuestionBlock);
    }

    // Remove an option
    function removeOption(button) {
        button.parentElement.remove();
    }

    // Remove a question
    function removeQuestion(button) {
        button.parentElement.remove();
    }

    // Remove a sub-question
    function removeSubQuestion(button) {
        button.parentElement.remove();
    }

    function collectFormData() {
        let formData = new FormData();
        let question = document.getElementById('first-question-input').value;
        if (!question) {
            alert('First question is required');
            return false; // Stop form submission if the question is missing
        }
        formData.append('question', question);

        let options = [];
        let optionBlocks = document.querySelectorAll('.option-block');

        // Validate options and sub-questions
        for (let optionBlock of optionBlocks) {
            let optionText = optionBlock.querySelector('input[name="option"]').value;

            // Validate the option text
            if (!optionText) {
                alert('Option text is required');
                return false; // Stop form submission if an option is missing
            }

            let subQuestions = [];
            let subQuestionBlocks = optionBlock.querySelectorAll('.sub-question-block');

            // Check if sub-questions are available and validate them
            for (let subQuestionBlock of subQuestionBlocks) {
                let subQuestionText = subQuestionBlock.querySelector('input[name="sub-question"]').value;

                // Validate the sub-question text
                if (!subQuestionText) {
                    alert('Sub-question text is required');
                    return false; // Stop form submission if a sub-question is missing
                }
                subQuestions.push({
                    sub_question: subQuestionText
                });
            }

            options.push({
                option: optionText,
                sub_questions: subQuestions
            });
        }

        // Check if at least one option is provided
        if (options.length === 0) {
            alert('At least one option is required');
            return false;
        }

        formData.append('options', JSON.stringify(options));

        return formData;
    }

    document.querySelector('form').onsubmit = function(event) {
        event.preventDefault(); // Prevent form from submitting traditionally

        let formData = collectFormData();

        if (!formData) {
            return; // Stop form submission if validation failed
        }

        // Proceed with form submission if validation passed
        fetch("{{ route('addQuestion') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Questions saved successfully!');
                    // Optionally, clear the form or redirect
                } else {
                    alert('There was an error saving the questions.');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('An error occurred while saving the questions.');
            });
    }
</script>
@endsection