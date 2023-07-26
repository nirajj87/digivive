<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contentAvailabilities = [
            ['title' => 'Free', 'slug' => 'free', 'status' => '1'],
            ['title' => 'Paid', 'slug' => 'paid', 'status' => '1'],
            ['title' => 'No Tag', 'slug' => 'no_tag', 'status' => '1']
        ];

        foreach ($contentAvailabilities as $row) {
            DB::table('content_availabilities')->insert([
                'title' => $row['title'],
                'slug' => $row['slug'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
