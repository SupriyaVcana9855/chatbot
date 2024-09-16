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
   
           $table->text('question'); 
           $table->json('options')->nullable();
            $table->text('answer')->nullable(); 

            // Changing question_type enum
            $table->enum('question_type', ['General', 'FAQ', 'Question'])
                  ->default('General')
                  ->comment('1 = General, 2 = FAQ, 3 = Question');
            $table->string('sequence')->nullable(); 
            $table->integer('type')->nullable(); 

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
