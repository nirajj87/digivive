<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StreamingSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $streamingSettings = [
            ['title' => 'Cloudfront', 'slug' => 'cloudfront', 'additional_settings' => '{"color": "red", "value": "#f00"}','status' => '1'],
            ['title' => 'Wowza', 'slug' => 'wowza', 'additional_settings' => '{"color": "red", "value": "#f00"}','status' => '1']
        ];

        foreach ($streamingSettings as $row) {
            DB::table('streaming_settings')->insert([
                'title'     => $row['title'],
                'slug'      => $row['slug'],
                'additional_settings' => $row['additional_settings'],
                'status'    => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
