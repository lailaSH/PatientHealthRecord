<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScientificName extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table="Scientific_Name";
    protected $fillable=['id','ScienceName'];
   
    
}
