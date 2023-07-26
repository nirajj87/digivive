<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Role;

class ModuleResource extends JsonResource
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
            'role'          => Role::select('id', 'title')->whereIn('id', json_decode($this->role))->get(),
            // 'roles'          => $this->roles(),
            'order'         => $this->order,
            'parent_id'     => $this->parent,
            'icon'          => $this->icon,
            'created_by'    => $this->created_by ?? "",
            'created_at'    => $this->created_at ? date_format_local($this->created_at) : "",
            'updated_at'    => $this->updated_at ? date_format_local($this->updated_at) : ""
        ];
    }

    public static function toModuleDetailArray($module): array
    {
        return [
            'id'            => $module->id,
            'title'         => $module->title,
            'slug'          => $module->slug,
            'status'        => intval($module->status),
            'role'          => Role::select('id', 'title')->whereIn('id', json_decode($module->role))->get(),
            'order'         => $module->order,
            'icon'          => $module->icon,
            'parent_id'     => $module->parent_id,
            'created_by'    => $module->created_by ?? "",
            'created_at'    => $module->created_at ?? "",
            'updated_at'    => $module->updated_at ?? ""
        ];
    }
}
