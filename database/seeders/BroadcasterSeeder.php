<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BroadcasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $broadcasters = [
            ['title' => 'ABP News Network Pvt. Ltd', 'slug' => 'abp_news_network_pvt_ltd', 'status' => '1'],
            ['title' => 'ATechnos', 'slug' => 'atechnos', 'status' => '1'],
            ['title' => 'Aakash Aath', 'slug' => 'aakash_aath', 'status' => '1'],
            ['title' => 'Acharya Chandana Ji', 'slug' => 'acharya_chandana_ji', 'status' => '1'],
            ['title' => 'Associated Broadcasting Company Pvt. Ltd.', 'slug' => 'associated_broadcasting_company_pvt_ltd', 'status' => '1'],
            ['title' => 'Brandvalue Communication Limited', 'slug' => 'brandvalue_communication_limited', 'status' => '1'],
            ['title' => 'Catvision', 'slug' => 'catvision', 'status' => '1'],
            ['title' => 'DEN', 'slug' => 'den', 'status' => '1'],
            ['title' => 'Digivive', 'slug' => 'digivive', 'status' => '1'],
            ['title' => 'Digivive PC', 'slug' => 'digivive_pc', 'status' => '1'],
            ['title' => 'Divya Broadcasting Network Private Limited', 'slug' => 'divya_broadcasting_network_private_limited', 'status' => '1'],
            ['title' => 'Dr Didi Ma Pratima', 'slug' => 'dr_didi_ma_pratima', 'status' => '1'],
            ['title' => 'FTA', 'slug' => 'fta', 'status' => '1'],
            ['title' => 'France 24', 'slug' => 'france_24', 'status' => '1'],
            ['title' => 'Horizon', 'slug' => 'horizon', 'status' => '1'],
            ['title' => 'Hare Krsna Content Broadcast Pvt Ltd', 'slug' => 'hare_krsna_content_broadcast_pvt_ltd', 'status' => '1'],
            ['title' => 'Holistic Broadcaster', 'slug' => 'holistic_broadcaster', 'status' => '1'],
            ['title' => 'IN10 Media Pvt Ltd', 'slug' => 'in10_media_pvt_ltd', 'status' => '1'],
            ['title' => 'Khusboo', 'slug' => 'Khusboo', 'status' => '1'],
            ['title' => 'Krishna Showbiz Services Pvt Ltd', 'slug' => 'krishna_showbiz_services_pvt_ltd', 'status' => '1'],
            ['title' => 'Lokdhun', 'slug' => 'lokdhun', 'status' => '1'],
            ['title' => 'Nexgtvwhitelabeled', 'slug' => 'nexgtvwhitelabeled', 'status' => '1'],
            ['title' => 'Platform8', 'slug' => 'platform8', 'status' => '1'],
            ['title' => 'Rajshree Entertainment', 'slug' => 'rajshree_entertainment', 'status' => '1'],
            ['title' => 'Rajshree Entertainment Pvt Ltd', 'slug' => 'rajshree_entertainment_pvt_ltd', 'status' => '1'],
            ['title' => 'Rajshree Music', 'slug' => 'rajshree_music', 'status' => '1'],
            ['title' => 'Sadhvi Samahita', 'slug' => 'sadhvi_samahita', 'status' => '1'],
            ['title' => 'TV Vision Limited', 'slug' => 'tv_vision_limited', 'status' => '1'],
            ['title' => 'Vivek Agnihotri', 'slug' => 'vivek_agnihotri', 'status' => '1'],
            ['title' => 'Youtube', 'slug' => 'youtube', 'status' => '1'],
            ['title' => 'Zonet', 'slug' => 'zonet', 'status' => '1']
        ];

        foreach ($broadcasters as $row) {
            DB::table('broadcasters')->insert([
                'title' => $row['title'],
                'slug' => $row['slug'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
