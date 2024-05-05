<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Field;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'user_id' => 1,
                'title' => 'Home Sections',
                'icon' => 'fa fa-home',
                'as_page' => false
            ]
        ]);


        $homeCategory = Category::where('title', 'Home Sections')->first();
        $fieldRecords = [
            'Home Sections' => [
                [
                    'user_id' => 1,
                    'category_id' => $homeCategory->id,
                    'required' => true,
                    'label' => 'Başlık',
                    'handler' => 'title'
                ],
                [
                    'user_id' => 1,
                    'category_id' => $homeCategory->id,
                    'required' => true,
                    'label' => 'Dosya',
                    'handler' => 'view'
                ],
            ]
        ];

        foreach ($fieldRecords['Home Sections'] as $record) {
            $homeCategory->fields()->save(Field::create($record));
        }
    }
}
