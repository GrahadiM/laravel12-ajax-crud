<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 250) as $index) {
            Category::create([
                'name' => 'Category ' . $index,
                'slug' => 'category-' . $index,
                'description' => 'Description ' . $index
            ]);
        }

        // Category::create([
        //     'name' => 'Category 1',
        //     'slug' => 'category-1',
        //     'description' => 'Description 1'
        // ]);
    }
}
