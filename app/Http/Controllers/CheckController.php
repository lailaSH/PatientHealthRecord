<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientPersonalInfo as ResourcesPatientPersonalInfo;
use App\Models\HealthRecrod;
use Illuminate\Http\Request;
use App\Http\Resources\PatientsName as ResourcesPatientNames;
use App\Models\ActicityLog;
use App\Models\PatientDoctor;
use App\Models\PatientPersonalInfo;
use App\Models\ProcressNote;
use App\Models\Review;
use App\Models\Specification;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Test;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CheckController extends BaseController
{
    /////////////////1
    public function check_first_time(Request $request)
    {
        $paitent = PatientPersonalInfo::where('ID_number', $request->ID_number)->first();
        
        if ($paitent == null) {
            return $this->sendError('there is no such ID', null);
        } else {

            $healthrecord = $paitent->HealthRecord;

            ////////////////////////////////////////////
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
                $Specification=Specification::where('health_record_id',$healthrecord->id)->orderBy('id', 'desc')->first();
                $progress_note=ProcressNote::where('health_record_id',$healthrecord->id)->orderBy('id', 'desc')->first();
                
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

                ////////////////
                if($Specification!=null && $progress_note!=null){
                $weight=$Specification->weight;
                $height=$Specification->height;
                $bloodType=$Specification->bloodType;
                $physical_health=$progress_note->physical_health;
                $mental_heealth=$progress_note->mental_heealth;
                $body_temperature=$progress_note->body_temperature;
                $pulse_rate=$progress_note->pulse_rate;
                $respiration_rate=$progress_note->respiration_rate;
                $blood_pressure=$progress_note->blood_pressure;

                return response()->json([
                    'status' => true,
                    'errNum' => "S000",
                    'msg' => '',
                    'healthrecord id'=>$healthrecord->id,
                    'weight' => $weight,
                    'height'=>$height,
                    'bloodType'=>$bloodType,
                    'physical_health'=>$physical_health,
                    'mental_heealth'=>$mental_heealth,
                    'body_temperature'=>$body_temperature,
                    'pulse_rate'=>$pulse_rate,
                    'respiration_rate'=>$respiration_rate,
                    'blood_pressure'=>$blood_pressure,

                ]);
            }else{
                return response()->json([
                    'status' => true,
                    'errNum' => "S000",
                    'msg' => '',
                    'healthrecord id'=>$healthrecord->id,
                    'weight' => null,
                    'height'=>null,
                    'bloodType'=>null,
                    'physical_health'=>null,
                    'mental_heealth'=>null,
                    'body_temperature'=>null,
                    'pulse_rate'=>null,
                    'respiration_rate'=>null,
                    'blood_pressure'=>null,

                ]);
            }
                // // return $this->sendResponse(
                //     [
                //         'health_record_id' => $healthrecord->id,
                //         [
                //             'health_record_id' => $healthrecord->id,
                //             'Patient_Info' => new ResourcesPatientPersonalInfo($paitent)
                //         ],
                //     ],
                //     'you can enter'
                // );
            }
            ////////////////////////////////////////////
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
                        'weight' => null,
                    'height'=>null,
                    'bloodType'=>null,
                    'physical_health'=>null,
                    'mental_heealth'=>null,
                    'body_temperature'=>null,
                    'pulse_rate'=>null,
                    'respiration_rate'=>null,
                    'blood_pressure'=>null,

                    ],
                    'you can enter , the health record is still empty'
                );
            else
            if($Specification!=null && $progress_note!=null){
                $weight=$Specification->weight;
                $height=$Specification->height;
                $bloodType=$Specification->bloodType;
                $physical_health=$progress_note->physical_health;
                $mental_heealth=$progress_note->mental_heealth;
                $body_temperature=$progress_note->body_temperature;
                $pulse_rate=$progress_note->pulse_rate;
                $respiration_rate=$progress_note->respiration_rate;
                $blood_pressure=$progress_note->blood_pressure;

                return response()->json([
                    'status' => true,
                    'errNum' => "S000",
                    'msg' => '',
                    'healthrecord id'=>$healthrecord->id,
                    'weight' => $weight,
                    'height'=>$height,
                    'bloodType'=>$bloodType,
                    'physical_health'=>$physical_health,
                    'mental_heealth'=>$mental_heealth,
                    'body_temperature'=>$body_temperature,
                    'pulse_rate'=>$pulse_rate,
                    'respiration_rate'=>$respiration_rate,
                    'blood_pressure'=>$blood_pressure,

                ]);
            }
        }
    }
    ///////////////////////////////////2
    public function show_patients_names(Request $request)
    {
        $auth_check = JWTAuth::parseToken()->authenticate();
        if ($auth_check) {
            $doctor_id = JWTAuth::authenticate($request->token)->id;
            $paitents = PatientDoctor::where('user_id', $doctor_id)->get();
            return $this->sendResponse(ResourcesPatientNames::collection($paitents), 'success');
        } else {
            return $this->returnError(
                null,
                'Sorry, token is an invalid'
            );
        }
    }
    ////////////////////////////////3
    public function check(Request $request)
    {
        $auth_check = JWTAuth::parseToken()->authenticate();
        if ($auth_check) {
            $paitent = PatientPersonalInfo::where('ID_number', $request->ID_number)->first();
            if ($paitent == null) {
                return $this->sendError('there is no such ID', null);
            } else {

                if ($paitent->HealthRecord->id == $request->health_record_id) {
                    $user = JWTAuth::authenticate($request->token);
                    $pateint_doctor = PatientDoctor::where('user_id', $user->id)
                        ->where('health_record_id', $request->health_record_id)
                        ->first();
                    if ($pateint_doctor == null)
                        return $this->sendError('you are not allowed to enter', null);
                    $pateint_doctor->statu = "yes";
                    $pateint_doctor->save();
                    //////////for log
                    $activity = new ActicityLog();
                    $activity->doctor_id =  $user->id;
                    $activity->health_record_id = $request->health_record_id;
                    $activity->first_name = $user->FirstName;
                    $activity->family_name = $user->LastName;
                    $activity->operation_type = "الدخول إلى السجل ";
                    $activity->description = "تم الدخول إلى سجلك الطبي";
                    $activity->save();
                    ////////////////
                    return $this->sendResponse(
                        ['Patient_Info' => new ResourcesPatientPersonalInfo($paitent)],
                        'you can enter'
                    );
                } else {
                    return $this->sendError('you are not allowed to enter', null);
                }
            }
        } else {
            return $this->returnError(
                null,
                'Sorry, token is an invalid'
            );
        }
    }
    public function close_the_record($health_record_id)
    {
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        $pateint_doctor->statu = "no";
        $pateint_doctor->save();
        return $this->sendResponse(
            null,
            'closed'
        );
    }



    public function notify_paitent(Request $request)
    {
        $doctor = Auth::user();
        $data = [
            'type' => 'Doctor',
            'name' => $doctor->FirstName . '  ' . $doctor->LastName,
            'action' => 'الدخول إلى سجلك الصحي',
        ];
        $userSchema = PatientPersonalInfo::find($request->patient_id);
        Notification::send($userSchema, new Test($data));
    }
    /////////////////////////////4
    public function patient_UNreadNotifications(Request $request)
    {
        $paitent = PatientPersonalInfo::find($request->patient_id);
        return $this->sendResponse($paitent->unreadNotifications, 'success');
    }
    /////////////////////////////5
    public function patient_accept(Request $request)
    {
        $paitent = PatientPersonalInfo::find($request->patient_id);
        $paitent->notifications->where('id', $request->notification_id)->markAsRead();
        $notification = DB::table('notifications')->where(
            'id',
            $request->notification_id
        )->update([
            "accept_statu" => true,
        ]);
    }
    /////////////////////////////////////////for_test
    // public function check_doctor_auth($health_record_id)
    // {
    //     $doctor = Auth::user();
    //     $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
    //         ->where('health_record_id', $health_record_id)
    //         ->first();
    //     if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
    //         $result = 'you are not allowed to enter';
    //     else $result = "allowed";
    //     return $result;
    // }
}
