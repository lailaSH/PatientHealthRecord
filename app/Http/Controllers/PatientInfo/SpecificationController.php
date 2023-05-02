<?php

namespace App\Http\Controllers\PatientInfo;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Specification as ResourcesSpecification;
use App\Models\ActicityLog;
use App\Models\Specification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HealthRecord;
use App\Models\PatientDoctor;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class SpecificationController extends BaseController
{

    public function show_specifications($health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $specification = Specification::where('health_record_id', $health_record_id)->get();
        return $this->sendResponse(ResourcesSpecification::collection($specification), 'success');
    }


    public function store_specification_info(Request $request, $health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $specification = new Specification();
        if($request->has('weight'))
        $specification->weight = $request->weight;
        if($request->has('height'))
        $specification->height= $request->height;
        if($request->has('bloodType'))
        $specification->bloodType = $request->bloodType;
        $specification->health_record_id = $health_record_id;
        $specification->save();
        //////////////////////////////////////
        $health_record = HealthRecord::find($health_record_id);
        $health_record->first_time = "NO";
        $health_record->save();
        //////////////////////////////////////
        //////////for log
        $activity = new ActicityLog();
        $activity->doctor_id = $doctor->id;
        $activity->health_record_id = $health_record_id;
        $activity->first_name = $doctor->FirstName;
        $activity->family_name = $doctor->LastName;
        $activity->operation_type = "إضافة";
        $activity->description = "تم الإضافة على معلومات المواصفات لديك";
        $activity->save();

        ////////////////
        return $this->sendResponse(new ResourcesSpecification($specification), 'success');
    }

    public function show_specification_info($specification_id)
    {
        $specification = Specification::find($specification_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id',  $specification->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        return $this->sendResponse(new ResourcesSpecification($specification), 'success');
    }


    public function update_specification_info(Request $request,  $specification_id)
    {
        // $specification=Specification::find($health_record_id);
        $specification = DB::table('specifications')->where(
            'id',
            $specification_id
        )->update([
            "weight" => $request->weight,
            "height" => $request->height,
            "bloodType" => $request->bloodType,
        ]);
    }

    public function destroy(Specification $specification)
    {
        //
    }
}
