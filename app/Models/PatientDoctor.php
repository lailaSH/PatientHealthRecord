<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDoctor extends Model
{
    use HasFactory;
    public function HealthRecord()
    {
        return $this->belongsTo('App\Models\HealthRecord');
    }
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
