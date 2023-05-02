<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;


    public $table = "healthrecords";


    public function PatientPersonalInfo()
    {
        return $this->belongsTo('App\Models\PatientPersonalInfo');
    }

    public function FamilyMadicalHistory()
    {
        return $this->hasMany('App\Models\FamilyMadicalHistory');
    }


    public function PatientDisease()
    {
        return $this->hasMany('App\Models\PatientDisease');
    }
    public function Habits()
    {
        return $this->hasMany('App\Models\Habits');
    }
    public function Surgeoncies()
    {
        return $this->hasMany('App\Models\Surgeoncies');
    }

    public function Specification()
    {
        return $this->hasMany('App\Models\Specification');
    }
    public function Vaccines()
    {
        return $this->hasMany('App\Models\Vaccines');
    }
    public function Allergy()
    {
        return $this->hasMany('App\Models\Allergy');
    }

    public function Review()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function PatientDoctor()
    {
        return $this->hasMany('App\Models\PatientDoctor');
    }
}
