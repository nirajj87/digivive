<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request); 
    }

    public static function StorageSettingsDetailArray($storageType,$awsStorageSetting,$wowzaStorageSetting)
    {
       
        $newData = [
            'storage_type' => [],
            'wowza' => $wowzaStorageSetting ?? [
                'id' => '',
                'storage_type' => '',
                'host' => '',
                'user_name' => '',
                'password' => '',
                'content_path' => '',
                'wowza_path_format' => '',
                'user_id' => '',
                'cdn_url' => '',
                'status' => '',
                'is_selected' => '',
            ],

            'aws' => $awsStorageSetting ?? [
                'id' => '',
                'storage_type' => '',
                'key' => '',
                'secret' => '',
                'region' => '',
                'default_acl' => '',
                'bucket' => '',
                'row_folder' => '',
                'content_folder' => '',
                'backup_folder' => '',
                'user_id' => '',
                'cdn_url' => '',
                'status' => '',
                'is_selected' => '',
            ]
        ];
        foreach ($storageType as $type) {
            $isSelected = ($type['title'] === 'AWS') ? ($awsStorageSetting ? $awsStorageSetting->is_selected : 0) : ($wowzaStorageSetting ? $wowzaStorageSetting->is_selected : 0);
            //$isSelected = ($type['title'] === 'AWS') ? ($awsStorageSetting ? 1 : 0) : ($wowzaStorageSetting ? 1 : 0);
            $newData['storage_type'][] = [
                'title' => $type['title'],
                'slug' => strtolower($type['title']),
                'is_selected' => $isSelected,
            ];
        };
        return $newData;
    }

}