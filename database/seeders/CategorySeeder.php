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
$categories = [
            ['name' => 'Travel', 'color' => '#3B82F6'],
            ['name' => 'Food', 'color' => '#EF4444'],
            ['name' => 'Office', 'color' => '#10B981'],
            ['name' => 'Equipment', 'color' => '#F59E0B'],
            ['name' => 'Marketing', 'color' => '#8B5CF6'],
            ['name' => 'Training', 'color' => '#06B6D4'],
            ['name' => 'Utilities', 'color' => '#84CC16'],
            ['name' => 'Other', 'color' => '#6B7280'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
