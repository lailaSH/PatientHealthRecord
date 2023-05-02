<?php

namespace App\Http\Controllers;

use App\Models\PatientPersonalInfo;
use App\Models\prequest;
use Illuminate\Http\Request;
use App\Http\Resources\prequest as Resourcesprequest;
use App\Models\FamilyMadicalHistory;
use App\Models\HealthRecord;
use App\Models\PatientDisease;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class PatientInfoController extends BaseController
{


use GeneralTrait;
    public function store_patient_info($prequest_id)
    {

        $prequest = prequest::find($prequest_id);

        //create the personal info record 
        $patientPersonalInfo = new PatientPersonalInfo();
        $patientPersonalInfo->name = $prequest->name;
        $patientPersonalInfo->father_name = $prequest->father_name;
        $patientPersonalInfo->family_name = $prequest->family_name;
        $patientPersonalInfo->email = $prequest->email;
        $patientPersonalInfo->gender = $prequest->gender;
        $patientPersonalInfo->date_of_birth = $prequest->date_of_birth;
        $patientPersonalInfo->city = $prequest->city;
        $patientPersonalInfo->phone_number = $prequest->phone_number;
        $patientPersonalInfo->phone_number2 = $prequest->phone_number2;
        $patientPersonalInfo->ID_number = $prequest->ID_number;
        $patientPersonalInfo->ipersonal_identification_img = $prequest->ipersonal_identification_img;
        $password='123456';
        $patientPersonalInfo->password =bcrypt($password);
        $patientPersonalInfo->save();
        //create the healthreacod record
        $healthrecord = new HealthRecord();
        $healthrecord->first_time = "yes";
        $healthrecord->patient_personal_info_id = $patientPersonalInfo->id;
        $healthrecord->save();
        //create the madical family history record
        // $madical_family_history = new FamilyMadicalHistory();
        // $madical_family_history->health_record_id = $healthrecord->id;
        // $madical_family_history->describtion = $prequest->family_health_history;
        // $madical_family_history->save();

        $prequest->delete();

        return $this->sendResponse(null, 'the record created successfully.');
    }
   
}
