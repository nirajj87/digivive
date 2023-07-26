<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class S3SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $s3Settings = [
            ['title' => 'Transcoding', 'slug' => 'transcoding', 'additional_settings' => '{"color": "red", "value": "#f00"}', 'status' => 1],
            ['title' => 'Images', 'slug' => 'images', 'additional_settings' => '{"color": "red", "value": "#f00"}', 'status' => 1],
            ['title' => 'Logs', 'slug' => 'logs', 'additional_settings' => '{"color": "red", "value": "#f00"}', 'status' => 1]
        ];

        foreach ($s3Settings as $row) {
            DB::table('s3_settings')->insert([
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
