<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CloudinaryImageSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'storage_type' => $this->storage_type,
            'host' => $this->host,
            'user_name' => $this->user_name,
            'password' => $this->password,
            'content_path' => $this->content_path,
            'wowza_path_format' => $this->wowza_path_format,
            'user_id' => $this->user_id,
            'cdn_url' => $this->cdn_url,
            'status' => $this->status

        ];
    }
}