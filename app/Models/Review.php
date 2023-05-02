<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $hidden = [
        'first_name',
        'family_name',
        'health_record_id',
        'doctor_id',
    ];

    use HasFactory;
    public function HealthRecord()
    {
        return $this->belongsTo('App\Models\HealthRecord');
    }
    public function ReviewDate()
    {
        return $this->hasOne('App\Models\ReviewDate');
    }
    public function Instruction()
    {
        return $this->hasMany('App\Models\Instruction');
    }
    public function Diagnosis()
    {
        return $this->hasOne('App\Models\Diagnosis');
    }
    public function MadicalTest()
    {
        return $this->hasMany('App\Models\MadicalTest');
    }
    public function ProcressNote()
    {
        return $this->hasOne('App\Models\ProcressNote');
    }
    public function Referral()
    {
        return $this->hasOne('App\Models\Referral');
    }
    public function MadicalSupport()
    {
        return $this->hasMany('App\Models\MadicalSupport');
    }
}
