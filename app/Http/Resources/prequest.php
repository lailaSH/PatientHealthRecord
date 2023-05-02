<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class prequest extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'address' => $this->city,
            'phone_number' => $this->phone_number,
            'phone_number2' => $this->phone_number2,
            'email' => $this->email,
            'ID_number' => $this->ID_number,
            'ipersonal_identification_img' => ( ($this->ipersonal_identification_img)),
            'family_health_history' => $this->family_health_history,
        ];
    }
}
