<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emailTemplates = [
            ['title' => 'Registration', 'user_id' => 1, 'subject' => 'subject', 'body' => 'body', 
            'variables' => 'variables', 'status' => 1,'puchline' => 'puchline','footer' => 'footer',]
        ];

        foreach ($emailTemplates as $row) {
            DB::table('email_templates')->insert([
                'title' => $row['title'],
                'user_id' => $row['user_id'],
                'subject' => $row['subject'],
                'body' => $row['body'],
                'variables' => $row['variables'],
                'status' => $row['status'],
                'puchline' => $row['puchline'],
                'footer' => $row['footer'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}