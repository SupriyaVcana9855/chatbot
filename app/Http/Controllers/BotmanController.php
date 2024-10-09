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

    // public function handle()
    // {
    //     $config = [];
    
    //     DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
    
    //     $botman = BotManFactory::create($config);
    
    //     $botman->hears('hello', function (BotMan $bot) {
    //         $bot->reply('Hello!');
    //     });
    
    //     $botman->fallback(function (BotMan $bot) {
    //         $userMessage = $bot->getMessage()->getText();
    
    //         // Notify customer service
    //         $this->notifyCustomerService($userMessage);
    
    //         // Reply to the user
    //         $bot->reply("I'm not sure how to respond to that. Please wait while I connect you to a customer service representative.");
    //     });
    
    //     $botman->listen();
    // }
    

    // protected function notifyCustomerService($message)
    // {
    //     $twilioSid = env('TWILIO_SID');
    //     $twilioAuthToken = env('TWILIO_AUTH_TOKEN');
    //     $twilioFrom = env('TWILIO_WHATSAPP_FROM');
    //     $twilioTo = env('TWILIO_WHATSAPP_TO');

    //     $client = new Client($twilioSid, $twilioAuthToken);

    //     $client->messages->create(
    //         $twilioTo,
    //         [
    //             'from' => $twilioFrom,
    //             'body' => "User sent the following message: $message"
    //         ]
    //     );
    // }

    // public function saveTree(Request $request)
    // {
    //     dd($request);
        
    //     $data = $request->input('questions');

    //     foreach ($data as $questionData) {
    //         // Step 1: Save the question in NewQuestion
    //         $question = new NewQuestion();
    //         $question->question = $questionData['question_text'];
    //         $question->parent_id = null; // Since it's the main question, no parent
    //         $question->save();
    
    //         // Step 2: Save the options related to the question
    //         foreach ($questionData['options'] as $optionData) {
    //             // Save the option in QuestionOption
    //             $option = new QuestionOption();
    //             $option->question_id = $question->id; // Link the option to the question
    //             $option->option = $optionData['option_text'];
    //             $option->save();
    
    //             // Step 3: Save sub-question if it exists
    //             if (!empty($optionData['sub_question'])) {
    //                 $subQuestion = new NewQuestion();
    //                 $subQuestion->question = $optionData['sub_question'];
    //                 $subQuestion->parent_id = $question->id; // Link sub-question to parent question
    //                 $subQuestion->option_id = $option->id; // Link the sub-question to the related option
    //                 $subQuestion->save();
    //             }
    //         }
    //     }
    
    //     return response()->json(['status' => 'success']);
    //     // $questionTree = $request->input('questions');
    
    //     // foreach ($questionTree as $questionData) {
    //     //     // Save the main question
    //     //     $question = \App\Models\NewQuestion::create([
    //     //         'chat_bot_id' => $questionData['chat_bot_id'] ?? null, // Optional chatbot ID
    //     //         'question' => $questionData['question'], // Main question text
    //     //         'parent_id' => $questionData['parent_id'] ?? null, // If no parent, set null
    //     //         'option_id' => null // This will be filled in for sub-questions
    //     //     ]);
    
    //     //     // Save the options related to the question
    //     //     if (!empty($questionData['options'])) {
    //     //         foreach ($questionData['options'] as $optionData) {
    //     //             // Save each option
    //     //             $option = \App\Models\QuestionOption::create([
    //     //                 'question_id' => $question->id, // Link option to the newly created question
    //     //                 'option' => $optionData['option']
    //     //             ]);
    
    //     //             // If the option has a sub-question, save the sub-question and link it to this option
    //     //             if (isset($optionData['sub_question'])) {
    //     //                 $subQuestionData = $optionData['sub_question'];
    //     //                 $subQuestion = \App\Models\NewQuestion::create([
    //     //                     'chat_bot_id' => $questionData['chat_bot_id'] ?? null,
    //     //                     'question' => $subQuestionData['question'], // Sub-question text
    //     //                     'parent_id' => $question->id, // Link sub-question to the parent question
    //     //                     'option_id' => $option->id // Link sub-question to the option that triggers it
    //     //                 ]);
    //     //             }
    //     //         }
    //     //     }
    //     // }
    
    //     // return response()->json(['success' => true, 'message' => 'Tree data saved successfully']);
    // }
    

    public function saveTree(Request $request)
    {
        dd($request);
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
