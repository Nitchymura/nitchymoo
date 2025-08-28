<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FAQ;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What is your favorite food?',
                'answer' => 'Ramen, Tonkatsu, Soba, and off course Beer!',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ]
        ];
        Faq::insert($faqs);
    }
}
