<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotUser extends Model
{
    use HasFactory;
    public function questionAnswer()
    {
         return $this->hasMany(QuestionAnswer::class,'bot_user_id');
    }

}
