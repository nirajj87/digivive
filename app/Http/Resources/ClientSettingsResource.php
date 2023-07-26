<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'                        => $this->id,
            'user_id'                   => $this->user_id,
            'video_profile_settings'    => json_decode($this->video_profile_settings),
            'audio_profile_setting'     => json_decode($this->audio_profile_setting),
            'status'                    => $this->status
        ];
    }
}
