<?php

namespace App\Http\Controllers\PatientInfo;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Vaccine  as ResourcesVaccine;
use App\Models\ActicityLog;
use App\Models\Vaccines;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientDoctor;

class VaccinesController extends BaseController
{
    public function show_vaccines($health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $vaccine = Vaccines::where('health_record_id', $health_record_id)->get();
        return $this->sendResponse(ResourcesVaccine::collection($vaccine), 'success');
    }


    public function store_vaccine(Request $request, $health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $vaccine = new Vaccines();
        $vaccine->type = $request->type;
        $vaccine->date = $request->date;
        $vaccine->notes = $request->notes;
        $vaccine->health_record_id = $health_record_id;
        $vaccine->save();
        //////////for log
        $activity = new ActicityLog();
        $activity->doctor_id = $doctor->id;
        $activity->health_record_id = $health_record_id;
        $activity->first_name = $doctor->FirstName;
        $activity->family_name = $doctor->LastName;
        $activity->operation_type = "إضافة";
        $activity->description = "تم الإضافة على معلومات اللقاحات لديك";
        $activity->save();
        ////////////////
        return $this->sendResponse(new ResourcesVaccine($vaccine), 'success');
    }


    public function show_vaccine_info($vaccine_id)
    {
        $vaccine = Vaccines::find($vaccine_id);
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $vaccine->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        return $this->sendResponse(new ResourcesVaccine($vaccine), 'success');
    }


    public function update_vaccine(Request $request, $vaccine_id)
    {
        // $specification=Specification::find($health_record_id);
        $vaccine = DB::table('vaccines')->where(
            'id',
            $vaccine_id
        )->update([
            "type" => $request->type,
            "date" => $request->date,
            "notes" => $request->notes,
        ]);
    }

    public function destroy()
    {
        //
    }
}
