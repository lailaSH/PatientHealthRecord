<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewDate extends Model
{
    use HasFactory;

    public function Review()
    {
        return $this->belongsTo('App\Models\Review');
    }
}
