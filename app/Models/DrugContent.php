<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugContent extends Model
{
    use HasFactory;
    protected $table="drug_content";
    protected $fillable=[
	'factName',
    'factoryCountry',
    'lang',
    'marketName',
    'gauge',
    'dosage',
    'effects',
    'interaction',
    'warnings',
    'form1',
    'formThum',
	'indication',
    'antiIndication',
     ];

     public function ScientificNames()
    {
        return $this->belongsToMany(ScientificName::class, 'DrugScientificName', 'DrugID', 'ScientificID');


    }
}
