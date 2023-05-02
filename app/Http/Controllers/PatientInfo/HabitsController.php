<?php

namespace App\Http\Controllers\PatientInfo;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Habit as ResourcesHabit;
use App\Models\ActicityLog;
use App\Models\Habits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientDoctor;

class HabitsController extends BaseController
{


    public function show_habits($health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $habits = Habits::where('health_record_id', $health_record_id)->get();
        return $this->sendResponse(ResourcesHabit::collection($habits), 'success');
    }


    public function store_habit(Request $request, $health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $habit = new Habits();
        $habit->habit = $request->habit;
        $habit->Description = $request->Description;
        $habit->health_record_id = $health_record_id;
        $habit->save();
        //////////for log
        $activity = new ActicityLog();
        $activity->doctor_id = $doctor->id;
        $activity->health_record_id = $health_record_id;
        $activity->first_name = $doctor->FirstName;
        $activity->family_name = $doctor->LastName;
        $activity->operation_type = "إضافة";
        $activity->description = "تم الإضافة على معلومات العادات الصحية لديك";
        $activity->save();

        ////////////////
        return $this->sendResponse(new ResourcesHabit($habit), 'success');
    }


    public function show_habit_info($habit_id)
    {
        $habit = Habits::find($habit_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $habit->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        return $this->sendResponse(new ResourcesHabit($habit), 'success');
    }


    public function update_habit(Request $request, $habit_id)
    {
        $habit = DB::table('habits')->where(
            'id',
            $habit_id
        )->update([
            "habit" => $request->habit,
            "Description" => $request->Description,
        ]);
    }

    public function destroy()
    {
        //
    }
}
