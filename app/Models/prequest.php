<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'father_name',
        'family_name',
        'email',
        'phone_number',
        'city',
        'ID_number',
        'ipersonal_identification_img',
        'family_health_history'
    ];
}
