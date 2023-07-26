<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentModesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentModes = [
            ['title' => 'PayTm', 'slug' => 'paytm', 'type'=>'Online', 'is_recurring' => 1, 'image' => '','additional_settings' => '{"color": "red", "value": "#f00"}', 'status' => 1]
        ];

        foreach ($paymentModes as $row) {
            DB::table('payment_modes')->insert([
                'title'     => $row['title'],
                'slug'      => $row['slug'],
                'type'      => $row['type'],
                'is_recurring'  => $row['is_recurring'],
                'image'         => $row['image'],
                'additional_settings' => $row['additional_settings'],
                'status'        => $row['status'],
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
        }
    }
}
