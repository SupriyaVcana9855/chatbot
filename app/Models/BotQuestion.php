<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotQuestion extends Model
{
    use HasFactory;
    protected $casts = [
        'options' => 'array', // Ensures 'options' is treated as an array
    ];
    public function bot()
    {
         return $this->belongsTo(ChatBot::class,'chat_bot_id');
    }

    public function questionFlow()
    {
        return $this->hasMany(BotQuestionFlow::class);
    }

    public function questionAnswers()
    {
        return $this->hasMany(QuestionAnswer::class,'bot_question_id');
    }
}
