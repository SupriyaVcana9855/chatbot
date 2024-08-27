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

        Schema::table('chat_bots', function (Blueprint $table) {
            $table->string('header_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('option_color')->nullable();
            $table->string('option_border_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_bots', function (Blueprint $table) {
            $table->dropColumn([
                'header_color', 
                'background_color', 
                'option_color', 
                'option_border_color'
            ]);
        });
    }
};
