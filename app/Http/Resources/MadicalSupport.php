<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MadicalSupport extends JsonResource
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
            'Supporter_Specialty' => $this->Supporter_Specialty,
            'Description' => $this->Description,
        ];
    }
}
