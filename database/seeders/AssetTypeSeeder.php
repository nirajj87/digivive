<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assetTypes = [
            ['title' => 'VOD', 'slug' => 'vod', 'status' => '1'],
            ['title' => 'MOVIE', 'slug' => 'movie', 'status' => '1'],
            ['title' => 'TVSHOW', 'slug' => 'tvshow', 'status' => '1'],
            ['title' => 'AUDIO', 'slug' => 'audio', 'status' => '1'],
            ['title' => 'PODCAST', 'slug' => 'podcast', 'status' => '1'],
            ['title' => 'LIVE', 'slug' => 'live', 'status' => '1']
        ];

        foreach ($assetTypes as $row) {
            DB::table('asset_types')->insert([
                'title'     => $row['title'],
                'slug'      => $row['slug'],
                'status'    => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
