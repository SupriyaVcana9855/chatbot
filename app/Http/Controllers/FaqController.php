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
    } 
    public function faq($id)
    {
        $chatBot = ChatBot::find($id);
        // dd($chatBot);
        return view('bots.faq',compact('chatBot'));
    }
    public function addFaq(Request $request)
    {
        // Dump the incoming request data for debugging
        // dd($request->all());
    
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
    
            // Prepare the question data
            $questions[] = [
                'bot_id' => $request->bot_id,
                'question' => $question,
                'answer' => $request->answer[$index] // Associate the answer
            ];
        }
    
        // Process each question and store in the database
        foreach ($questions as $questionData) {
            $botQuestion = new BotQuestion();
            $botQuestion->chat_bot_id = $questionData['bot_id'];
            $botQuestion->question_type = 'FAQ'; // Adjust based on your type field
            $botQuestion->question = $questionData['question'];
            $botQuestion->answer = $questionData['answer']; // Store answer
            $botQuestion->save();
        }
    
        return redirect()->back()->with('success', 'FAQs added successfully!');
    }
    
}
