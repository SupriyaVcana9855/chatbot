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
            $table->string('button_color')->nullable()->after('button_design');
            $table->string('button_text_color')->nullable()->after('button_color');
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
            $table->dropColumn('button_color');
        });
    }
};
