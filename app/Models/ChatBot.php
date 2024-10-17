<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBot extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'type', 'name', 'intro_message', 'main_color', 'bubble_background', 'logo', 'description', 'font', 'font_size', 'bot_position', 'message_bubble', 'radius', 'text_alignment', 'question_color', 'answer_color', 'status', 'created_at', 'updated_at', 'header_color', 'background_color', 'option_color', 'option_border_color', 'button_design', 'button_type'];
    
    public function botQuestions()
    {
         return $this->hasMany(BotQuestion::class,'chat_bot_id');
    }

    public function botUsers()
    {
         return $this->hasMany(BotUser::class,'chat_bot_id');
    }
}
