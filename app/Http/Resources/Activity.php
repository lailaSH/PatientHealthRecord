<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Activity extends JsonResource
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
            ' العملية' => $this->operation_type,
            'منفذ العملية ' => [$this->first_name, $this->family_name],
            'الوصف' => $this->description,
            'التاريخ' => $this->created_at,

        ];
    }
}
