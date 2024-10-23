<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;
    protected $fillable = ['status'];

    public function users()
    {
         return $this->belongsTo(User::class,'bot_user_id');
    }
    public function botUser()
    {
         return $this->belongsTo(BotUser::class,'bot_user_id');
    }

    public function botQuestion()
    {
         return $this->belongsTo(BotQuestion::class,'bot_question_id');
    }
    public function chatBots()
    {
         return $this->belongsTo(ChatBot::class,'chat_bot_id');
    }
    
//     public function lastResponse()
//     {
//          return $this->hasMany(LastResponse::class,'bot_user_id');
//     }

}
