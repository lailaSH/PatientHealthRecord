<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Habit extends JsonResource
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
            'id' => $this->id,
            'habit' => $this->habit,
            'Description' => $this->Description,
        ];
    }
}
