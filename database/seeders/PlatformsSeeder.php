<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plateforms = [
            ['title' => 'Android', 'slug' => 'android', 'status' => '1'],
            ['title' => 'iOS', 'slug' => 'ios', 'status' => '1'],
            ['title' => 'Web', 'slug' => 'web', 'status' => '1'],
            ['title' => 'Fire Tv', 'slug' => 'fire_tv', 'status' => '1'],
            ['title' => 'Android Tv', 'slug' => 'android_tv', 'status' => '1'],
            ['title' => 'Roky Tv', 'slug' => 'roky_tv', 'status' => '1'],
            ['title' => 'Apple Tv', 'slug' => 'apple_tv', 'status' => '1']
        ];

        foreach ($plateforms as $row) {
            DB::table('platforms')->insert([
                'title' => $row['title'],
                'slug' => $row['slug'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
