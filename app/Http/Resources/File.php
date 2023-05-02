<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class File extends JsonResource
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
            'madical_test_id' => $this->madical_test_id,
            'file_id' => $this->id,
            'name' => $this->name,
            'size' => $this->size,
            'notes' => $this->notes,

        ];
    }
}
