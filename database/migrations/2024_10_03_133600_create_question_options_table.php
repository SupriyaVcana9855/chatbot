<form id="leadGenerationForm">
    <div id="questionsContainer"></div>
    <button type="button" id="addQuestion">Add Question</button>
    <button type="submit">Save</button>
</form>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#addQuestion').click(function () {
        const questionHtml = `
            <div class="question">
                <input type="text" name="question[]" placeholder="Enter question" required>
                <button type="button" class="addOption">Add Option</button>
                <div class="optionsContainer"></div>
            </div>`;
        $('#questionsContainer').append(questionHtml);
    });

    $(document).on('click', '.addOption', function () {
        const optionHtml = `
            <div class="option">
                <input type="text" name="option[]" placeholder="Enter option" required>
                <button type="button" class="addSubQuestion">Add Sub-question</button>
                <div class="subquestionsContainer"></div>
            </div>`;
        $(this).siblings('.optionsContainer').append(optionHtml);
    });

    $(document).on('click', '.addSubQuestion', function () {
        const subQuestionHtml = `
            <div class="subquestion">
                <input type="text" name="subquestion[]" placeholder="Enter sub-question" required>
                <input type="text" name="suboption[]" placeholder="Enter option for sub-question" required>
            </div>`;
        $(this).siblings('.subquestionsContainer').append(subQuestionHtml);
    });

    $('#leadGenerationForm').submit(function (e) {
        e.preventDefault();
        // Handle form submission (e.g., AJAX request to save data)
        const formData = $(this).serializeArray();
        console.log(formData); // For debugging
        // TODO: Make an AJAX request to save the form data to your database
    });
});
</script>
