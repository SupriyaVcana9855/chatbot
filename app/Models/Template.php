<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'main_color', 'bubble_background', 'font', 'font_size', 'button_type', 'radius', 'text_alignment', 'question_color', 'answer_color', 'created_at', 'updated_at', 'chat_bots_id'];
}
