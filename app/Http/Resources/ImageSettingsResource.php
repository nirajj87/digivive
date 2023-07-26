<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageSettingsResource extends JsonResource
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

    public static function ImageSettingsDetailArray($storageType,$awsImageSettings,$cloudinaryImageSettings)
    {   
        
        $newData = [
            'storage_type' => [],
            'cloudinary' => [
                'id' => $cloudinaryImageSettings->id ?? '',
                'storage_type' => $cloudinaryImageSettings->storage_type ?? '',
                'key' => $cloudinaryImageSettings->key ?? '',
                'secret' => $cloudinaryImageSettings->secret ?? '',
                'bucket' => $cloudinaryImageSettings->bucket ?? '',
                'status' => $cloudinaryImageSettings->status ?? '',
                'cdn_url' => $cloudinaryImageSettings->cdn_url ?? '',
                'is_selected' => $cloudinaryImageSettings->is_selected ?? '',
                'user_id' => $cloudinaryImageSettings->user_id ?? ''
            ],
            'aws' => [
                'id' => $awsImageSettings->id ?? '',
                'storage_type' => $awsImageSettings->storage_type ?? '',
                'copy_credential_same_as_aws' => $awsImageSettings->copy_credential_same_as_aws ?? '',
                'key' => $awsImageSettings->key ?? '',
                'secret' => $awsImageSettings->secret ?? '',
                'region' => $awsImageSettings->region ?? '',
                'default_acl' => $awsImageSettings->default_acl ?? '',
                'bucket' => $awsImageSettings->bucket ?? '',
                'row_folder' => $awsImageSettings->row_folder ?? '',
                'content_folder' => $awsImageSettings->content_folder ?? '',
                'backup_folder' => $awsImageSettings->backup_folder ?? '',
                'path_format' => $awsImageSettings->path_format ?? '',
                'is_selected' => $awsImageSettings->is_selected ?? '',
                'user_id' => $awsImageSettings->user_id ?? '',
                'cdn_url' => $awsImageSettings->cdn_url ?? '',
                'status' => $awsImageSettings->status ?? '',
            ]
        ];
        foreach ($storageType as $type) {
            $isSelected = ($type['title'] === 'AWS') ? ($awsImageSettings ? $awsImageSettings->is_selected : 0) : ($cloudinaryImageSettings ? $cloudinaryImageSettings->is_selected : 0);
            $newData['storage_type'][] = [
                'title' => $type['title'],
                'slug' => strtolower($type['title']),
                'is_selected' => $isSelected,
            ];
        };
        return $newData;
    }
}
