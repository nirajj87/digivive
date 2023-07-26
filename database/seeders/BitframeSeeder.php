<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BitframeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bitframes = [
            ['type' => 'Video', 'presets' => '144/360/480/720/1080/audio', 'is_downloadable' => '1', 'download_type' => 'HD', 'status' => '1'],
            ['type' => 'Video', 'presets' => '144/360/480/720/1080/audio', 'is_downloadable' => '0', 'download_type' => 'SD', 'status' => '1'],
            ['type' => 'Video', 'presets' => '144/360/480/720/1080/audio', 'is_downloadable' => '1', 'download_type' => 'HQ', 'status' => '1'],

            ['type' => 'Audio', 'presets' => '144/360/480/720/1080/audio', 'is_downloadable' => '1', 'download_type' => 'HD', 'status' => '1'],
            ['type' => 'Audio', 'presets' => '144/360/480/720/1080/audio', 'is_downloadable' => '0', 'download_type' => 'SD', 'status' => '1'],
            ['type' => 'Audio', 'presets' => '144/360/480/720/1080/audio', 'is_downloadable' => '1', 'download_type' => 'HQ', 'status' => '1'],

        ];

        foreach ($bitframes as $row) {
            DB::table('bitframes')->insert([
                'type' => $row['type'],
                'presets' => $row['presets'],
                'is_downloadable' => $row['is_downloadable'],
                'download_type' => $row['download_type'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}