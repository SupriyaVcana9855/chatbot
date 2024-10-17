@extends('layout.app')
@section('content')

<link rel="stylesheet" href="{{ asset('css/setup.css') }}">
<div class="boxpadding">
    <div class="accordion-body">
        <div class="row">
            <div class="col-xl-12">
                <div class="boxinner">
                    <h1>Edit Question with Options</h1>
                    <form id="questionForm" action="{{route('editSaveQuestion')}}" method="post">
                    <input type="te" id="question_id" name="question_id" value="{{$editQuestions[0]->id ?? ''}}">
                    <input type="xc" id="chat_bot_id" name="chat_bot_id" value="{{$editQuestions[0]->chat_bot_id ?? ''}}">
                        @csrf
                        <div class="row">
                            <div>
                                <label for="question">Question:</label>
                                <div class="col-md-12" style="display: flex; align-items: center;">
                                    <input type="text" class="form-control" id="question" value="{{$editQuestions[0]->question}}" name="question" placeholder="Enter your question" required>
                                </div>
                            </div>
                        </div>
                        @foreach ($editQuestions[0]->options as $option)
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">option</label>
                                <input type="text" name="option[]" value="{{$option}}">
                            </div>
                        </div>
                        @endforeach

                        <div id="optionContainer" class="mt-4">
                            <a href="{{route('addOptionQuestion',$editQuestions[0]->chat_bot_id)}}"><button type="button" class="btn btn-secondary">Back</button></a>
                            <button type="submit" class="btn btn-primary">Edit</button>
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
    //
</script>
@endsection