<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranscodingSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $videoPresets = json_decode($this->video_presets_id) ?? [
            "144" => 0,
            "360" => 0,
            "480" => 0,
            "720" => 0,
            "1080" => 0,
            "audio" => 0
        ];
        $videoDownload = json_decode($this->video_download_type_id) ?? [
            "sd" => [
                "title" => "SD",
                "is_selected" => 0,
                "presets" => [
                    "144" => 0,
                    "360" => 0,
                    "480" => 0,
                    "720" => 0,
                    "1080" => 0,
                    "audio" => 0
                ]
            ],
            "hd" => [
                "title" => "HD",
                "is_selected" => 0,
                "presets" => [
                    "144" => 0,
                    "360" => 0,
                    "480" => 0,
                    "720" => 0,
                    "1080" => 0,
                    "audio" => 0
                ]
            ],
            "hq" => [
                "title" => "HQ",
                "is_selected" => 0,
                "presets" => [
                    "144" => 0,
                    "360" => 0,
                    "480" => 0,
                    "720" => 0,
                    "1080" => 0,
                    "audio" => 0
                ]
            ]
        ];
        $audioPresets = json_decode($this->audio_presets_id) ?? [
            "64" => 0,
            "128" => 0,
            "is_selected" => 0
        ];
        $audioDownload = json_decode($this->audio_download_type_id) ?? [
            "lq" => [
                "title" => "LQ",
                "is_selected" => 0,
                "presets" => [
                    "64" => 0,
                    "128" => 0,
                    "is_selected" => 0
            ]
            ],
            "hq" => [
                "title" => "LQ",
                "is_selected" => 0,
                "presets" => [
                    "64" => 0,
                    "128" => 0,
                    "is_selected" => 0
            ]
            ]
        ];
        return [
            'video' => [
                "title" => "Video Transcoding",
                "presets" => $videoPresets,
                "download" => $videoDownload
            ],
            'audio' => [
                "title" => "Audio Transcoding",
                "presets" => $audioPresets,
                "download" => $audioDownload
            ],
            'status' => $this->status ?? ""
        ];
    }
}
