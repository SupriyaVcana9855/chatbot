<?php
namespace App\Helpers;
use App\Models\QuestionAnswer;
use App\Models\BotUser;
use App\Models\NewQuestion;
use App\Models\QuestionOption;
use App\Models\BotQuestion;
use Illuminate\Http\Request;
use App\Models\LastResponse;


class Helper
{


    public static function getData($message, $bot,$request=null,$botUserData=null,$question=null)
    {
        if ($message == 'schedule a meeting') 
        {
            $text =   '<a target="_blank" href="https://calendly.com/anshul_seo/30min?month=2024-09">click here to schedule a meeting</a>';
        } else if ($bot->type != 'lead' && $message == 'chat with live agent') 
        {
            $text =  "Let me check if any agent is available for you....please wait.";
        } elseif ($message == 'exit') 
        {
            $text =  "Thanx for the information we will contact you soon.......";
        } else 
        {
          return $data = [];
        }
        $data = LastResponse::where('bot_user_id',$request->bot_user_id)->first();
        if(!$data){
            $lastresponse = new LastResponse();
            $lastresponse->bot_user_id = $request->bot_user_id;
            $lastresponse->question = $message;
            $lastresponse->answer = $text;
            $lastresponse->save();
        }
        // self::saveLatResponse($question,$botUserData,$request,$bot,$message);
        $data = [
            'message' => $text,
            'question_id' => 0,
            'chat_bot_type' => $bot->type,
            'bot_user_id' =>$request->bot_user_id
        ];
        return $data;
    }

    public static function validatedata($question,$message)
    {
        $coloum ='';
        
        if ($question->answer_type == 'email') {
            if (!preg_match('/^[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/', $message)) {
                $data = [
                    'message' => "Enter a valid email!",
                    'question_id' => $question->id
                ];
                return $data;
            }
            $coloum = 'email';
        } else if ($question->answer_type == 'contact') {
            if (!preg_match('/^\+?[0-9]{10,15}$/', $message)) {
                $data = [
                    'message' => "Enter a valid phone number!",
                    'question_id' => $question->id
                ];
                return $data;
            }
            $coloum = 'contact';
        } else if ($question->answer_type == 'name') {
            if (!preg_match('/^[\p{L} ]+$/u', $message)) {

                $data = [
                    'message' => "Enter a valid name!",
                    'question_id' => $question->id
                ];
                return $data;
            }
            $coloum = 'name';
        } else {
            if (!preg_match('/./', $message)) { // Simple check for non-empty string
                $data = [
                    'message' => "Enter a valid data!",
                    'question_id' => $question->id
                ];
                return $data;
            }
        }
        return $coloum;
    }


    public static function saveQuestionAnser($question,$botUserData,$request,$bot,$message)
    {
        //make diffrent common function
        $saveanswer = new QuestionAnswer;
        $saveanswer->bot_question_id = ($question) ? $question->id : '0';
        $saveanswer->answer = $message;
        $saveanswer->user_id = 1; //chat bot ka malik 
        $saveanswer->chat_bot_id = $bot->id;
        $saveanswer->status = '1';
        $saveanswer->bot_user_id = ($botUserData)?$botUserData->id:$request->bot_user_id; // kon chat krne aaya
        $saveanswer->save();
    }


