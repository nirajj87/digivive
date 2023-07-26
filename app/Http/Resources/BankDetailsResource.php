<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return[
            'beneficiary_name'              => $this->beneficiary_name,
            'bank_name'                     => $this->bank_name,
            'account_number'                => $this->account_number,
            'ifsc_code'                     => $this->ifsc_code,
            'swift_code'                    => $this->swift_code,
            'bank_address'                  => json_decode($this->bank_address),
            'cancelled_cheque'              => $this->cancelled_cheque,
            'payment_gateway_id'            => $this->payment_gateway_id,
            'gstin'                         => $this->gstin,
            'cin'                           => $this->cin,
            'pan'                           => $this->pan,
            'status'                        => $this->status,
        ];
    }
}
