<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AwsStorageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $awsstorage = [
            ['storage_type' => 'aws', 'key' => 'awskey', 'secret' => 'awssecret', 'region' => 'IND', 'default_acl' => 'default_acl', 'bucket' => 'digivive', 'row' => 'rowfile', 'content' => 'contentfile', 'backup' => 'backupfile', 'user_id' => '1', 'cdn_url' => 'cdn_urlurl', 'status' => '1'],

        ];

        foreach ($awsstorage as $row) {
            DB::table('aws_storage_settings')->insert([
                'storage_type' => $row['storage_type'],
                'key' => $row['key'],
                'secret' => $row['secret'],
                'region' => $row['region'],
                'default_acl' => $row['default_acl'],
                'bucket' => $row['bucket'],
                'row' => $row['row'],
                'content' => $row['content'],
                'backup' => $row['backup'],
                'user_id' => $row['user_id'],
                'cdn_url' => $row['cdn_url'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}