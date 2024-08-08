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
            $table->string('type')->nullabel();
            $table->string('name')->nullabel();
            $table->string('intro_message')->nullabel();
            $table->string('main_color')->nullabel();
            $table->string('bubble_background')->nullabel();
            $table->string('logo')->nullabel();
            $table->text('description')->nullabel();
            $table->string('font')->nullabel();
            $table->string('font_size')->nullabel();
            $table->string('bot_position')->nullabel();
            $table->string('message_bubble')->nullabel();
            $table->string('radius')->nullabel();
            $table->string('text_alignment')->nullabel();
            $table->string('question_color')->nullabel();
            $table->string('answer_color')->nullabel();
            $table->string('status')->nullabel();
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
