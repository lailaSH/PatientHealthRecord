<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientPersonalInfo extends JsonResource
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
            'name' => $this->name,
            'father_name' => $this->father_name,
            'family_name' => $this->family_name,
            'phone_number' => $this->phone_number,
            'phone_number2' => $this->phone_number2,
            'city' => $this->city,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'email' => $this->email,
            'created_date' => $this->created_at

        ];
    }
}
