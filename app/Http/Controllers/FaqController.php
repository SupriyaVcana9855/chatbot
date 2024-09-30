<?php

namespace App\Http\Controllers;

use App\Models\BotQuestion;
use App\Models\BotQuestionFlow;
use App\Models\ChatBot;
use Illuminate\Http\Request;

class FaqController extends Controller
{
   
    public function singleBotFaqListing($id)
    {
        $bots=BotQuestion::where('chat_bot_id',$id)->get();
        $sequence = BotQuestion::select('sequence')->where('chat_bot_id',$id)->get();

        $questionFlowIds = BotQuestionFlow::pluck('bot_question_id2')->toArray();
        $questionsNotInFlow = BotQuestion::where('chat_bot_id', $id)
            ->whereNotIn('id', $questionFlowIds)
            ->get();

        return view('bots.bot-faq-listing',compact('bots','id','questionsNotInFlow'));
    } public function faq($chat_bot_id = null, $questions_id = null)
    {
        // dd($chat_bot_id, $questions_id); // Debugging to check the parameters
        $chatBot = ChatBot::find($chat_bot_id);
        $botQuestions = BotQuestion::find($questions_id);
        // dd($chatBot);
        return view('bots.faq', compact('chatBot','botQuestions'));
    }
    

    public function deleteFaq($id)
    {
        $deleteFaq = BotQuestion::find($id);
        $deleteFaq->delete();
        if($deleteFaq){
            return redirect()->back()->with('success', 'FAQs deleted successfully.');
        }else{
            return redirect()->back()->with('error', 'Somthing went wrong.');
        }
    }

    public function addFaq(Request $request)
{
    // Initialize an array to hold formatted questions
    $questions = [];

    // Ensure both questions and answers are provided
    if (!isset($request->questions) || !isset($request->answer) ||
        !is_array($request->questions) || !is_array($request->answer)) {
        return redirect()->back()->with('error', 'Invalid data provided.');
    }

    // Iterate over the questions and answers
    foreach ($request->questions as $index => $question) {
        // Check if there's a corresponding answer
        if (!isset($request->answer[$index])) {
            continue; // Skip if no corresponding answer
        }

        // Prepare the question data with question_id
        $questions[] = [
            'question_id' => $request->question_id[$index] ?? null, // Get question_id if it exists
            'bot_id' => $request->bot_id,
            'question' => $question,
            'answer' => $request->answer[$index]
        ];
    }

    // Process each question and update if question_id exists, otherwise create a new entry
    foreach ($questions as $questionData) {
        if ($questionData['question_id']) {
            // Update existing question if question_id is present
            $botQuestion = BotQuestion::find($questionData['question_id']);
            if ($botQuestion) {
                // Update the found record
                $botQuestion->chat_bot_id = $questionData['bot_id'];
                $botQuestion->question = $questionData['question'];
                $botQuestion->answer = $questionData['answer'];
                $botQuestion->save();
            } else {
                // Handle the case where the question_id is provided but the record is not found
                return redirect()->back()->with('error', 'FAQ not found for the provided question ID.');
            }
        } else {
            // Create a new question if no question_id is provided
            $botQuestion = new BotQuestion();
            $botQuestion->chat_bot_id = $questionData['bot_id'];
            $botQuestion->question_type = 'FAQ'; // Adjust based on your type field
            $botQuestion->question = $questionData['question'];
            $botQuestion->answer = $questionData['answer'];
            $botQuestion->save();
        }
    }

    if (isset($botQuestion) && $botQuestion) {
        return redirect()->route('singleBotFaqListing', $botQuestion->chat_bot_id)->with('success', 'FAQs saved successfully.');
    } else {
        return redirect()->back()->with('error', 'Something went wrong.');
    }
}

    // public function addFaq(Request $request)
    // {
    //     // Dump the incoming request data for debugging
    //     dd($request->all());
    
    //     // Initialize an array to hold formatted questions
    //     $questions = [];
    
    //     // Ensure both questions and answers are provided
    //     if (!isset($request->questions) || !isset($request->answer) ||
    //         !is_array($request->questions) || !is_array($request->answer)) {
    //         return redirect()->back()->with('error', 'Invalid data provided.');
    //     }
    
    //     // Iterate over the questions and answers
    //     foreach ($request->questions as $index => $question) {
    //         // Check if there's a corresponding answer
    //         if (!isset($request->answer[$index])) {
    //             continue; // Skip if no corresponding answer
    //         }
    
    //         // Prepare the question data
    //         $questions[] = [
    //             'bot_id' => $request->bot_id,
    //             'question' => $question,
    //             'answer' => $request->answer[$index] // Associate the answer
    //         ];
    //     }
    
    //     // Process each question and store in the database
    //     foreach ($questions as $questionData) {
    //         $botQuestion = new BotQuestion();
    //         $botQuestion->chat_bot_id = $questionData['bot_id'];
    //         $botQuestion->question_type = 'FAQ'; // Adjust based on your type field
    //         $botQuestion->question = $questionData['question'];
    //         $botQuestion->answer = $questionData['answer']; // Store answer
    //         $botQuestion->save();
    //     }

    //     if($botQuestion){
    //         return redirect()->route('singleBotFaqListing',$botQuestion->chat_bot_id)->with('success', 'FAQs saved successfully.');
    //     }else{
    //         return redirect()->back()->with('error', 'Somthing went wrong.');
    //     }
    // }
    
}
