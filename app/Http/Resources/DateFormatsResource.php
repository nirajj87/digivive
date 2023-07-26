<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DateFormatsResource extends JsonResource
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
            'format_key'    => $this->format_key,
            'order'         => $this->order,
            'status'        => $this->status
        ];
    }

    public static function toDateTimeArray($date_list, $time_list): array
    {
        return [
            'dates' => self::collection($date_list),
            'times' => self::collection($time_list)
        ];
    }
}
