<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgressNote extends JsonResource
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
            'physical_health' => $this->physical_health,
            'mental_heealth' => $this->mental_heealth,
            'body_temperature' => $this->body_temperature,
            'pulse_rate ' => $this->pulse_rate,
            'respiration_rate' => $this->respiration_rate,
            'blood_pressure' => $this->blood_pressure,
        ];
    }
}
