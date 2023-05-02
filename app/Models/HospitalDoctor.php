<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalDoctor extends Model
{
    use HasFactory;
    public $table = "hospital_doctors";

    public function Doctor()
    {
        return $this->belongsTo('App\Models\Doctor','DoctorID');
    }
    public function Hospital()
    {
        return $this->belongsTo('App\Models\Hospital','HospitalID');
    }
}
