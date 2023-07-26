<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Role;

class ModulePermissionsResource extends JsonResource
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
            'id'            => $this->id,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'status'        => $this->status,
            'order'         => $this->order,
            'parent_id'     => $this->parent_id,
            'privileges'    => $this->privileges,
            'sub_modules'   => $this->sub_modules,
            'created_by'    => $this->created_by ?? "",
            'created_at'    => $this->created_at ?? "",
            'updated_at'    => $this->updated_at ?? ""
        ];
    }

    public static function userSavedPermissions($permissions): array
    {
        return [
            json_decode($permissions)[0]
        ];
    }
}
