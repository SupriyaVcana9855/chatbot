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
         return $this->belongsTo(ChatBot::class,foreignKey: 'chat_bot_id');
    }
    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'bot_question_id');
    }
    public function questionFlow()
    {
        return $this->hasMany(BotQuestionFlow::class);
    }

    public function questionAnswers()
    {
        return $this->hasMany(QuestionAnswer::class,'bot_question_id');
    }

    public function triggerOption()
    {
        return $this->belongsTo(QuestionOption::class, 'option_id');
    }
    public function triggerParent()
    {
        return $this->belongsTo(QuestionOption::class, 'parent_id');
    }

    public function childQuestions()
    {
        return $this->hasMany(BotQuestion::class, 'parent_id');
    }
}
