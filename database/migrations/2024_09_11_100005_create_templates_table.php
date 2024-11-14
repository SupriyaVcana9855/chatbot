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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('intro_message')->nullable();
            $table->string('width')->nullable();
            $table->string('main_color')->nullable();
            $table->string('bubble_background')->nullable();
            $table->string('logo')->nullable();
            $table->string('description')->nullable();
            $table->string('font')->nullable();
            $table->string('font_size')->nullable();
            $table->string('bot_position')->nullable();
            $table->string('message_bubble')->nullable();
            $table->string('radius')->nullable();
            $table->string('text_alignment')->nullable();
            $table->string('question_color', 100)->nullable();
            $table->string('answer_color', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('header_color', 100)->nullable();
            $table->string('background_color', 100)->nullable();
            $table->string('option_color', 100)->nullable();
            $table->string('option_border_color', 100)->nullable();
            $table->string('button_design', 100)->nullable();
            $table->string('button_color', 100)->nullable();
            $table->string('button_text_color', 100)->nullable();
            $table->string('temp_title')->nullable();
            $table->text('temp_description')->nullable();
            $table->string('temp_img')->nullable();
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
        Schema::dropIfExists('templates');
    }
};
