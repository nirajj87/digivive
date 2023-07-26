<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupAndPackageManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupAndPackageManagement = [
            ['title' => 'Test', 'slug' => 'test','additional_settings' => '{"color": "red", "value": "#f00"}', 'order' => 0, 'status' => 1]
        ];

        foreach ($groupAndPackageManagement as $row) {
            DB::table('group_and_package_management')->insert([
                'title'     => $row['title'],
                'slug'      => $row['slug'],
                'additional_settings' => $row['additional_settings'],
                'order'     => $row['order'],
                'status'    => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
