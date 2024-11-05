<?php
namespace App\Http\Controllers;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\BotManFactory;
use Illuminate\Http\Request;
use App\Models\NewQuestion;
use App\Models\QuestionOption;

use Twilio\Rest\Client; 
class BotmanController extends Controller
{
    public function getOptionsData(Request $request)
    {
        $newquestion = NewQuestion::where('chat_bot_id',$request->chat_bot_id)->with('options')->get();
        dd($newquestion);
    }
    public function newQuestions(Request $request)
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

    
    public function saveTree(Request $request)
    {
        $data = $request->input('questions'); // Assuming your JSON input is in this format
        foreach ($data as $question) {
            // Save the main question
            $newQuestion =new NewQuestion();
            $newQuestion->chat_bot_id = 1;
            $newQuestion->question = $question['question_text'];
            $newQuestion->parent_id = null;
            $newQuestion->save();
            
            // dd($newQuestion);

            // Save the options for the main question
            $this->saveOptions($newQuestion->id, $question['options']);
        }
    }

    private function saveOptions($questionId, $options, $parentId = null)
    {
        foreach ($options as $option) {
            // Save the option
            $questionOption =new QuestionOption();
            $questionOption->question_id = $questionId;
              $questionOption->option = $option['option_text'];
              $questionOption->save();

            // Save the parent ID for relationship
            NewQuestion::where('id', $questionId)
                ->update(['option_id' => $questionOption->id, 'parent_id' => $parentId]);

            // If there is a sub-question, save it recursively
            if (isset($option['sub_question'])) {

                $subQuestion =new NewQuestion();
                $subQuestion->chat_bot_id = 1;
                $subQuestion->question = $option['sub_question']['question_text'];
                $subQuestion->parent_id = $questionOption->id;
                $subQuestion->save();
            

                // Save the options for the sub-question
                $this->saveOptions($subQuestion->id, $option['sub_question']['options'], $questionOption->id);
            }
        }
    }
}
