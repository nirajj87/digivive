<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientSmtpResource extends JsonResource
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
            'id' => $this->id,
            'host' => $this->host,
            'user_name' => $this->user_name,
            'password' => $this->password,
            'port' => $this->port,
            'encryption' => $this->encryption,
            'from_email' => $this->from_email,
            'user_id' => $this->user_id,
            'status' => $this->status
        ];
    }
}