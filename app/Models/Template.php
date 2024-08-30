<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    public function chatbots(){
        return $this->belongsTo(ChatBot::class,'chat_bots_id','id');
    }
}
