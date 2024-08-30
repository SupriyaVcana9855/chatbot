<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Template::create([
            'name' => 'Digital Marketing',
            'chat_bots_id' => '1',
            'main_color' => '#0054F5', 
            'bubble_background' => '#f5f5f5', 
            'font' => 'Arial, sans-serif', 
            'font_size' => '12px', 
            'button_type' => 'border-radius: 12px;', 
            'radius' => '50%', 
            'text_alignment' => 'center', 
            'question_color' => '#fff', 
            'answer_color'  => '#aaa',
        ]);
    }
}
