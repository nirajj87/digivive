<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdsManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adsManagement = [
            ['title' => 'Test', 'slug' => 'test','additional_settings'=>'{"color": "red", "value": "#f00"}','order'=>1, 'status' =>1],
        ];

        foreach ($adsManagement as $row) {
            DB::table('ads_management')->insert([
                'title'         => $row['title'],
                'slug'          => $row['slug'],
                'additional_settings' => $row['additional_settings'],
                'order'          => $row['order'],
                'status'         => $row['status'],
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ]);
        }
    }
}
