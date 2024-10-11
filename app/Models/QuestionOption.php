<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'option_text'
    ];

    // Define relationships
    public function question()
    {
        return $this->belongsTo(NewQuestion::class, 'question_id');
    }
    public function subquestion()
    {
        return $this->belongsTo(NewQuestion::class, 'id');
    }

}
