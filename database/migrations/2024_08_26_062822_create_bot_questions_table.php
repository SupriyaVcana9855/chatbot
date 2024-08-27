<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_bot_id'); 
            $table->foreign('bot_id')->references('id')->on('chat_bots')->onDelete('cascade');
            $table->text('question'); 
            $table->text('option1')->nullable(); 
            $table->text('option2')->nullable(); 
            $table->text('answer')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_questions');
    }
};
