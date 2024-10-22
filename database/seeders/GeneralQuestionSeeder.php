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
                'chat_bot_id' => 0,
                'question' => 'What is your email ID?',
               'options' => null,
                'answer' => '',
                'question_type' => 'general',
                'answer_type' =>'email'

                
            ],
            [
                'chat_bot_id' => 0,
                'question' => 'What is your name?',
                'options' => null,
                'answer' => '',
                'question_type' => 'general',
                'answer_type' =>'name'
            ],
            [
                'chat_bot_id' => 0,
                'question' => 'What is your contact number?',
                'options' => null, // Convert array to JSON string
                'answer' => '',
                'question_type' => 'general',
                'answer_type' =>'contact'

            ]
        ]);
        
    }
}
