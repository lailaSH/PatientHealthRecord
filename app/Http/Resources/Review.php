<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Review extends JsonResource
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
            'health_record_id' => $this->health_record_id,
            'review_id' => $this->id,
            'doctor_id' => $this->doctor_id,
            'first_name' => $this->first_name,
            'family_name' => $this->family_name,
            'creation_date' => $this->created_at,
        ];
    }
}
