<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Specification as ResourcesSpecification;
use App\Models\ActicityLog;
use App\Models\Specification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HealthRecord;
use App\Models\PatientDoctor;
use App\Models\ProcressNote;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;


class SpecVitalSignsController extends Controller
{
public function CreateSpecVitalSigns(Request $request, $health_record_id)
{
    $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
       
    $specification = new Specification();
    if($request->weight)
    $specification->weight = $request->weight;
    if($request->height)
    $specification->height= $request->height;
    if($request->bloodType)
    $specification->bloodType = $request->bloodType;
    $specification->health_record_id = $health_record_id;
    $specification->save();


    $progress_note = new ProcressNote();
    $progress_note->health_record_id = $health_record_id;
        $progress_note->physical_health = $request->physical_health;
        $progress_note->mental_heealth = $request->mental_heealth;
        ////Vital Signs
        $progress_note->body_temperature = $request->body_temperature;
        $progress_note->pulse_rate = $request->pulse_rate;
        $progress_note->respiration_rate = $request->respiration_rate;
        $progress_note->blood_pressure = $request->blood_pressure;
        $progress_note->save();


    //////////for log
    $activity = new ActicityLog();
    $activity->doctor_id = $doctor->id;
    $activity->health_record_id = $health_record_id;
    $activity->first_name = $doctor->FirstName;
    $activity->family_name = $doctor->LastName;
    $activity->operation_type = "إضافة";
    $activity->description = "تم الإضافة على معلومات المواصفات و العلامات الحيوية لديك";
    $activity->save();

}

}