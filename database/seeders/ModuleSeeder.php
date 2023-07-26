<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $modules = [

            ['title' => 'Dashboard', 'slug' => 'dashboard', 'parent_id' => 0, 'icon' => 'images/dashboard.png', 'active_icon' => 'images/dashboard_Selected.png', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
            
            ['title' => 'Account Management', 'slug' => 'account-management', 'parent_id' => 0, 'icon' => 'images/accountmanagement.png', 'active_icon' => 'images/accountmanagement_Selected.png', 'order' => '2', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Profile', 'slug' => 'profile', 'parent_id' => 2, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Security', 'slug' => 'security', 'parent_id' => 2, 'icon' => '', 'active_icon' => '', 'order' => '2', 'role_ids' => [1,3], 'status' => '1'],
                ['title' => 'Billing', 'slug' => 'billing', 'parent_id' => 2, 'icon' => '', 'active_icon' => '', 'order' => '3', 'role_ids' => [1,3], 'status' => '1'],
                ['title' => 'Permission', 'slug' => 'permission', 'parent_id' => 2, 'icon' => '', 'active_icon' => '', 'order' => '4', 'role_ids' => [1,3], 'status' => '1'],
           
            ['title' => 'User Management', 'slug' => 'user-management', 'parent_id' => 0, 'icon' => 'images/usermanagement.png', 'active_icon' => 'images/usermanagement_Selected.png', 'order' => '3', 'role_ids' => [1,2, 3], 'status' => '1'],
               ['title' => 'Admin User', 'slug' => 'admin-user', 'parent_id' => 7, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2], 'status' => '1'],
               ['title' => 'Clients', 'slug' => 'clients', 'parent_id' => 7, 'icon' => '', 'active_icon' => '', 'order' => '2', 'role_ids' => [1,2], 'status' => '1'],
               ['title' => 'Clients User', 'slug' => 'clients-user', 'parent_id' => 7, 'icon' => '', 'active_icon' => '', 'order' => '3', 'role_ids' => [1,2, 3], 'status' => '1'],
               ['title' => 'Subscriber', 'slug' => 'subscriber', 'parent_id' => 7, 'icon' => '', 'active_icon' => '', 'order' => '4', 'role_ids' => [1,2, 3], 'status' => '1'],
               ['title' => 'Broadcasters', 'slug' => 'broadcasters', 'parent_id' => 7, 'icon' => '', 'active_icon' => '', 'order' => '5', 'role_ids' => [1,2, 3], 'status' => '1'],
          
            ['title' => 'Video Library', 'slug' => 'video-library', 'parent_id' => 0, 'icon' => 'images/videolibrary.png', 'active_icon' => 'images/videolibrary_Selected.png', 'order' => '4', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Manage Video', 'slug' => 'manage-video', 'parent_id' => 13, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Archives Video', 'slug' => 'archives-video', 'parent_id' => 13, 'icon' => '', 'active_icon' => '', 'order' => '2', 'role_ids' => [1,2, 3], 'status' => '1'],
                
            ['title' => 'Content Management', 'slug' => 'content-management', 'parent_id' => 0, 'icon' => 'images/contentmanagement.png', 'active_icon' => 'images/contentmanagement_Selected.png', 'order' => '5', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Video', 'slug' => 'video', 'parent_id' => 16, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Live', 'slug' => 'live', 'parent_id' => 16, 'icon' => '', 'active_icon' => '', 'order' => '2', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Tv Show', 'slug' => 'tv-show', 'parent_id' => 16, 'icon' => '', 'active_icon' => '', 'order' => '3', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Podcast', 'slug' => 'podcast', 'parent_id' => 16, 'icon' => '', 'active_icon' => '', 'order' => '4', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Audio', 'slug' => 'audio', 'parent_id' => 16, 'icon' => '', 'active_icon' => '', 'order' => '5', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Live Event', 'slug' => 'live-event', 'parent_id' => 16, 'icon' => '', 'active_icon' => '', 'order' => '6', 'role_ids' => [1,2, 3], 'status' => '1'],

            ['title' => 'Finance', 'slug' => 'finance', 'parent_id' => 0, 'icon' => 'images/finance.png', 'active_icon' => 'images/finance_Selected.png', 'order' => '6', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Invoice', 'slug' => 'invoice', 'parent_id' =>23, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Payments', 'slug' => 'payments', 'parent_id' =>23, 'icon' => '', 'active_icon' => '', 'order' => '2', 'role_ids' => [1,2, 3], 'status' => '1'],

            ['title' => 'Reports', 'slug' => 'reports', 'parent_id' => 0, 'icon' => 'images/reports.png', 'active_icon' => 'images/reports_Selected.png', 'order' => '7', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Content Report', 'slug' => 'content-report', 'parent_id' => 26, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Active User Report', 'slug' => 'active-user-report', 'parent_id' => 26, 'icon' => '', 'active_icon' => '', 'order' => '2', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Analytics Report', 'slug' => 'analytics-report', 'parent_id' => 26, 'icon' => '', 'active_icon' => '', 'order' => '3', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Billing Report', 'slug' => 'billing-report', 'parent_id' => 26, 'icon' => '', 'active_icon' => '', 'order' => '4', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Transcoding Report', 'slug' => 'transcoding-report', 'parent_id' => 26, 'icon' => '', 'active_icon' => '', 'order' => '5', 'role_ids' => [1,2, 3], 'status' => '1'],

            ['title' => 'Billing Management', 'slug' => 'billing-management', 'parent_id' => 0, 'icon' => 'images/billing.png', 'active_icon' => 'images/billingmanagement_Selected.png', 'order' => '8', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Group Management', 'slug' => 'group-management', 'parent_id' => 32, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Package Management', 'slug' => 'package-management', 'parent_id' => 32, 'icon' => '', 'active_icon' => '', 'order' => '2', 'role_ids' => [1,2, 3], 'status' => '1'],
               
            ['title' => 'Masters', 'slug' => 'masters', 'parent_id' => 0, 'icon' => 'images/masters.png', 'active_icon' => 'images/masters_Selected.png', 'order' => '9', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Country Management', 'slug' => 'country-management', 'parent_id' => 35, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                    ['title' => 'Country', 'slug' => 'country', 'parent_id' => 36,'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                    ['title' => 'State', 'slug' => 'state', 'parent_id' => 36, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                    ['title' => 'City', 'slug' => 'city', 'parent_id' => 36,'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
           
            ['title' => 'Genre', 'slug' => 'genre', 'parent_id' => 35, 'icon' => 'images/genre.png', 'active_icon' => 'images/genre_Selected.png', 'order' => '10', 'role_ids' => [1,2, 3], 'status' => '1'],
            
            ['title' => 'Miscellaneous', 'slug' => 'miscellaneous', 'parent_id' => 35, 'icon' => 'images/miscellaneous.png', 'active_icon' => 'images/miscellaneous_Selected.png', 'order' => '11', 'role_ids' => [1,2, 3], 'status' => '1'],
                
                ['title' => 'Ads Management', 'slug' => 'ads-management', 'parent_id' => 41, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Broadcaster', 'slug' => 'broadcaster', 'parent_id' => 41, 'icon' => '', 'active_icon' => '', 'order' => '2', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Bitfram', 'slug' => 'bitfram', 'parent_id' => 41, 'icon' => '', 'active_icon' => '', 'order' => '3', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Content Advisory', 'slug' => 'content-advisory', 'parent_id' => 41, 'icon' => '', 'active_icon' => '', 'order' => '4', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Content Availability', 'slug' => 'content availability', 'parent_id' => 41, 'icon' => '', 'active_icon' => '', 'order' => '5', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Date Formate', 'slug' => 'date-formate', 'parent_id' => 41, 'icon' => '', 'active_icon' => '', 'order' => '6', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Language', 'slug' => 'Language', 'parent_id' => 41, 'icon' => '', 'active_icon' => '', 'order' => '7', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Module', 'slug' => 'module', 'parent_id' => 41, 'icon' => '', 'active_icon' => '', 'order' => '8', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Plateform', 'slug' => 'plateform', 'parent_id' => 41, 'icon' => '', 'active_icon' => '', 'order' => '9', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Viewer Rating', 'slug' => 'viewer-rating', 'parent_id' => 41, 'icon' => '', 'active_icon' => '', 'order' => '10', 'role_ids' => [1,2, 3], 'status' => '1'],

            ['title' => 'Notification', 'slug' => 'notification', 'parent_id' => 0, 'icon' => 'images/notification.png', 'active_icon' => 'images/notification_Selected.png', 'order' => '12', 'role_ids' => [1,2, 3], 'status' => '1'],

                ['title' => 'SMTP', 'slug' => 'smtp', 'parent_id' => 52, 'icon' => '', 'active_icon' => '', 'order' => '1', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Email Template', 'slug' => 'email-template', 'parent_id' => 52, 'icon' => '', 'active_icon' => '', 'order' => '2', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'SMS', 'slug' => 'sms', 'parent_id' => 52, 'icon' => '', 'active_icon' => '', 'order' => '3', 'role_ids' => [1,2, 3], 'status' => '1'],
                ['title' => 'Whatsapp', 'slug' => 'whatsapp', 'parent_id' => 52, 'icon' => '', 'active_icon' => '', 'order' => '4', 'role_ids' => [1,2, 3], 'status' => '1'],
            
            ['title' => 'DRM Settings', 'slug' => 'drm-settings', 'parent_id' => 0, 'icon' => 'images/drmsettings.png', 'active_icon' => 'images/drmsettings_Selected.png', 'order' => '13', 'role_ids' => [1,2, 3], 'status' => '1'],
            ['title' => 'CDN Settings', 'slug' => 'cdn-settings', 'parent_id' => 0, 'icon' => 'images/cdnsetting.png', 'active_icon' => 'images/cdnsetting_Selected.png', 'order' => '14', 'role_ids' => [1,2, 3], 'status' => '1'],
            ['title' => 'Image Setting', 'slug' => 'image-settings', 'parent_id' => 0, 'icon' => 'images/imagesetting.png', 'active_icon' => 'images/imagesetting_Selected.png', 'order' => '15', 'role_ids' => [1,2, 3], 'status' => '1'],    
        ];

        foreach ($modules as $row) {
            DB::table('module')->insert([
                'title'     => $row['title'],
                'slug'      => $row['slug'],
                'parent_id' => $row['parent_id'],
                'icon'      => $row['icon'],
                'order'     =>$row['order'],
                'role_ids'  =>json_encode($row['role_ids']),
                'status'    => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
