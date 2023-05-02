<?php

namespace App\Http\Controllers\PatientInfo;

use App\Http\Controllers\BaseController;
use App\Models\FamilyMadicalHistory;
use Illuminate\Http\Request;
use App\Http\Resources\FamilyMadicalHistory as ResourcesFamilyMadicalHistory;
use App\Models\ActicityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientDoctor;

class FamilyMadicalHistoryController extends BaseController
{

    public function show_histories($health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $history = FamilyMadicalHistory::where('health_record_id', $health_record_id)->get();

        return $this->sendResponse(ResourcesFamilyMadicalHistory::collection($history), 'success');
    }


    public function store_history(Request $request, $health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        for($i=0;$i<count($request->describtion);$i++){
        $history = new FamilyMadicalHistory();
        $history->describtion = $request->describtion[$i];
        $history->health_record_id = $health_record_id;
        $history->save();
        }
        //////////for log
        $activity = new ActicityLog();
        $activity->doctor_id = $doctor->id;
        $activity->health_record_id = $health_record_id;
        $activity->first_name = $doctor->FirstName;
        $activity->family_name = $doctor->LastName;
        $activity->operation_type = "إضافة";
        $activity->description = "تم الإضافة على معلومات تاريخ العائلة المرضي لديك";
        $activity->save();
        ////////////////
        return $this->sendResponse(new ResourcesFamilyMadicalHistory($history), 'success');
    }


    public function show_history_info($history_id)
    {
        $history = FamilyMadicalHistory::find($history_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $history->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        return $this->sendResponse(new ResourcesFamilyMadicalHistory($history), 'success');
    }


    public function update_history(Request $request, $history_id)
    {
        $history = DB::table('family_madical_histories')->where(
            'id',
            $history_id
        )->update([
            "describtion" => $request->describtion,
        ]);
    }

    public function destroy()
    {
        //
    }
}
