<?php

namespace App\Http\Controllers\DoctorReview;

use App\Http\Controllers\BaseController;
use App\Http\Resources\File as ResourcesFile;
use App\Http\Resources\MadicalTest as ResourcesMadicalTest;
use App\Models\File;
use App\Models\HealthRecrod;
use App\Models\MadicalTest;
use App\Models\PatientDoctor;
use App\Models\Prescription;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\GeneralTrait;
use App\Traits\InteractionsDrugs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class DocController extends BaseController
{
    use GeneralTrait;
    public function store_madical_test(Request $request, $review_id)
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
        $madical_test = new MadicalTest();
        $madical_test->type = $request->type;
        $madical_test->spec = $request->spec;
        $madical_test->description = $request->description;
        $madical_test->review_id = $review_id;
        $madical_test->save();
        return $this->sendResponse(new ResourcesMadicalTest($madical_test), 'success');
    }

    public function show_madical_tests($review_id)
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
        $madical_tests = MadicalTest::where('review_id', $review_id)->get();
        return $this->sendResponse(ResourcesMadicalTest::collection($madical_tests), 'success');
    }

    public function show_madical_test_info($madical_test_id)
    {
        $madical_test = MadicalTest::find($madical_test_id);
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $madical_test->Review->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);
        //////////////////////////////////////
        return $this->sendResponse(new ResourcesMadicalTest($madical_test), 'success');
    }

    public function show_files($madical_test_id)
    {
        $files = File::where('madical_test_id', $madical_test_id)->get();
        
        return $this->sendResponse(ResourcesFile::collection($files), 'success');
    }
    public function download_test_result($file_id)
    {
        $file = File::find($file_id);
        return response()->download(storage_path("app/public/MadicalTests/{$file->name}"));
    }


    public function CreatePrescription(Request $request, $review_id)
    {
        $review = Review::find($review_id);

        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $review->HealthRecord->id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to make anychanges in this record', null);
            $arrayLength = count($request->drug_id);
        
            for ($i = 0; $i < $arrayLength; $i++) {
                $Prescription = new Prescription();
                $Prescription->drug_id = $request->drug_id[$i];
                $Prescription->Description =  $request->Description[$i];
                $Prescription->EndDateUse =  $request->EndDateUse[$i];
                $Prescription->permanent = $request->permanent[$i];
                $Prescription->review_id = $review_id;
                $Prescription->save();
            }
    }
    public function ShowAllPrescriptions($health_record_id)
    {
        $reviews = Review::where('health_record_id', $health_record_id)->get();
        $Prescriptions = array();

        foreach ($reviews as $review) {
            $Pre = Prescription::where('review_id', $review->id)->get();
            array_push($Prescriptions, $Pre);
        }
        return $this->returnData('Prescriptions', $Prescriptions);
    }
    public function ShowPrescription($review_id)
    {
        $Prescription = Prescription::where('review_id', $review_id)->get();
        
        return $this->returnData('Prescription', $Prescription);
    }

    public function UpdatePrescription(Request $request, $review_id)
    {
        $Pre = Prescription::where('review_id', $review_id)->get();
        $arrayLength = count($Pre);
        for ($i = 0; $i < $arrayLength; $i++) {
            $Prescription = DB::table('prescriptions')->where(
                'id',
                $Pre[$i]->id
            )->update([
                "Description" => $request->Description[$i],
                "EndDateUse" => $request->EndDateUse[$i],
                "permanent" => $request->permanent[$i],
            ]);
        }
        return $this->returnSuccessMessage('تم التعديل بنجاح');
    }

    public function CurrentDrugUsed($health_record_id)
    {
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
                    if($Prescription->permanent=='true')
                    {
                        array_push($drugs, $Prescription->drug_id);
                    }
            }
        }
        return $this->returnData('CurrentDrugUsed', $drugs);
    }
    public function DeletePrescription($Prescription_id)
    {
        $Prescription = DB::table('prescriptions')->where('id', $Prescription_id)->delete();
        return $this->returnSuccessMessage('تم الحذف بنجاح');
    }

}
