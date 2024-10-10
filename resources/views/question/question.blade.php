<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Form</title>
    <style>
        .option-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .option-group input {
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <h1>Create Question with Options</h1>

    <form id="questionForm">
        <div>
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" placeholder="Enter your question" required>
                <button type="button" onclick="addOption()">Add Option</button>
        </div>

        <div id="optionContainer">
            <div class="option-group">
                <input type="text" name="options[]" placeholder="Enter option" required>
            </div>
        </div>

        <br>
        <button type="submit">Submit</button>
    </form>

    <script>
        function addOption() {
            // Create a new option input field
            let newOptionDiv = document.createElement('div');
            newOptionDiv.classList.add('option-group');

            // Create the option input field
            let newOptionInput = document.createElement('input');
            newOptionInput.type = 'text';
            newOptionInput.name = 'options[]';
            newOptionInput.placeholder = 'Enter option';
            newOptionInput.required = true;

            // Create the remove button
            let removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = 'Remove';
            removeButton.onclick = function() {
                this.parentElement.remove();
            };

            // Append the input field and remove button to the div
            newOptionDiv.appendChild(newOptionInput);
            newOptionDiv.appendChild(removeButton);

            // Append the new option div to the container
            document.getElementById('optionContainer').appendChild(newOptionDiv);
        }
    </script>

</body>
</html>
