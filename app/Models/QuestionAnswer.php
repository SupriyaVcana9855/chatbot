<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;
    protected $fillable = ['status'];

    public function botUser()
    {
         return $this->belongsTo(BotUser::class,'bot_user_id');
    }

}
