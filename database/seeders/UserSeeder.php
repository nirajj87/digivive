<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id'             => 1,
                'first_name'     => 'Digivive super Admin',
                'last_name'     => 'super Admin',
                'email'          => 'superadmin@digivive.com',
                'password'       => Hash::make('Digivive@123#'),
                'country_id'     => 101,
                'timezone'       => 'Asia/Kolkata',
                'date_format'    => 'd-F-Y',
                'dob'            =>'01-12-1987',
                'phone_number'   =>'8855885566',
                'status'         => '1',
                'role_id'        => 1,
                'permission'     => null,
                'is_parent'      => NULL,
                'created_by'     => NULL,
                'updated_by'     => NULL
            ],
            /*[
                'id'            => 2,
                'first_name'    => 'Digivive Admin',
                'last_name'     => 'Admin',
                'email'         => 'admin@digivive.com',
                'password'      => Hash::make('Digivive@123#'),
                'country_id'    => 101,
                'timezone'      => 'Asia/Kolkata',
                'date_format'   => 'd-F-Y',
                'dob'           => '01-12-1987',
                'phone_number'  => '8855885566',
                'status'        => '1',
                'role_id'       => 2,
                'permission'    => $this->AdminPermission(),
                'is_parent'     => NULL,
                'created_by'    => NULL,
                'updated_by'    => NULL
            ],*/
            [
                'id'            => 2,
                'first_name'    => 'Client',
                'last_name'     => 'Samatva',
                'email'         => 'client@digivive.com',
                'password'      => Hash::make('Digivive@123#'),
                'country_id'    => 101,
                'timezone'      => 'Asia/Kolkata',
                'date_format'   => 'd-F-Y',
                'dob'           => '01-12-1987',
                'phone_number'  => '8855885566',
                'status'        => '1',
                'role_id'       => 2,
                'permission'    => $this->AdminPermission(),
                'is_parent'     => NULL,
                'created_by'    => NULL,
                'updated_by'    => NULL
            ],
        ];

        foreach ($users as $key => $value) {
            User::create($value);
        }
    }

    # set permission for admin user
    public function AdminPermission()
    {
        $objectData = '[{"dashboard": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"profile_settings": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"discovery": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"affiliate_offers": {"is_add": 0, "is_edit": 0, "is_view": 0, "is_delete": 0}}, {"influencers_offers": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"notification_centre": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"user_management": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"finance": {"is_add": 0, "is_edit": 0, "is_view": 0, "is_delete": 0}}, {"masters": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"static_pages": {"is_add": 0, "is_edit": 0, "is_view": 0, "is_delete": 0}}, {"advanced_report": {"is_add": 0, "is_edit": 0, "is_view": 0, "is_delete": 0}}, {"basic_details": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"billing_details": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"organization_details": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"manager": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"postback": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"company": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"notifications": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"permissions": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"store_details": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"security": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"search_influencers": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"my_list": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"explore_offers": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"my_offers": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"archive_offers": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"global_postback": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"postback_test": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"inf_explore_offers": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"inf_my_offers": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"inf_archive_offers": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"manage_communication": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"manage_admin_users": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"manage_clients": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"client_users": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"manage_advertisers": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"manage_merchant": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"manage_affiliates": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"affiliate_users": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"manage_influencers": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"manage_request": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"payout": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"invoices": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"payments": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"country": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"state": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"city": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"timezone": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"role_management": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"module_management": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"platform": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"content_type": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"category": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"solution": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"designation": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"email_smtp_settings": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"email_template": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"whatsapp_settings": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"influencer_offer_report": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"affiliate_offer_report": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"influencer_report": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"affiliate_report": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"advertiser_report": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"payout_report": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"revenue_report": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"office_locations": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"release_order": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"protocals": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"verticals": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"channels": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"operating_systems": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"tracking_domains": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"pixel_type": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"conversion_pixels": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"promotional_method": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"traffic_sources": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}, {"business_models": {"is_add": 1, "is_edit": 1, "is_view": 1, "is_delete": 1}}]';
        return $objectData;
    }
}
