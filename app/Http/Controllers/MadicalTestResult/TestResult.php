<?php

namespace App\Http\Controllers\MadicalTestResult;

use App\Http\Controllers\BaseController;
use App\Http\Resources\MadicalTest as ResourcesMadicalTest;
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

class TestResult extends BaseController
{

    public function check_to_get_(Request $request)
    {
        $paitent = PatientPersonalInfo::where('ID_number', $request->ID_number)->first();
        if ($paitent == null) {
            return $this->sendError('there is no such ID', null);
        } else {
            $healthrecord = $paitent->HealthRecord;
            $reviews = Review::where('health_record_id', $healthrecord->id)
                ->take(5)
                ->get();
            $tests = array();
            foreach ($reviews as $review) {
                if ($review->MadicalTest->executor == null)
                    $tests[] = $review->MadicalTest;
            }
            return $this->sendResponse(ResourcesMadicalTest::collection($tests), 'success');
        }
    }

    public function update_madical_test_info(Request $request,  $madical_test_id)
    {
        $madical_test = DB::table('madical_tests')->where(
            'id',
            $madical_test_id
        )->update([
            "executor" => $request->executor,
            "number" => $request->number,
            "date" => $request->date,
            "description" => $request->description,
        ]);
        return $this->sendResponse(['madical_test_id' => $madical_test_id], 'success');
    }
    public function store_doc(Request $request, $madical_test_id)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,bmp,png,doc,docx,csv,rtf,xlsx,xls,txt,pdf,zip',
        ]);
        $new_file = new File();
        $FILE = $request->file;
        $name = time() * rand(1, 100) . $FILE->getClientOriginalName();
        $new_file->name = $name;
        $new_file->path = 'storage/MadicalDoc/' . $name;
        $FILE->move('storage/MadicalDoc/', $name);
        $new_file->size = 10;
        $new_file->madical_test_id = $madical_test_id;
        $new_file->save();
        return $this->sendResponse(null, 'success');
    }
}
