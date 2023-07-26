<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_profile = [
            [
            'user_id' => 1,
            'user_name' =>'Super Admin',
            'designation' => 'Super Admin',
            'company_name' => 'Digivive',
            'company_logo' => '',
            'address' => ["line1" => "Noida", "line2" => "Sector 15", "city_id" => 45482, "state_id" => 4021, "country_id" => 101, "zip_code" => "845469", "landmark" => "Gov. School"],
            'communication_channel' => ["email" => "admin@gmail.com", "phone_number" => "88515416569"],
            'billing' => ["benificiary_name" => "Super Admin", "bank_name" => "ICICI Bank", "account_number" => "8855663322556611", "ifsc_code" => "ICICI0125", "swift_code" => "SWIFT01", "bank_address" => "Sector 12", "cheque" => "cancelled_cheque.png", "payment_gateway_id" => "paytm102456", "gstin" => "gstin", "cin" => "cin", "pan" => "CJFPS5576"],
            'managers' => ["1"],
            'status' => 1,
            ],
           /* [
                'user_id' => 2,
                'user_name' => 'Admin',
                'designation' => 'Admin',
                'company_name' => 'Digivive',
                'company_logo' => '',
                'address' => ["addressline1" => "Noida", "addressline2" => "Sector 22"],
                'communication_channel' => ["email" => "admin@gmail.com", "phone_number" => "88515416569"],
                'billing' => ["beneficiary_name" => "Admin", "bank_name" => "ICICI Bank", "account_number" => "8855663322556611", "ifsc_code" => "ICICI0125", "swift_code" => "SWIFT01", "bank_address" => "Sector 12", "cancelled_cheque" => "cancelled_cheque.png", "payment_gateway_id" => "paytm102456", "gstin" => "gstin", "cin" => "cin", "pan" => "CJFPS5576"],
                'managers' => ["Name" => "Test", "manager_id" => 1, "email" => "admin@gmail.com", "phone_number" => "88515416569"],
                'landmark' => "Near Government School",
                'country_id' => 101,
                'state_id' => 4021,
                'city_id' => 45487,
                'zip_code' => "201301",
                'status' => 1,
            ],*/
            [
                'user_id' => 2,
                'user_name' => 'Client',
                'designation' => 'Client',
                'company_name' => 'Digivive',
                'company_logo' => '',
                'address' => ["line1" => "Noida", "line2" => "Sector 15", "city_id" => 45482, "state_id" => 4021, "country_id" => 101, "zip_code" => "845469", "landmark" => "Gov. School"],
                'communication_channel' => ["email" => "admin@gmail.com", "phone_number" => "88515416569"],
                'billing' => ["benificiary_name" => "Super Admin", "bank_name" => "ICICI Bank", "account_number" => "8855663322556611", "ifsc_code" => "ICICI0125", "swift_code" => "SWIFT01", "bank_address" => "Sector 12", "cheque" => "cancelled_cheque.png", "payment_gateway_id" => "paytm102456", "gstin" => "gstin", "cin" => "cin", "pan" => "CJFPS5576"],
                'managers' => ["1"],
                'status' => 1,
            ],
        ];

        foreach ($user_profile as $row) {
            DB::table('user_profile')->insert([
                'user_id' => $row['user_id'],
                'user_name' => $row['user_name'],
                'designation' => $row['designation'],
                'company_name' => $row['company_name'],
                'company_logo' => $row['company_logo'],
                'address' => json_encode($row['address']),
                'communication_channel' => json_encode($row['communication_channel']),
                'billing' => json_encode($row['billing']),
                'managers' => json_encode($row['managers']),
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}