    public static function generateReply($message, $bot, $question, $request)
    {
        $coloum = '';
        if ($question) {

           $returndata =  self::validateData($question,$message);
           if(is_array($returndata))
           {
                return $returndata;
           }
        }
        $botUserData = BotUser::find($request->bot_user_id);
        if($request->bot_id !='0')
        {
            if (!$botUserData) {
                $botUserData = new BotUser;
                $botUserData->chat_bot_id = $bot->id;
                $botUserData->$returndata = $message;
                $botUserData->save();
            } else {
                // if ($returndata != '') {
                    $botUserData->$returndata = $message;
                    $botUserData->save();
                // }
            }
        }

        $saveanswer = self::saveQuestionAnser($question,$botUserData,$request,$bot,$message);

        

        $questionsIds = QuestionAnswer::where('chat_bot_id', $bot->id)
            ->where('status', '1')
            ->where('bot_question_id', '!=', '0')
            ->pluck('bot_question_id')
            ->toArray();
        $questionsIds = array_unique($questionsIds);
        if ($bot->type == 'lead') {
            if($request->option_id != '')
            {
                $questions = BotQuestion::where(function ($query) use ($bot, $request) {
                    // Match chat_bot_id with the specific bot id or global (0)
                    $query->where('chat_bot_id', $bot->id)
                        ->orWhere('chat_bot_id', 0);
                        })
                ->whereNotIn('id', $questionsIds)
                ->where('option_id',$request->option_id) 
                ->first();
            }else{
                $questions = BotQuestion::where(function ($query) use ($bot,$request) {
                    $query->where('chat_bot_id', $bot->id)
                        ->orWhere('chat_bot_id', 0);
                })
                ->whereNotIn('id', $questionsIds)
                ->first();
            }
        } else {
            $data = BotQuestion::where(function ($query) use ($bot) {
                $query->Where('chat_bot_id', 0);
            })
            ->whereNotIn('id', $questionsIds)
            ->first();
            if ($data) {
                $length = 0;
                $questions = $data;
            } else {
                $length = 1;
                $questions = BotQuestion::where(function ($query) use ($bot) {
                    $query->where('chat_bot_id', $bot->id);
                })
                ->whereNotIn('id', $questionsIds)
                ->get();
            }
        }
        $getAllOptions = '';
        if ($questions) {
            $arr = [];
            if ($bot->type == 'lead') {
                $questionNew = $questions->question;
                $getAllOptions = QuestionOption::where('bot_question_id', $questions->id)
                ->pluck('id');
                $optionNew = ($questions->options) ? $questions->options : null;
                $questionId = $questions->id;
            } else {

                if ($length > 0) {

                    if($message == 'schedule a meeting' || $message =='chat with live agent' || $message == 'exit')
                    {

                        return self::getData($message, $bot,$request,$botUserData,$question);
                    }
                   else {

                        $botAnswer = BotQuestion::where('question', 'LIKE', '%' . $message . '%')->first();
                        $questionNew = ($botAnswer) ? $botAnswer->answer : '' . '<br><br>Please select to know more about our website.....';


                        foreach ($questions as $ques) {
                            //will add the question only

                            $arr[] = $ques->question;
                            $questionId[] = $ques->id;
                        }
                        $optionNew = $arr;
                        if (!count($optionNew)) {
                            if($botAnswer)
                            {
                                if($botAnswer->answer)
                                {
                                    $optionNew = array('Please select to know more about our website.....','schedule a meeting', 'chat with live agent', 'exit');
                                }else
                                {
                                    $optionNew = array('schedule a meeting', 'chat with live agent', 'exit');
                                }
                            }else
                            {
                                $optionNew = array('schedule a meeting', 'chat with live agent', 'exit');
                            }
                           
                        }
                    }
                } else {


                    $questionNew = $questions->question;
                    $optionNew = ($questions->options) ? $questions->options : null;
                    $questionId = $questions->id;
                }
            }
            // dd($getAllOptions);

            $data = [
                'message' => $questionNew,
                'question_id' => ($questions->count() > 0) ? $questionId : '',
                'bot_user_id' => ($botUserData)?$botUserData->id:'',
                'chat_bot_type' => $bot->type,
                'options' =>  $optionNew,
                'questions' => $questions,
                'question_option_ids'=> ($getAllOptions)?$getAllOptions:$questionId,//add ids here for the otions we have
            ];
        } else {

            if($message == 'schedule a meeting' || $message =='chat with live agent' || $message == 'exit')
            {
                return self:: getData($message, $bot,$request,$botUserData,$question);
            }
           else {
                $optionNew = array('schedule a meeting', 'exit');
                $data = [
                    'message' => "Please Select from following to know more about us...",
                    'question_id' => 0,
                    'chat_bot_type' => $bot->type,
                    'options' =>  $optionNew,
                    'bot_user_id'=>$botUserData->id
    
                ];

                
                return $data;
            }
        }
        return $data;
    }


}