<?php

namespace App\Http\Controllers;
use App\Models\NewQuestion;
use App\Models\QuestionOption;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function addOptionQuestion()
    {
        $newQuestions = NewQuestion::all();
        return view('question.questionList',compact('newQuestions'));
    }
    public function saveOptionQuestion(Request $request)
    {
        $newquestion = new NewQuestion();
        $newquestion->question = $request->question;
        $newquestion->chat_bot_id = $request->chat_bot_id;
        $newquestion->option_id = ($request->option_id)?$request->option_id:0;
        $newquestion->parent_id = ($request->parent_id)?$request->parent_id:0;
        $newquestion->save(); 
        $questionoption = new QuestionOption();
        $optionIds =[];
        $options = explode(',',$request->option);
        foreach($options as $option)
        {
            $questionoption->option = $option; 
            $questionoption->question_id = $newquestion->id;
            $questionoption->save();
            $optionIds[] = $questionoption->id;
        }
        $data = [
            'parent_id' => $newquestion->id,
            'option_ids' =>  $optionIds,
            'chat_bot_id' =>  $request->chat_bot_id,
        ];
        return $data;
    }
}
