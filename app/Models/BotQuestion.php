<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotQuestion extends Model
{
    use HasFactory;
    public function bot()
    {
         return $this->belongsTo(ChatBot::class);
    }

    public function questionFlow()
    {
        return $this->hasMany(BotQuestionFlow::class);
    }
}
