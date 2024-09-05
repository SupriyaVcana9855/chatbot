@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/setup.css') }}">

    <div class="boxpadding">
        <div class="accordion-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="boxinner">
                        <form action="{{ route('addQuestion') }}" method="post">
                            @csrf
                            <div class="textbox">
                                <h6>Text</h6>
                                <div class="imgboxhead">
                                    <button type="submit" class="btn btn-primary" style="width: 86px;">Save</button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="question-type" class="form-label">Question Type</label>
                                <select class="form-select selctQuestion" id="question-type" name="type" aria-label="Choose A Question Type">
                                    <option selected>Choose A Question Type</option>
                                    <option value="option">Mcq</option>
                                    <option value="single">Single</option>
                                </select>
                            </div>
                             {{-- <div class="mb-3">
                                <label for="question-type" class="form-label">Answer Type</label>
                                <select class="form-select selctQuestion" id="question-type" name="answerType" aria-label="Choose A Question Type">
                                    <option selected>Choose A Answer Type</option>
                                    <option value="email">Email</option>
                                    <option value="string">String</option>
                                    <option value="number">Number</option>
                                </select>
                            </div> --}}
                            <input type="hidden" name="bot_id" value="{{ $id }}">
                            <div class="mb-3">
                                <label for="question-input" class="form-label">Question</label>
                                <input type="text" class="form-control " id="question-input" placeholder="Enter Question?" aria-label="Enter Question?" name="question">
                            </div>
                            <div class="optionClass" style="display:none">
                                <div class="mb-3">
                                    <label for="welcome-text" class="form-label">Option1</label>
                                    <input type="text" class="form-control option2" id="welcome-text" placeholder="Welcome Text" aria-label="Welcome Text" name="option1">
                                </div>

                                <div class="mb-3">
                                    <label for="bot-description" class="form-label">Option2</label>
                                    <input type="text" class="form-control option1" id="bot-description" placeholder="Bot Description" aria-label="Bot Description" name="option2">
                                
                                </div>
                            </div>
                           <div class="mb-3 answer">
                                    <label for="bot-description" class="form-label">Answer</label>
                                    <input type="text" class="form-control " id="bot-description" placeholder="Bot Description" aria-label="Bot Description" name="answer">
                                
                                </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.selctQuestion').on('change',function(){
            var val = $(this).val();
            if(val == 'option')
            {
                $('.optionClass').css('display','block');
                $('.answer').css('display','none');
            }else
            {
                 $('.optionClass').css('display','none');
                $('.answer').css('display','block');
            }
        });
    </script>
@endsection
