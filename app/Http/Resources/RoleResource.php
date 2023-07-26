<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'id'            => $this->id,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'is_parent'     => intval($this->is_parent),
            'created_by'    => $this->created_by ?? "",
            'updated_at'    => $this->updated_at ? date_format_local($this->updated_at) : ""
        ];
    }
}
