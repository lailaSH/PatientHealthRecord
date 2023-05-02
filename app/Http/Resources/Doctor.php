<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Doctor extends JsonResource
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
            'doctor_id' => $this->id,
            'FirstName' => $this->FirstName,
            'FatherName' => $this->FatherName,
            'LastName' => $this->LastName,
            'city' => $this->city,
            'phoneNumber' => $this->phoneNumber,
            'type' => $this->type,
            'email' => $this->email

        ];
    }
}
