<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastResponse extends Model
{
    use HasFactory;
    public function botUser()
    {
         return $this->belongsTo(BotUser::class,'bot_user_id');
    }
}
