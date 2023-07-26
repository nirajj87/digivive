<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemplatesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'user_id' => $this->user_id,
            'subject' => $this->subject,
            'body' => $this->body,
            'variables' => $this->variables,
            'status' => $this->status,
            'puchline' => $this->puchline,
            'footer' => $this->footer,
        ];
    }
}