<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AwsImageSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof Collection) {
            return $this->resource->toArray();
        }
        return parent::toArray($request);
        return [
            'id' => $this->id,
            'storage_type' => $this->storage_type,
            'copy_credential_same_as_aws' => $this->copy_credential_same_as_aws,
            'key' => $this->key,
            'secret' => $this->secret,
            'region' => $this->region,
            'default_acl' => $this->default_acl,
            'bucket' => $this->bucket,
            'row' => $this->row,
            'content' => $this->content,
            'backup' => $this->backup,
            'user_id' => $this->user_id,
            'cdn_url' => $this->cdn_url,
            'status' => $this->status


        ];
    }
}