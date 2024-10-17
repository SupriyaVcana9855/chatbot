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
        // Retrieve all bot questions with their related options, child questions, and trigger options
        // where the chat_bot_id matches the provided $id, and order them by ID in descending order.
        $newQuestions = BotQuestion::with('options', 'childQuestions', 'triggerOption')
            ->where('chat_bot_id', $id)
            ->orderBy('id', 'DESC')
            ->get();
        
        // Return the 'questionList' view with the retrieved questions.
        return view('question.questionList', compact('newQuestions'));
    }
    
    public function addNewQuestion($id = null)
    {
        // Retrieve all option_id values from the BotQuestion table,
        // and filter out any null or 0 values from the result.
        $optionIds = BotQuestion::pluck('option_id')
            ->filter(function ($value) {
                return !is_null($value) && $value != 0;  // Filter out null and 0
            })->toArray();
    
        // Fetch all QuestionOption records where the bot_question_id matches $id
        // and where the id is not present in the previously filtered optionIds array.
        $newQuestions = QuestionOption::where('bot_question_id', $id)
            ->whereNotIn('id', $optionIds)
            ->get();
    
        // Return the 'question' view with the retrieved newQuestions and id.
        return view('question.question', compact('newQuestions', 'id'));
    }
    
    public function editQuestion($id = null)
    {
        // Retrieve the bot question with its related options where the id matches the provided $id.
        $editQuestions = BotQuestion::with('options')
            ->where('id', $id)
            ->get();
    
        // Return the 'edit_question' view with the retrieved editQuestions.
        return view('question.edit_question', compact('editQuestions'));
    }
    

    public function editSaveQuestion(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'question' => 'required|string',
            'option' => 'required|array',
            'option.*' => 'string', // Validate each option
        ]);

        // Fetch the existing bot question
        $botQuestion = BotQuestion::findOrFail($request->question_id);
        $botQuestion->chat_bot_id = $request->chat_bot_id;
        $botQuestion->question = $request->question;
        $botQuestion->options = $request->option; // Assuming 'options' can store as JSON
        $botQuestion->save();

        $questionOptions = QuestionOption::where('bot_question_id', $botQuestion->id)->get();

        // Iterate over existing question options and update them
        foreach ($questionOptions as $key => $questionOption) {
            // Check if the current index exists in the request options
            if (isset($validatedData['option'][$key])) {
                // Update the option if it exists
                $questionOption->option = $validatedData['option'][$key]; // Update with the corresponding request option
                $questionOption->save();
            }
        }

        // Prepare the data for redirection
        $data = [
            'chat_bot_id' => $request->chat_bot_id,
        ];

        // Redirect to the appropriate route with a success message
        return redirect()->route('addOptionQuestion', $data['chat_bot_id'])
            ->with('success', 'Question updated successfully.');
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
