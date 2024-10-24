<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Tree Structure for Chatbot</title>
    <style>
        .question-block, .option-block, .sub-question-block {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        button {
            margin-left: 10px;
        }
    </style>
</head>
<body>

<!-- Section to add the first question -->
<div id="first-question-section">
    <label>First Question:</label>
    <input type="text" id="first-question-input" placeholder="Enter the first question">
    <button onclick="addFirstQuestion()">Add First Question</button>
</div>

<!-- Tree container for the questions -->
<div id="tree-container">
    <!-- The tree will be appended here -->
</div>

<!-- Option template (hidden) -->
<div id="option-template" style="display: none;">
    <div class="option-block">
        <input type="text" placeholder="Enter option text">
        <button onclick="addSubQuestion(this)">Add Sub-question</button>
        <button onclick="removeOption(this)">Remove Option</button>
    </div>
</div>

<!-- Template for sub-question -->
<div id="sub-question-template" style="display: none;">
    <div class="sub-question-block">
        <label>Sub-question:</label>
        <input type="text" placeholder="Enter sub-question">
        <button onclick="addOption(this)">Add Option</button>
        <button onclick="removeSubQuestion(this)">Remove Sub-question</button>
    </div>
</div>

<button onclick="submitData()">Save Data</button>

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
        <input type="text" value="${firstQuestionInput}" style="display:none">
        <button onclick="addOption(this)">Add Option</button>
        <button onclick="removeQuestion(this)">Remove Question</button>
    `;
    
    document.getElementById('tree-container').appendChild(questionBlock);
    document.getElementById('first-question-section').style.display = 'none'; // Hide first question input after adding
}

// Add an option to a specific question block
function addOption(button) {
    let questionBlock = button.parentElement;
    let optionTemplate = document.getElementById('option-template').innerHTML;
    let optionBlock = document.createElement('div');
    optionBlock.innerHTML = optionTemplate;
    questionBlock.appendChild(optionBlock);
}

// Add a sub-question to an option
function addSubQuestion(button) {
    let optionBlock = button.parentElement;
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


function submitData() {
    let treeContainer = document.getElementById('tree-container');
    let questionBlocks = treeContainer.querySelectorAll('.question-block');
    let data = [];

    questionBlocks.forEach(function (questionBlock) {
        let questionText = questionBlock.querySelector('label').textContent;
        console.log(questionText);
        let questionObj = {
            question_text: questionText,
            options: []
        };
        console.log('questionObj',questionObj);
        let optionBlocks = questionBlock.querySelectorAll('.option-block');
        optionBlocks.forEach(function (optionBlock) {
            let optionText = optionBlock.querySelector('input[type="text"]').value;
            let optionObj = {
                option_text: optionText,
                sub_questions: []
            };
        console.log('optionText',optionText);

            // Look for sub-question blocks within this option block
            let subQuestionBlocks = optionBlock.querySelectorAll('.sub-question-block');
            subQuestionBlocks.forEach(function (subQuestionBlock) {
                let subQuestionText = subQuestionBlock.querySelector('input[type="text"]').value;
                let subQuestionObj = {
                    question_text: subQuestionText,
                    options: []
                    
                };

                // Look for options within this sub-question block
                let subOptionBlocks = subQuestionBlock.querySelectorAll('.sub-option-block');
                subOptionBlocks.forEach(function (subOptionBlock) {
                    let subOptionText = subOptionBlock.querySelector('input[type="text"]').value;
                    subQuestionObj.options.push({
                        option_text: subOptionText
                    });
                });

                // Add the sub-question object to the option's sub_questions array
                optionObj.sub_questions.push(subQuestionObj);
            });

            // Add the option object to the question's options array
            questionObj.options.push(optionObj);
        });

        // Add the question object to the main data array
        data.push(questionObj);
    });

    console.log(data);
    return data;
}

</script>

</body>
</html>
