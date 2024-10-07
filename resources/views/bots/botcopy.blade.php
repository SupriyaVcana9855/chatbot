<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Question Form</title>
    <style>
        .question, .option, .sub-question {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<form id="questionForm">
    <div id="questionsContainer">
        <div class="question">
            <label>Question:</label>
            <input type="text" name="question_text[]" placeholder="Enter your question">
            <button type="button" class="add-option">Add Option</button>
            <div class="optionsContainer"></div>
        </div>
    </div>
    <button type="button" id="add-question">Add Question</button>
    <button type="submit">Submit</button>
</form>

<script>
document.getElementById('add-question').addEventListener('click', function() {
    const questionsContainer = document.getElementById('questionsContainer');
    
    const newQuestionDiv = document.createElement('div');
    newQuestionDiv.className = 'question';
    
    newQuestionDiv.innerHTML = `
        <label>Question:</label>
        <input type="text" name="question_text[]" placeholder="Enter your question">
        <button type="button" class="add-option">Add Option</button>
        <div class="optionsContainer"></div>
    `;
    
    questionsContainer.appendChild(newQuestionDiv);
    
    // Attach event listener to new option button
    newQuestionDiv.querySelector('.add-option').addEventListener('click', function() {
        addOption(newQuestionDiv.querySelector('.optionsContainer'));
    });
});

// Function to add an option with sub-questions
function addOption(optionsContainer) {
    const newOptionDiv = document.createElement('div');
    newOptionDiv.className = 'option';

    newOptionDiv.innerHTML = `
        <label>Option:</label>
        <input type="text" name="option_text[]" placeholder="Enter your option">
        <button type="button" class="add-sub-question">Add Sub-question</button>
        <div class="subQuestionsContainer"></div>
    `;
    
    optionsContainer.appendChild(newOptionDiv);

    // Attach event listener to new sub-question button
    newOptionDiv.querySelector('.add-sub-question').addEventListener('click', function() {
        addSubQuestion(newOptionDiv.querySelector('.subQuestionsContainer'));
    });
}

// Function to add a sub-question with sub-options
function addSubQuestion(subQuestionsContainer) {
    const newSubQuestionDiv = document.createElement('div');
    newSubQuestionDiv.className = 'sub-question';

    newSubQuestionDiv.innerHTML = `
        <label>Sub-question:</label>
        <input type="text" name="sub_question_text[]" placeholder="Enter your sub-question">
        <button type="button" class="add-sub-option">Add Sub-option</button>
        <div class="subOptionsContainer"></div>
    `;
    
    subQuestionsContainer.appendChild(newSubQuestionDiv);

    // Attach event listener to new sub-option button
    newSubQuestionDiv.querySelector('.add-sub-option').addEventListener('click', function() {
        addSubOption(newSubQuestionDiv.querySelector('.subOptionsContainer'));
    });
}

// Function to add a sub-option
function addSubOption(subOptionsContainer) {
    const newSubOptionDiv = document.createElement('div');

    newSubOptionDiv.innerHTML = `
        <label>Sub-option:</label>
        <input type="text" name="sub_options[]" placeholder="Enter your sub-option">
    `;
    
    subOptionsContainer.appendChild(newSubOptionDiv);
}

// Handle form submission
document.getElementById('questionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Here you can gather the form data and process it
    const formData = new FormData(this);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        if (!data[key]) {
            data[key] = [];
        }
        data[key].push(value);
    }
    
    console.log(JSON.stringify(data)); // This is where you can handle the data
});
</script>

</body>
</html>
