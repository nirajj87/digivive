<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnalyticsManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $analyticsManagement = [
            ['title' => 'Test', 'slug' => 'test', 'tracking_id' => 'test001', 'additional_settings' => '{"color": "red", "value": "#f00"}', 'status' => '1']
        ];

        foreach ($analyticsManagement as $row) {
            DB::table('analytics_management')->insert([
                'title'     => $row['title'],
                'slug'      => $row['slug'],
                'tracking_id' => $row['tracking_id'],
                'additional_settings' => $row['additional_settings'],
                'status'        => $row['status'],
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
        }
    }
}
