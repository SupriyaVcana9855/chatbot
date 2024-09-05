<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotQuestionFlow extends Model
{
    use HasFactory;
    public function botQuestion()
    {
        return $this->belongsTo(BotQuestion::class);
    }
}
