<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MadicalSupport extends Model
{
    use HasFactory;

    public function Review()
    {
        return $this->belongsTo('App\Models\Review');
    }
}
