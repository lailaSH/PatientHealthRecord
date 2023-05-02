<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Referral extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'review_id' => $this->review_id,
            'Doctor_Specialty' => $this->Doctor_Specialty,
            'Description' => $this->Description,
        ];
    }
}
