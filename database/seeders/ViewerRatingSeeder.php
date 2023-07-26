<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ViewerRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $viewerRating = [
            ['title' => 'U', 'slug' => 'u', 'view_order'=>0,'status' => '1'],
            ['title' => 'U/A 7+', 'slug' => 'u_a_7+', 'view_order' => 0,'status' => '1'],
            ['title' => 'U/A 13+', 'slug' => 'u_a_13+', 'view_order' => 0,'status' => '1'],
            ['title' => 'U/A 16+', 'slug' => 'u_a_16+', 'view_order' => 0,'status' => '1'],
            ['title' => 'A', 'slug' => 'a', 'view_order' => 0,'status' => '1']
        ];

        foreach ($viewerRating as $row) {
            DB::table('viewer_ratings')->insert([
                'title' => $row['title'],
                'slug' => $row['slug'],
                'view_order' => $row['view_order'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
