<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MadicalTest extends JsonResource
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
            'madical_test_id' => $this->id,
            'type' => $this->type,
            'description' => $this->description,
            'date' => $this->date,
            'executor' => $this->executor,
            'executor_number' => $this->number,
        ];
    }
}
