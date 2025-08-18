<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //can use save(), create(), createMany(), insert()

        $categories = [
            [
                'name' => 'Travel',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],[
                'name' => 'Figure Skate',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],[
                'name' => 'Beer',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],[
                'name' => 'Animal',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],[
                'name' => 'Art',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],[
                'name' => 'Others',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ]
        ];
        Category::insert($categories);
        //$this->category->insert($categories)
    }
}
