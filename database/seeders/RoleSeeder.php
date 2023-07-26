<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role')->insert([
            'id'=> 1,
            'title' => 'Super Admin',
            'slug' => 'super_admin',
            'is_parent' => 1,
            'created_by'=> 1,
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);

       /* DB::table('role')->insert([
            'id'=> 2,
            'title' => 'Admin',
            'slug' => 'admin',
            'is_parent' => 1,
            'created_by'=> 1,
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);*/

        DB::table('role')->insert([
            'id'=> 2,
            'title' => 'Client',
            'slug' => 'client',
            'is_parent' => 1,
            'created_by'=> 1,
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);
    }
}
