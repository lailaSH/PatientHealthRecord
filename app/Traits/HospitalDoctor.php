<?php

namespace App\Traits;

use App\Models\ActicityLog;
use App\Models\PatientDoctor;
use App\Models\PatientPersonalInfo;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\PatientsName as ResourcesPatientNames;
use App\Http\Resources\PatientPersonalInfo as ResourcesPatientPersonalInfo;
use App\Models\HospitalDoctor as ModelsHospitalDoctor;

trait HospitalDoctor
{
    public function CheckHospitalDoctor(Request $request){
        $paitent = PatientPersonalInfo::where('ID_number',$request->ID_number)->first();
        $HospitalDoctor=ModelsHospitalDoctor::where('DoctorID',$request->DoctorID)->first();
        if($HospitalDoctor){
            $healthrecord = $paitent->HealthRecord;
            $auth_check = JWTAuth::parseToken()->authenticate();
            if ($auth_check) {
                $user = JWTAuth::authenticate($request->token);
            } else {
                return $this->returnError(
                    null,
                    'Sorry, token is an invalid'
                );
                }
                $pat_doc = PatientDoctor::where('user_id', $user->id)
                ->where('health_record_id', $healthrecord->id)
                ->first();
            if ($pat_doc != null) {
                $pat_doc->statu = "yes";
                $pat_doc->save();
                //////////for log
                $activity = new ActicityLog();
                $activity->doctor_id = $user->id;
                $activity->health_record_id = $healthrecord->id;
                $activity->first_name = $user->FirstName;
                $activity->family_name = $user->LastName;
                $activity->operation_type = "الدخول إلى السجل ";
                $activity->description = "تم الدخول إلى سجلك الطبي";
                $activity->save();
                return $this->sendResponse(
                    [
                        'health_record_id' => $healthrecord->id,
                        [
                            'health_record_id' => $healthrecord->id,
                            'Patient_Info' => new ResourcesPatientPersonalInfo($paitent)
                        ],
                    ],
                    'you can enter'
                );
            }
            $patient_doctor = new PatientDoctor();
                $patient_doctor->health_record_id = $healthrecord->id;
                $patient_doctor->user_id = $user->id;
                $patient_doctor->first_name = $paitent->name;
                $patient_doctor->father_name = $paitent->father_name;
                $patient_doctor->family_name = $paitent->family_name;
                $patient_doctor->statu = "yes";
                $patient_doctor->save();
                //////////for log
                $activity = new ActicityLog();
                $activity->doctor_id = $user->id;
                $activity->health_record_id = $healthrecord->id;
                $activity->first_name = $user->FirstName;
                $activity->family_name = $user->LastName;
                $activity->operation_type = "الدخول إلى السجل ";
                $activity->description = "تم الدخول إلى سجلك الطبي";
                $activity->save();
                ////////////////
                if ($healthrecord->first_time == "yes")
                    return $this->sendResponse(
                        [
                            'health_record_id' => $healthrecord->id,
                            'Patient_Info' => new ResourcesPatientPersonalInfo($paitent)
                        ],
                        'you can enter , the health record is still empty'
                    );
                else
                    return $this->sendResponse(
                        [
                            'health_record_id' => $healthrecord->id,
                            'Patient_Info' => new ResourcesPatientPersonalInfo($paitent)
                        ],
                        'you can enter'
                    );
            }
    }
}