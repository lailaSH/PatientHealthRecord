<?php

namespace App\Http\Controllers\PatientInfo;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Surgeoncy as RecourcesSurgeoncy;
use App\Models\ActicityLog;
use App\Models\Surgeoncies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientDoctor;

class SurgeonciesController extends BaseController
{
    public function show_surgeoncies($health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $surgeoncies = Surgeoncies::where('health_record_id', $health_record_id)->get();
        return $this->sendResponse(RecourcesSurgeoncy::collection($surgeoncies), 'success');
    }


    public function store_surgeoncy(Request $request, $health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $surgeoncy = new Surgeoncies();
        $surgeoncy->type_of_surgery = $request->type_of_surgery;
        $surgeoncy->date_of_surgery = $request->date_of_surgery;
        $surgeoncy->notes = $request->notes;
        $surgeoncy->result = $request->result;
        $surgeoncy->description = $request->description;
        $surgeoncy->health_record_id = $health_record_id;
        $surgeoncy->save();
        //////////for log
        $activity = new ActicityLog();
        $activity->doctor_id = $doctor->id;
        $activity->health_record_id = $health_record_id;
        $activity->first_name = $doctor->FirstName;
        $activity->family_name = $doctor->LastName;
        $activity->operation_type = "إضافة";
        $activity->description = "تم الإضافة على معلومات الجراحات لديك";
        $activity->save();

        ////////////////
        return $this->sendResponse(new RecourcesSurgeoncy($surgeoncy), 'success');
    }

    public function show_surgeoncy_info($surgeoncy_id)
    {
        $surgeoncy = Surgeoncies::find($surgeoncy_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $surgeoncy->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        return $this->sendResponse(new RecourcesSurgeoncy($surgeoncy), 'success');
    }


    public function update_surgeoncy(Request $request, $surgeoncy_id)
    {
        // $specification=Specification::find($health_record_id);
        $surgeoncy = DB::table('surgeoncies')->where(
            'id',
            $surgeoncy_id
        )->update([
            "type_of_surgery" => $request->type_of_surgery,
            "date_of_surgery" => $request->date_of_surgery,
            "notes" => $request->notes,
            "result" => $request->result,
            "description" => $request->description,
        ]);
    }

    public function destroy()
    {
        //
    }
}
