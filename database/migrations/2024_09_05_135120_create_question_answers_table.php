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
        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            
            // Correct table name for the foreign key
            $table->integer('bot_question_id')->nullable(); 
            // $table->foreign('bot_question_id')->references('id')->on('bot_questions')->onDelete('cascade');
            
            $table->text('answer')->nullable(); 
            $table->string('status')->nullable(); 

            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
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
        Schema::dropIfExists('question_answers');
    }
};
