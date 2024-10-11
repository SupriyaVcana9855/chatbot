@extends('layout.app')
@section('content')


<?php



// $chat_bot_id = $_GET['chat_bot_id'];

?>

<link rel="stylesheet" href="{{ asset('css/setup.css') }}">
<div class="boxpadding">
    <div class="accordion-body">
        <div class="row">
            <div class="col-xl-12">
                <div class="boxinner">
                    <h1>Create Question with Options</h1>
                    <form id="questionForm" action="{{route('saveOptionQuestion')}}" method="post">
    @csrf
  
    <input type="hidden" id="parent_id" name="parent_id" value="{{$newQuestions[0]->bot_question_id ?? ''}}">
    <input type="hidden" id="chat_bot_id" name="chat_bot_id" value="{{$bot_id?? ''}}">

    @if ($newQuestions->isNotEmpty())
        <div class="form-group">
            <label for="exampleFormControlSelect1">Select Option:</label>
            <select class="form-control" name="option_id" id="option_id">
                <option selected disabled>Please Select Option</option>
                @foreach ($newQuestions as $option)
                    <option value="{{$option->id}}">{{$option->option}}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="form-group">
        <label for="exampleFormControlInput1">Question:</label>
        <input type="text" class="form-control" id="question" name="question" placeholder="Enter your question" required>
    </div>

    <div class="col-md-12 mt-4">
        <div id="optionContainer"></div>
    </div>

    <div class="col-md-2">
        <button type="button" style="width: 60%;" class="btn btn-success" onclick="addOption()">Add Option</button>
    </div>

    <div id="optionContainer" class="mt-4">
        <a href="{{route('addOptionQuestion',$bot_id)}}"><button type="button" class="btn btn-secondary">Back</button></a>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
</div>




@endsection
@section('java_scripts')
<script>
    function addOption() {
        // Create a new option input field container (div)
        let newOptionDiv = document.createElement('div');
        newOptionDiv.classList.add('option-group');

        // Add inline style for flex display and center alignment
        newOptionDiv.style.display = 'flex';
        newOptionDiv.style.alignItems = 'center';
        newOptionDiv.style.marginBottom = '10px'; // Optional: Add some spacing

        // Create the option input field
        let newOptionInput = document.createElement('input');
        newOptionInput.type = 'text';
        newOptionInput.name = 'options[]';
        newOptionInput.classList.add('col-md-10', 'form-control', 'mt-4');
        newOptionInput.placeholder = 'Enter option';
        newOptionInput.required = true;

        // Create the remove button
        let removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.textContent = 'Remove';
        removeButton.classList.add('btn', 'btn-danger', 'ml-2'); // Bootstrap classes for styling
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
@endsection