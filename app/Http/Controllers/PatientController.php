<?php

namespace App\Http\Controllers;

use App\Http\Resources\Activity;
use Illuminate\Http\Request;
use App\Http\Resources\Allergy as ResourcesAllergy;
use App\Http\Resources\Doctor;
use App\Models\Allergy;
use App\Http\Resources\FamilyMadicalHistory as ResourcesFamilyMadicalHistory;
use App\Models\FamilyMadicalHistory;
use App\Models\Habits;
use App\Http\Resources\Habit as ResourcesHabit;
use App\Http\Resources\PatientPersonalInfo as ResourcesPatientPersonalInfo;
use App\Http\Resources\Specification as ResourcesSpecification;
use App\Models\Specification;
use App\Http\Resources\Vaccine  as ResourcesVaccine;
use App\Models\Vaccines;
use App\Http\Resources\Surgeoncy as RecourcesSurgeoncy;
use App\Models\Surgeoncies;
use App\Http\Resources\Review as ResourcesReview;
use App\Models\ActicityLog;
use App\Models\HealthRecord;
use App\Models\Review;
use App\Models\PatientDoctor;
use App\Models\PatientPharmacists;
use App\Models\Pharmacist;
use App\Models\PatientPersonalInfo;
use App\Models\Prescription;
use App\Models\ReviewDate;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PatientController extends BaseController
{
    use GeneralTrait;
    public function login_patient(Request $request)
    {
        $rules = [
            'ID_number' => 'required|exists:patient_personal_infos,ID_number',
            'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->returnError('401', $validator->errors());
        }
        $data = $request->only(['ID_number', 'password']);
        $token = Auth::guard('patient')->attempt($data);
        if (!$token) {
            return $this->returnError(Response::HTTP_UNAUTHORIZED, 'Invalid  Password');
        }
        //
        $patient = Auth::guard('patient')->user();
        $health_record_id = HealthRecord::where('patient_personal_info_id', $patient->id)->first();
        $patient->health_record_id = $health_record_id->id;
        $patient->api_token = $token;

       // for_log  
        $activity = new ActicityLog();
        $activity->operation_type = "تسجيل الدخول";
        $activity->health_record_id = $health_record_id->id;
        $activity->description = "تم تسجيل الدخول إلى سجلك";
        $activity->save();

        return $this->returnData('patient', $patient);
    }
    public function UpdateProfile(Request $request)
    {
        $patient_id = Auth::user()->id;
        $health_record = HealthRecord::where('patient_personal_info_id', $patient_id)->first();
        $health_record_id = $health_record->id;

        $auth_check = JWTAuth::parseToken()->authenticate();
        if ($auth_check) {
            $patient_id = JWTAuth::authenticate($request->token)->id;
            $patient = DB::table('patient_personal_infos')->where(
                'id',
                $patient_id
            )->update([

                "phone_number" => $request->phone_number,
                "phone_number2" => $request->phone_number2,
                "city" => $request->city,
                "email" => $request->email,
                "date_of_birth" => $request->date_of_birth,
                "password" => bcrypt($request->password),
            ]);
            $activity = new ActicityLog();
            $activity->health_record_id = $health_record_id;
            $activity->operation_type = "تعديل";
            $activity->description = "تم العديل على المعلومات الشخصية لديك";
            $activity->save();
            return $this->returnSuccessMessage('تم التعديل بنجاح');
        } else {
            return $this->returnError(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Sorry, token is an invalid'
            );
        }
    }

    //patient_personal_infos
    public function show_personal_infos()
    {
        $patient_id = Auth::user()->id;
        $health_record = HealthRecord::where('patient_personal_info_id', $patient_id)->first();
        $infos = $health_record->PatientPersonalInfo;
        return $this->sendResponse(new ResourcesPatientPersonalInfo($infos), 'success');
    }
    //patinet_health_infos
    public function show_infos()
    {
        $patient_id = Auth::user()->id;
        $health_record = HealthRecord::where('patient_personal_info_id', $patient_id)->first();
        $health_record_id = $health_record->id;
        $specification = Specification::where('health_record_id', $health_record_id)->get();
        $allergy = Allergy::where('health_record_id', $health_record_id)->get();
        $history = FamilyMadicalHistory::where('health_record_id', $health_record_id)->get();
        $habits = Habits::where('health_record_id', $health_record_id)->get();
        $surgeoncies = Surgeoncies::where('health_record_id', $health_record_id)->get();
        $vaccine = Vaccines::where('health_record_id', $health_record_id)->get();

        return $this->sendResponse([
            'Specification' => ResourcesSpecification::collection($specification),
            'Allergy' => ResourcesAllergy::collection($allergy),
            'FamilyMadicalHistory' => ResourcesFamilyMadicalHistory::collection($history),
            'Habits' => ResourcesHabit::collection($habits),
            'Surgeoncies' => RecourcesSurgeoncy::collection($surgeoncies),
            'Vaccines' => ResourcesVaccine::collection($vaccine)
        ], 'success');
    }

    ///////////////////////////////////////////////////
    //diff ways to show the reviews
    public function show_reviews_Ascending_Order()
    {
        $patient_id = Auth::user()->id;
        $health_record = HealthRecord::where('patient_personal_info_id', $patient_id)->first();
        $health_record_id = $health_record->id;
        $reviews = Review::where('health_record_id', $health_record_id)
            ->get();
        return $this->sendResponse(ResourcesReview::collection($reviews), 'success');
    }
    public function show_reviews_Descending_Order()
    {
        $patient_id = Auth::user()->id;
        $health_record = HealthRecord::where('patient_personal_info_id', $patient_id)->first();
        $health_record_id = $health_record->id;
        $reviews = Review::where('health_record_id', $health_record_id)
            ->orderBy('id', 'desc')
            ->get();
        return $this->sendResponse(ResourcesReview::collection($reviews), 'success');
    }
    public function show_reviews_accordingTOdoctor(Request $request)
    {
        $patient_id = Auth::user()->id;
        $health_record = HealthRecord::where('patient_personal_info_id', $patient_id)->first();
        $health_record_id = $health_record->id;
        $reviews = Review::where('health_record_id', $health_record_id)
            ->where('doctor_id', $request->doctor_id)
            ->get();
        return $this->sendResponse(ResourcesReview::collection($reviews), 'success');
    }

    public function review_info(Request $request)
    {
        $review = Review::find($request->review_id);
        $instruction = $review->Instruction;
        $diagnoses = $review->Diagnosis;
        $madical_tests = $review->MadicalTest;
        $progress_note = $review->ProcressNote;
        $referral = $review->Referral;
        $madical_support = $review->MadicalSupport;
        $next_review_date = $review->ReviewDate;
        return $this->sendResponse([
            new ResourcesReview($review),
            [
                'Instruction' =>  $instruction,
                'Diagnose' =>  $diagnoses,
                'MadicalTests' => $madical_tests,
                'NextReviewDate' => $next_review_date,
                'Progress' => $progress_note,
                'Referral' => $referral,
                'MadicalSupport' =>  $madical_support,
            ]
        ], 'success');
    }

    //////////////////////////////////////////////////
    //doctors
    public function doctor_info($doctor_id)
    {
        $doctor = User::find($doctor_id);
        return $this->sendResponse(new Doctor($doctor), 'success');
    }

    public function doctors_list()
    {
        $patient_id = Auth::user()->id;
        $health_record = HealthRecord::where('patient_personal_info_id', $patient_id)->first();
        $health_record_id = $health_record->id;

        $doctors = PatientDoctor::where('health_record_id', $health_record_id)
            // ->with(['User'])
            ->get();
        $records = array();
        foreach ($doctors as $doctor)
            $records[] = $doctor->User;
        return $this->sendResponse(Doctor::collection($records), 'success');
    }
    ////////////////////////////////////tests
    public function tests_list()
    {
        $patient_id = Auth::user()->id;
        $health_record = HealthRecord::where('patient_personal_info_id', $patient_id)->first();
        $health_record_id = $health_record->id;
        $test = new TestController();
        $result = $test->Specify_Test($health_record_id);
        return $this->sendResponse($result, 'success');
    }
    //Activity_Log
    public function activity_log()
    {
        $patient_id = Auth::user()->id;
        $health_record = HealthRecord::where('patient_personal_info_id', $patient_id)->first();
        $health_record_id = $health_record->id;
        $activities = ActicityLog::where('health_record_id', $health_record_id)
            ->take(200)
            ->get();
        return $this->sendResponse(Activity::collection($activities), 'success');
    }
    public function CurrentDrugUsed()
    {
        $patient_id = Auth::user()->id;
        $health_record = HealthRecord::where('patient_personal_info_id', $patient_id)->first();
        $health_record_id = $health_record->id;
        $date = Carbon::now();
        $reviews = Review::where('health_record_id', $health_record_id)->get();
        $drugs = array();
        $Prescriptions = Prescription::all();
        foreach ($reviews as $review) {
            foreach ($Prescriptions as $Prescription) {
                if ($Prescription->review_id == $review->id)
                    if ($Prescription->EndDateUse >= $date) {
                        array_push($drugs, $Prescription->drug_id);
                    }
            }
        }
        if(count($drugs)!=0){
        return $this->returnData('CurrentDrugUsed', $drugs);
        }else
        return $this->returnError('E100','لا يوجد ادوية مستخدمة حاليا');
    }
    // public function AddPharmacist(Request $request)
    // {
    //     $paitent = Auth::user();   
    //     if ($paitent == null) {
    //         return $this->sendError('there is no such ID', null);
    //     } else {
    //         $healthrecord = $paitent->HealthRecord;
    //     }
    //     $arr=array();
    //     $arr=$request->ids;
    //     foreach($arr as $id)
    //     {
    //         $check=Pharmacist::find($id)->first();
    //         if($check)
    //         {
    //             $patientPharmacist=new PatientPharmacists();
    //             $patientPharmacist->pharmacistID=$id;
    //             $patientPharmacist->health_record_id = $healthrecord->id;
    //             $patientPharmacist->save();
    //             //////////for log
    //             $activity = new ActicityLog();
    //             $activity->health_record_id = $healthrecord->id;
    //             $activity->operation_type = "اضافة صيدلي ";
    //             $activity->description = "تم اضافة صيدلي إلى سجلك الطبي";
    //             $activity->save();
    //         }
    //     }
    //     return $this->returnSuccessMessage('تمت الاضافة بنجاح');
    // }
    // public function ShowPharmacist()
    // {
    //     $paitent = Auth::user();        
    //     if ($paitent == null) {
    //         return $this->sendError('there is no such ID', null);
    //     } else {

    //         $healthrecord = $paitent->HealthRecord;
    //     }
    //     $patientPharmacistArr=array();
    //     $patientPharmacists=PatientPharmacists::where('health_record_id',$healthrecord->id)->get();
    //     foreach($patientPharmacists as $patientPharmacist)
    //     {
    //         $Pharmacist=Pharmacist::where('id',$patientPharmacist->pharmacistID)->first();
    //         array_push($patientPharmacistArr,$Pharmacist);
    //     }
    //     return $this->returnData('patientPharmacists',$patientPharmacistArr);
    // }
}