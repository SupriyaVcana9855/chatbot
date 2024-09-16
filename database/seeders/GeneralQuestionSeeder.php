<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the table to remove existing data
        // DB::table('bot_questions')->truncate();

        // Insert multiple rows in a single query
        DB::table('bot_questions')->insert([
            [
                'chat_bot_id' => 1,
                'question' => 'What is your name?',
                'options' => json_encode(value: ['name' => 'name', 'key2' => 'value2']),
                'answer' => '',
                'question_type' => 'General'
            ],
            [
                'chat_bot_id' => 1,
                'question' => 'What is your email ID?',
               'options' => json_encode(['email' => 'admin@gmail.com', 'key2' => 'value2']),
                'answer' => '',
                'question_type' => 'General'
            ],
            [
                'chat_bot_id' => 1,
                'question' => 'What is your contact number?',
                'options' => json_encode(['contact' => '987654322', 'key2' => 'value2']), // Convert array to JSON string
                'answer' => '',
                'question_type' => 'General'
            ]
        ]);
    }
}
