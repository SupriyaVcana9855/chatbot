<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBot extends Model
{
    use HasFactory;
    public function botQuestions()
    {
         return $this->hasMany(BotQuestion::class,'chat_bot_id');
    }

    public function templates(){
        return $this->hasMany(Template::class,'chat_bots_id','id');
    }
}
