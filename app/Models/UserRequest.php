<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory;
    public $table = "userrequests";

    protected $fillable = [
        'FirstName',
        'FatherName',
        'LastName',
        'phoneNumber',
        'city',
        'type',
        'CertificateImage',
        'ipersonal_identification_img',
    ];
}
