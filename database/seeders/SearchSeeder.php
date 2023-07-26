<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $searches = [
            ['title' => 'Aloglia', 'configration' => '{"Qqwerty": "VOD279972", "Language": "English", "Genre": "Adventure", "Category": "Art", "Type": "Action"}', 'status' => '1'],
            ['title' => 'Typesense', 'configration' => '{"Qqwerty": "VOD279972", "Language": "English", "Genre": "Adventure", "Category": "Art", "Type": "Action"}', 'status' => '1']
        ];

        foreach ($searches as $row) {
            DB::table('searches')->insert([
                'title' => $row['title'],
                'configration' => $row['configration'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }  
    }
}
