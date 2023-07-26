<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class SearchSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * 
     */
    public function toArray($request): array
    {
       
       //return parent::toArray($request);

        return [
            'id' => $request->id ?? null,
            'search_type' => $request->search_type ?? null,
            'app_id' => $request->app_id ?? null,
            'key' => $request->key ?? null,
            'collection_name' => $request->collection_name ?? null,
            'host' => $request->host ?? null,
            'port' => $request->port ?? null,
            'protocol' => $request->protocol ?? null,
            'user_id' => $request->user_id ?? null,
            'status' => $request->status ?? null
        ];
    }
    
    public static function SearchSettingsDetailArray($searchType,$algoliaSearchSettings,$typesenseSearchSettings)
    {   
        $newData = [
            'search_type' => [],
            'algolia' => $algoliaSearchSettings ?? [
                'search_type' => '',
                'app_id' => '',
                'key' => '',
                'collection_name' => '',
                'host' => '',
                'port' => '',
                'protocol' => '',
                'user_id' => '',
                'is_selected' => '',
                'status' => ''
            ],
            'typesense' => $typesenseSearchSettings ?? [
                'search_type' => '',
                'app_id' => '',
                'key' => '',
                'collection_name' => '',
                'host' => '',
                'port' => '',
                'protocol' => '',
                'user_id' => '',
                'is_selected' => '',
                'status' => ''
            ]
        ];
        foreach ($searchType as $type) {
            $isSelected = ($type['title'] === 'ALGOLIA') ? ($algoliaSearchSettings ? $algoliaSearchSettings->is_selected : 0) : ($typesenseSearchSettings ? $typesenseSearchSettings->is_selected : 0);
            $newData['search_type'][] = [
                'title' => $type['title'],
                'slug' => strtolower($type['title']),
                'is_selected' => $isSelected,
            ];
        };
        return $newData;
    }
}