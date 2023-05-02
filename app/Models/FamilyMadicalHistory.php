<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMadicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'describtion'
    ];


    public function HealthRecord()
    {
        return $this->belongsTo('App\Models\HealthRecord');
    }
}
