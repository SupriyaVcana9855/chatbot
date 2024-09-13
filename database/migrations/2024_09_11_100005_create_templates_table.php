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
            $table->string('main_color')->nullable();
            $table->string('bubble_background')->nullable();
            $table->string('box_width')->nullable();
            $table->string('font')->nullable();
            $table->string('font_size')->nullable();
            $table->string('button_design')->nullable();
            $table->string('question_radius')->nullable();
            $table->string('answer_radius')->nullable();
            $table->string('text_alignment')->nullable();
            $table->string('text_color')->nullable();
            $table->string('question_color')->nullable();
            $table->string('answer_color')->nullable();
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
