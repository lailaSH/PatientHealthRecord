<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Surgeoncy extends JsonResource
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
            'type_of_surgery' => $this->type_of_surgery,
            'date_of_surgery' => $this->date_of_surgery,
            'notes' => $this->notes,
        ];
    }
}
