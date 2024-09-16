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
        Schema::create('bot_question_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_question_id'); 
            $table->foreign('bot_question_id')->references('id')->on('bot_questions')->onDelete('cascade');
            $table->text('answer')->nullable(); 
            $table->unsignedBigInteger('bot_question_id2'); 
            $table->foreign('bot_question_id2')->references('id')->on('bot_questions')->onDelete('cascade');
            
            $table->unsignedBigInteger('chat_bot_id'); 
            $table->foreign('chat_bot_id')->references('id')->on('chat_bots')->onDelete('cascade');
            
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
        Schema::dropIfExists('bot_question_flows');
    }
};
