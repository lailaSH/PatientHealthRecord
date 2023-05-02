<?php

namespace App\Http\Controllers\DoctorReview;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Diagnose as ResourcesDiagnose;
use App\Http\Resources\Instruction as ResourcesInstruction;
use App\Http\Resources\MadicalSupport as ResourcesMadicalSupport;
use App\Http\Resources\MadicalTest as ResourcesMadicalTest;
use App\Http\Resources\NextReviewDate as ResourcesNextReviewDate;
use App\Http\Resources\ProgressNote as ResourcesProgressNote;
use App\Http\Resources\Referral as ResourcesReferral;
use App\Http\Resources\Review as ResourcesReview;
use App\Models\ActicityLog;
use App\Models\Diagnosis;
use App\Models\DrugContent;
use App\Models\DrugsDisease;
use App\Models\groups;
use App\Models\HealthRecrod;
use App\Models\Instruction;
use App\Models\MadicalSupport;
use App\Models\MadicalTest;
use App\Models\PatientDisease;
use App\Models\Review;
use App\Models\ReviewDate;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\PatientDoctor;
use App\Models\Prescription;
use App\Models\ProcressNote;
use App\Models\Referral;
use App\Models\ScientificName;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends BaseController
{
    use GeneralTrait;
    public function cteate_review($health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);
        //////////////////////////////////////
        $review = new Review();
        $review->health_record_id = $health_record_id;
        $review->doctor_id = $doctor->id;
        $review->first_name = $doctor->FirstName;
        $review->family_name = $doctor->LastName;
        $review->save();
        //////////for log
        $activity = new ActicityLog();
        $activity->doctor_id = $doctor->id;
        $activity->health_record_id = $health_record_id;
        $activity->first_name = $doctor->FirstName;
        $activity->family_name = $doctor->LastName;
        $activity->operation_type = "إضافة";
        $activity->description = "تم إضافة مراجعة جديدة لديك";
        $activity->save();
        ////////////////
        return $this->sendResponse(new ResourcesReview($review), 'success');
    }


    public function create_diagnose(Request $request, $review_id)
    {
        $review = Review::find($review_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $review->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);
        //////////////////////////////////////
        $diagnose = new Diagnosis();
        $diagnose->review_id = $review_id;
        $diagnose->description = $request->description;
        $diagnose->save();
        return $this->sendResponse(new ResourcesDiagnose($diagnose), 'success');
    }

    public function create_next_review_date(Request $request, $review_id)
    {
        $review = Review::find($review_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $review->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);
        //////////////////////////////////////
        $next_review_date = new ReviewDate();
        $next_review_date->date = $request->date;
        $next_review_date->time = $request->time;
        $next_review_date->review_id = $review_id;
        $next_review_date->save();
        return $this->sendResponse(new ResourcesNextReviewDate($next_review_date), 'success');
    }

    public function create_instruction(Request $request, $review_id)
    {
        $review = Review::find($review_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $review->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);
        //////////////////////////////////////
        $instruction = new Instruction();
        $instruction->instruction = $request->instruction;
        $instruction->review_id = $review_id;
        $instruction->save();
        return $this->sendResponse(new ResourcesInstruction($instruction), 'success');
    }
    public function create_procress_notes(Request $request, $health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);
        //////////////////////////////////////
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
        return $this->sendResponse(new ResourcesProgressNote($progress_note), 'success');
    }
    //Treatment Plan
    public function Referral(Request $request, $review_id)
    {
        $review = Review::find($review_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $review->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);
        //////////////////////////////////////
        $referral = new Referral();
        $referral->review_id = $review_id;
        $referral->Doctor_Specialty = $request->Doctor_Specialty;
        $referral->Description = $request->Description;
        $referral->save();
        return $this->sendResponse(new ResourcesReferral($referral), 'success');
    }

    public function madical_support(Request $request, $review_id)
    {
        $review = Review::find($review_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $review->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);
        //////////////////////////////////////
        $madical_support = new MadicalSupport();
        $madical_support->review_id = $review_id;
        $madical_support->Supporter_Specialty = $request->Supporter_Specialty;
        $madical_support->Description = $request->Description;
        $madical_support->save();
        return $this->sendResponse(new ResourcesMadicalSupport($madical_support), 'success');
    }
    /////////////////////////////////////////////////////////
    public function show_reviews(Request $request, $health_record_id)
    {
        $auth_check = JWTAuth::parseToken()->authenticate();
        if ($auth_check) {
            $doctor = JWTAuth::authenticate($request->token);

            $reviews = Review::where('health_record_id', $health_record_id)
                ->where('doctor_id', $doctor->id)
                ->get();
            return $this->sendResponse(ResourcesReview::collection($reviews), 'success');
        }
    }
    public function show_review_info(Request $request, $review_id)
    {
        $auth_check = JWTAuth::parseToken()->authenticate();
        if ($auth_check) {
            $doctor_id = JWTAuth::authenticate($request->token)->id;
            $review = Review::find($review_id);
            if ($review->doctor_id != $doctor_id)
                return $this->sendError('you are not allowed to show this review', null);

            $instruction = $review->Instruction;
            $diagnoses = $review->Diagnosis;
            $madical_tests = $review->MadicalTest;
           // $progress_note = $review->ProcressNote;
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
                    // 'Progress' => $progress_note,
                    'Referral' => $referral,
                    'MadicalSupport' =>  $madical_support,
                ]
            ], 'success');
        }
    }
    public function interactions(Request $request,$review_id)
    {

        /////////////تعارضات الادوية فيما  بينها  
        $DrugDrugInteraction=array();
        $NameDrugs=array();

        $drugs=array();
        $drugsName=$request->drugsName;
        foreach($drugsName as $dr)
        {
            $d=DrugContent::where('marketName',$dr)->first();
            if(!$d)
            {
                return $this->returnError('#E100','غير موجود'.$dr);
            }else
            array_push($drugs,$d->docid);
        }
        foreach($drugs as $drug1)
        {
            $drug_scientific_name= DB::table('drug_scientific_name')->where('DrugID',$drug1)->get();
            $scientific_name=array();
            $scs=ScientificName::all();
            foreach($drug_scientific_name as $value){
                foreach($scs as $sc){
                    if($sc->id==$value->ScientificID)
                        array_push($scientific_name,$sc->ScienceName);
                }
            }
            foreach($drugs as $drug2)
            {
                if($drug1!=$drug2)
                foreach($scientific_name as $Sc)
                {
                    $interactions=DrugContent::where('interaction','like','%'. $Sc .'%')->first();

                    if($interactions!=null)
                    {
                        $inter=DrugContent::where('docid',$drug2)->first();
                        array_push($DrugDrugInteraction,$inter->interaction);
                        $d1=DrugContent::where('docid',$drug1)->first();
                        $d2=DrugContent::where('docid',$drug2)->first();
                        array_push($NameDrugs,$d1->marketName.' X '.$d2->marketName);

                    }
                }
            }

        }

        
        ///////////////تعارضات الادوية مع الامراض 

        $review = Review::find($review_id);
        $health_record_id=$review->health_record_id;
        $NameDrugsDisease=array();
        $diseases = PatientDisease::where('health_record_id', $health_record_id)->get();
        foreach($diseases as $d){
        if($d->type!=null)
        {
        $Disease=$d->type;
        $DrugsDisease=DrugsDisease::where('CodeDisease',$Disease)->get();
        $interactions=array();
        if($DrugsDisease){
        foreach($drugsName as $drug){
        $Drugs=DrugContent::where('marketName',$drug)->first();
        $drug_scientific_name= DB::table('drug_scientific_name')->where('DrugID',$Drugs->docid)->get();
        $scientific_name=array();
        $scs=ScientificName::all();
        foreach($drug_scientific_name as $value){
            foreach($scs as $sc){
                if($sc->id==$value->ScientificID)
                    array_push($scientific_name,$sc->ScienceName);

            }
        }
        $groupsdrug= DB::table('groupsdrug')->where('DrugID',$Drugs->docid)->get();
        $groups=array();
        $gs=groups::all();
        foreach($groupsdrug as $value){
            foreach($gs as $g){
                if($g->id==$value->GroupID)
            array_push($groups,$g->groupName);
            }
        }
    
        foreach($DrugsDisease as $DD){
            if($DD->NameDrug!=null){
            foreach($scientific_name as $ScDrug)
                {
                    $check=false;
                    if($ScDrug==$DD->NameDrug){
                        foreach($interactions as $IA){
                            if($IA==$DD->descrpition)
                            $check=true;
                        }
                        if(!$check){
                        array_push($interactions,$DD->descrpition);
                        array_push($NameDrugsDisease,$drug.' X '.$d->disease);
    
                    }
                    }
                }
            }
            if($DD->NameGruop!=null)
            {
                foreach($groups as $g)
                {
                    $check=false;
                    if($g==$DD->NameGruop){
                        foreach($interactions as $IA){
                            if($IA==$DD->descrpition)
                            $check=true;
                        }
                        if(!$check)
                        {
                        array_push($interactions,$DD->descrpition);
                        array_push($NameDrugsDisease,$drug.' X '.$d->disease);

                    }
                    }
                }
            }
            
        }
     }
        }
        }
        }
        
        if(count($DrugDrugInteraction)!=0&&count($interactions)!=0){
            return response()->json([
                'status' => true,
                'errNum' => "S000",
                'msg' => '',
                'NameDrugs'=>$NameDrugs,
                'DrugDrugInteraction' => $DrugDrugInteraction,
                'NameDrugsDisease'=>$NameDrugsDisease,
                'DiseaseDruginteractions'=>$interactions,
            ]);
        }

        if(count($DrugDrugInteraction)!=0&&count($interactions)==0){
            return response()->json([
                'status' => true,
                'errNum' => "S000",
                'msg' => '',
                'NameDrugs'=>$NameDrugs,
                'DrugDrugInteraction' => $DrugDrugInteraction,
                'NameDrugsDisease'=>null,
                'DiseaseDruginteractions'=>'لا يوجد تعارضات بين الادوية والسجل الصحي',
            ]);
        }
        if(count($DrugDrugInteraction)==0&&count($interactions)!=0){
            return response()->json([
                'status' => true,
                'errNum' => "S000",
                'msg' => '',
                'NameDrugs'=>null,
                'DrugDrugInteraction' => 'لا يوجد تداخلان بين الادوية',
                'NameDrugsDisease'=>$NameDrugsDisease,
                'DiseaseDruginteractions'=>$interactions,
            ]);
        }
        if(count($DrugDrugInteraction)==0&&count($interactions)==0){
            return $this->returnError('#E100',' لا يوجد تعارضات ولا يوجد تداخلات');

        }

        }


    public function createDiagInstPres(Request $request, $health_record_id){
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);

        $review = new Review();
        $review->health_record_id = $health_record_id;
        $review->doctor_id = $doctor->id;
        $review->first_name = $doctor->FirstName;
        $review->family_name = $doctor->LastName;
        $review->save();
        //////////for log
        $activity = new ActicityLog();
        $activity->doctor_id = $doctor->id;
        $activity->health_record_id = $health_record_id;
        $activity->first_name = $doctor->FirstName;
        $activity->family_name = $doctor->LastName;
        $activity->operation_type = "إضافة";
        $activity->description = "تم إضافة مراجعة جديدة لديك";
        $activity->save();
        
        $review_id=Review::orderByDesc('health_record_id',$health_record_id)->first();
        $review_id=$review_id->id;
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $review->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);
        //////////////////////////////////////
        if($request->description!=null){
        $diagnose = new Diagnosis();
        $diagnose->review_id = $review_id;
        $diagnose->description = $request->description;
        $diagnose->save();

        }
        if($request->instruction!=null){

         //////////////////////////////////////
        $instruction = new Instruction();
        $instruction->instruction = $request->instruction;
        $instruction->review_id = $review_id;
        $instruction->save();

        }
        if($request->drug_name[0]!=null){

        $drugs=array();
        $drugsName=array();

        $arrayLength = count($request->drug_name);

            for ($i = 0; $i < $arrayLength; $i++) {
                $check=DrugContent::where('marketName','like','%'.$request->drug_name[$i].'%')->first();
                if($check==null)
                {
                    return $this->returnError('#E100',' اسم الدواء غير صحيح'.$request->drug_name[$i]);
                    
                }
                array_push($drugs,$check->docid);
                array_push($drugsName,$check->marketName);

            }
        if($request->check=='yes'){
            for ($i = 0; $i < $arrayLength; $i++) {
                $Prescription = new Prescription();
                $Prescription->drug_id = $drugs[$i];
                $Prescription->drug_name = $drugsName[$i];
                $Prescription->Description =  $request->Description[$i];
                $Prescription->EndDateUse =  $request->EndDateUse[$i];
                $Prescription->permanent = $request->permanent[$i];
                $Prescription->review_id = $review_id;
                $Prescription->save();

            }
        }
       
    }
    if($request->date!=null){
        $next_review_date = new ReviewDate();
        $next_review_date->date = $request->date;
        $next_review_date->time = $request->time;
        $next_review_date->review_id = $review_id;
        $next_review_date->save();
        }
        if($request->type!=null){

    $madical_test = new MadicalTest();
    $madical_test->type = $request->type;
    $madical_test->spec = $request->spec;
    $madical_test->description = $request->descriptionTest;
    $madical_test->review_id = $review_id;
    $madical_test->save();
        }
    }
}
