<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habits extends Model
{
    use HasFactory;

    public function HealthRecord()
    {
        return $this->belongsTo('App\Models\HealthRecord');
    }
}
