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

                    <h1>Please add question sequence wise.</h1>


                    <div id="questions-container">
                        <form action="{{route('addQuestion')}}" method="post">
                            @csrf
                        <!-- Section to add the first question -->
                        <div id="first-question-section">
                            <label>First Question:</label>
                            <input type="text" id="first-question-input" name="question" placeholder="Enter the first question">
                            <button type="button" onclick="addFirstQuestion()">Add First Question</button>
                        </div>

                        <!-- Tree container for the questions -->
                        <div id="tree-container">
                            <!-- The tree will be appended here -->
                        </div>

                        <!-- Option template (hidden) -->
                        <div id="option-template" style="display: none;">
                            <div class="option-block">
                                <input type="text" name="option" placeholder="Enter option text">
                                <button type="button" onclick="addSubQuestion(this)">Add Sub-question</button>
                                <button type="button" onclick="removeOption(this)">Remove Option</button>
                            </div>
                        </div>

                        <!-- Template for sub-question -->
                        <div id="sub-question-template" style="display: none;">
                            <div class="sub-question-block">
                                <label>Sub-question:</label>
                                <input type="text" name="sub-question" placeholder="Enter sub-question">
                                <button type="button" onclick="addOption(this)">Add Option</button>
                                <button type="button" onclick="removeSubQuestion(this)">Remove Sub-question</button>
                            </div>
                        </div>
                        <button type="submit" class="btn-info">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
        <input type="text" name="question" placeholder="Enter question">
        <button type="button" onclick="addOption(this)">Add Option</button>
        <button type="button" onclick="removeQuestion(this)">Remove Question</button>
    `;
        document.getElementById('tree-container').appendChild(questionBlock);
    }

    // Add an option to a specific question block
    function addOption(button) {
        let questionBlock = button.parentElement;
        let optionTemplate = document.getElementById('option-template').innerHTML;
        let optionBlock = document.createElement('div');
        optionBlock.innerHTML = optionTemplate;
        questionBlock.appendChild(optionBlock);
    }

// Add a sub-question to an option (only if one doesn't already exist)
function addSubQuestion(button) {
    let optionBlock = button.parentElement;
    let existingSubQuestion = optionBlock.querySelector('.sub-question-block');

    if (existingSubQuestion) {
        alert('You can only add one sub-question per option.');
        return;
    }

    let subQuestionTemplate = document.getElementById('sub-question-template').innerHTML;
    let subQuestionBlock = document.createElement('div');
    subQuestionBlock.innerHTML = subQuestionTemplate;
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

    // Validate the first question
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
        console.log(optionText);
        // Validate the option text
        // if (!optionText) {
        //     alert('Option text is required');
        //     return false; // Stop form submission if an option is missing
        // }

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
            subQuestions.push({ sub_question: subQuestionText });
        }

        

        options.push({ option: optionText, sub_questions: subQuestions });
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
    event.preventDefault();  // Prevent form from submitting traditionally

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
            alert('There was an error saving the questions');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during form submission.');
    });
};



</script>
@endsection