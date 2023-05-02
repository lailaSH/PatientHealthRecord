<?php

namespace App\Http\Controllers\MadicalTestResult;

use App\Http\Controllers\BaseController;
use App\Http\Resources\MadicalTest as ResourcesMadicalTest;
use App\Models\ActicityLog;
use App\Models\File;
use App\Models\HealthRecrod;
use App\Models\MadicalTest;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\PatientPersonalInfo;

class RadiologicalTestResultController extends BaseController
{

    public function check_to_get_Rad(Request $request)
    {
        $paitent = PatientPersonalInfo::where('ID_number', $request->ID_number)->first();
        if ($paitent == null) {
            return $this->sendError('there is no such ID', null);
        } else {
            $healthrecord = $paitent->HealthRecord;
            $reviews = Review::where('health_record_id', $healthrecord->id)
                ->take(10)
                ->get();
            $tests = array();
            foreach ($reviews as $review) {
                foreach ($review->MadicalTest as $madicaltest)
                    if ($madicaltest->executor == null && $madicaltest->spec == "أشعة")
                        $tests[] = $madicaltest;
            }
            return $this->sendResponse(ResourcesMadicalTest::collection($tests), 'success');
        }
    }

    public function update_madical_test_info_Rad(Request $request)
    {
        $executor_id = Auth::user()->id;
        $madical_test = DB::table('madical_tests')->where(
            'id',
            $request->madical_test_id
        )->update([
            "executor" => $request->executor,
            "number" => $request->number,
            "date" => $request->date,
            "description" => $request->description,
            'executor_id' => $executor_id,
        ]);
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,bmp,png,doc,docx,csv,rtf,xlsx,xls,txt,pdf,zip,rar',
        ]);
        $new_file = new File();
        $name = time() * rand(1, 100) . $request->file->getClientOriginalName();
        $new_file->name = $name;
        // $FILE = $request->file;
        // Storage::disk('local')->put($name, $FILE);
        // $new_file->path = "storage/app/public/MadicalTests/";
        $new_file->path = $request->file->storeAs('public/MadicalTests', $name);
        $new_file->size = 10;
        $new_file->madical_test_id = $request->madical_test_id;
        $new_file->notes = $request->notes;
        $new_file->save();
        //////////for log
        $madical_test = MadicalTest::find($request->madical_test_id);
        $activity = new ActicityLog();
        $activity->health_record_id =  $madical_test->Review->HealthRecord->id;
        $activity->operation_type = "إضافة";
        $activity->first_name = $madical_test->executor;
        $activity->description = "تم تحميل نتيجة الاختبار الطبي";
        $activity->save();


        return $this->sendResponse(['madical_test_id' => $request->madical_test_id], 'success');
    }
    public function store_doc_Rad(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,bmp,png,doc,docx,csv,rtf,xlsx,xls,txt,pdf,zip,rar',
        ]);
        $new_file = new File();
        $name = time() * rand(1, 100) . $request->file->getClientOriginalName();
        $new_file->name = $name;
        // $FILE = $request->file;
        // Storage::disk('local')->put($name, $FILE);
        // $new_file->path = "storage/app/public/MadicalTests/";
        $new_file->path = $request->file->storeAs('public/MadicalTests', $name);
        $new_file->size = 10;
        $new_file->madical_test_id = $request->madical_test_id;
        $new_file->notes = $request->notes;
        $new_file->save();
        //////////for log
        $madical_test = MadicalTest::find($request->madical_test_id);
        $activity = new ActicityLog();
        $activity->health_record_id =  $madical_test->Review->HealthRecord->id;
        $activity->operation_type = "إضافة";
        $activity->first_name = $madical_test->executor;
        $activity->description = "تم تحميل نتيجة الاختبار الطبي";
        $activity->save();

        return $this->sendResponse(['madical_test_id' => $request->madical_test_id], 'success');
    }
}
