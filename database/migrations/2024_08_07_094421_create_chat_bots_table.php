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
        Schema::create('chat_bots', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('intro_message')->nullable();
            $table->string('main_color')->nullable();
            $table->string('bubble_background')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('font')->nullable();
            $table->string('font_size')->nullable();
            $table->string('bot_position')->nullable();
            $table->string('message_bubble')->nullable();
            $table->string('radius')->nullable();
            $table->string('text_alignment')->nullable();
            $table->string('question_color')->nullable();
            $table->string('answer_color')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('chat_bots');
    }
};
