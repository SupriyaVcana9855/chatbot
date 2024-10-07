<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'chat_bot_id',
        'question_text',
        'parent_id',
        'option_id'
    ];

    // Define relationships
    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }

    public function parentQuestion()
    {
        return $this->belongsTo(QuestionOption::class, 'parent_id');
    }

    public function triggerOption()
    {
        return $this->belongsTo(QuestionOption::class, 'option_id');
    }
}
