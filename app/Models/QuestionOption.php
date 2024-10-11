<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'bot_question_id',
        'option_text'
    ];

    // Define relationships
    public function question()
    {
        return $this->belongsTo(BotQuestion::class, 'bot_question_id');
    }
    public function subquestion()
    {
        return $this->belongsTo(BotQuestion::class, 'id');
    }

}
