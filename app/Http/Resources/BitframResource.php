<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BitframResource extends JsonResource
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
            'type' => $this->type,
            'presets' => $this->presets,
            'is_downloadable' => $this->is_downloadable,
            'download_type' => $this->download_type,
            'status' => $this->status

        ];
    }
}