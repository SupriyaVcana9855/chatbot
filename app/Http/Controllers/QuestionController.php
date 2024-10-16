<?php

namespace App\Http\Controllers;

use App\Models\BotQuestion;
use App\Models\NewQuestion;
use App\Models\QuestionOption;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function addOptionQuestion($id)
    {
        $newQuestions = BotQuestion::with('options')->where('chat_bot_id',$id)->get();
        return view('question.questionList',compact('newQuestions'));
    }
    // public function addNewQuestion($id =null)
    // {
    
    //     $newQuestions = QuestionOption::with('options')->where('id',$id)->get();
    //     dd( $newQuestions);
    //     return view('question.question',compact('newQuestions'));
    // }
    public function addNewQuestion($id = null)
    {
      
            // Get all option_id values from the BotQuestion tabl
            
            
             
            $optionIds = BotQuestion::pluck('option_id')->filter(function($value) {
                return !is_null($value) && $value != 0;  // Filter out null and 0
            })->toArray();
                    // Use these option IDs to filter out options in the QuestionOption table
        $newQuestions = QuestionOption::where('bot_question_id', $id)
            ->whereNotIn('id', $optionIds)
            ->get();

        return view('question.question', compact('newQuestions'));
    }
    
    
    public function saveOptionQuestion(Request $request)
    {
   
    $options = $request->options; // No need to check if it's an array, it already is

    // Create a new bot question
    $botQuestion = new BotQuestion();
    $botQuestion->chat_bot_id = $request->chat_bot_id;
    $botQuestion->question_type = 'question'; // Adjust this based on your use case
    $botQuestion->question = $request->question;
    $botQuestion->options = $options; // Store the options as JSON or another appropriate format
    $botQuestion->option_id = ($request->option_id) ? $request->option_id : 0;
    $botQuestion->parent_id = ($request->parent_id) ? $request->parent_id : 0;
    $botQuestion->save();

    // Handle question options
    $optionIds = [];

    foreach ($options as $option) {
        $questionoption = new QuestionOption();
        $questionoption->option = $option;
        $questionoption->bot_question_id = $botQuestion->id;
        $questionoption->save();
        $optionIds[] = $questionoption->id;
    }

   

    // Prepare the data for redirection
    $data = [
        'parent_id' => $botQuestion->id,
        'option_ids' => $optionIds,
        'chat_bot_id' => $request->chat_bot_id,
    ];

    // Redirect to the appropriate route with a success message
    return redirect()->route('addOptionQuestion', $data['chat_bot_id'])->with('success', 'Question added successfully.');
}


}
