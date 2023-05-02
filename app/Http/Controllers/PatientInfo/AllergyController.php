<?php

namespace App\Http\Controllers\PatientInfo;

use App\Http\Controllers\BaseController;
use App\Models\Allergy;
use Illuminate\Http\Request;
use App\Http\Resources\Allergy as ResourcesAllergy;
use App\Models\ActicityLog;
use Illuminate\Support\Facades\DB;
use App\Models\PatientDoctor;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AllergyController extends BaseController
{
    public function show_allergies($health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $allergy = Allergy::where('health_record_id', $health_record_id)->get();

        return $this->sendResponse(ResourcesAllergy::collection($allergy), 'success');
    }


    public function store_allergy(Request $request, $health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        /////////////////////////////////////
        for($i=0;$i<count($request->element);$i++){
        $allergy = new Allergy();
        $allergy->element = $request->element[$i];
        $allergy->notes = $request->notes[$i];
        $allergy->health_record_id = $health_record_id;
        $allergy->save();
        }
        //////////for log
        $activity = new ActicityLog();
        $activity->doctor_id = $doctor->id;
        $activity->health_record_id = $health_record_id;
        $activity->first_name = $doctor->FirstName;
        $activity->family_name = $doctor->LastName;
        $activity->operation_type = "إضافة";
        $activity->description = "تم الإضافة على معلومات الحساسية لديك";
        $activity->save();

        ////////////////
        return $this->sendResponse(new ResourcesAllergy($allergy), 'success');
    }


    public function show_allergy_info($allergy_id)
    {
        $allergy = Allergy::find($allergy_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id',  $allergy->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        return $this->sendResponse(new ResourcesAllergy($allergy), 'success');
    }


    public function update_allergy(Request $request, $allergy_id)
    {
        $allergy = DB::table('allergies')->where(
            'id',
            $allergy_id
        )->update([
            "element" => $request->element,
            "notes" => $request->notes,
        ]);
    }

    public function destroy()
    {
        //
    }
}
