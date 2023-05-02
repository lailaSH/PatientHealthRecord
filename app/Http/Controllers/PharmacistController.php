<?php

namespace App\Http\Controllers;

use App\Models\PatientPersonalInfo;
use App\Models\Pharmacist;
use App\Models\PharmacistRequest;
use App\Models\Prescription;
use App\Models\Review;
use App\Models\UsersLogin;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class PharmacistController extends Controller
{
    use GeneralTrait;
    public function Send_request(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'FirstName' => 'required',
                'FatherName' => 'required',
                'LastName' => 'required',
                'DateOfBirth' => 'required',
                'city' => 'required',
                'type' => 'required',
                'CertificateImage' => 'required',
                'ipersonal_identification_img' => 'required'
            ]
        );

        if ($validator->fails()) {
            return $this->returnError('401', $validator->errors());
        }
        $UserRequest = new PharmacistRequest();
        $UserRequest->FirstName = $request->FirstName;
        $UserRequest->FatherName = $request->FatherName;
        $UserRequest->LastName = $request->LastName;
        $UserRequest->DateOfBirth = $request->DateOfBirth;
        $UserRequest->city = $request->city;
        $UserRequest->region = $request->region;
        $UserRequest->phoneNumber = $request->phoneNumber;
        $UserRequest->email = $request->email;
        $UserRequest->type = $request->type;

        $email=UsersLogin::where('email',$request->email)->first();
        if($email!=null)
        return $this->returnError('#E100',' البريد الالكتروني مستخدم مسبقا');
        
        if($request->has('CertificateImage')){

            $CertificateImage = $request->CertificateImage;
            $CertificateImageName = time() . $request-> FirstName. $CertificateImage->getClientOriginalName();
            $CertificateImage->move('Userimages/CertificateImage/', $CertificateImageName);
            $UserRequest->CertificateImage = 'Userimages/CertificateImage/' . $CertificateImageName;
            }else{
                return $this->returnError('#E100',' الرجاء ادخال صورة الشهادة الجامعية');
            }
    
            if($request->has('ipersonal_identification_img')){
    
            $ipersonal_identification_img = $request->ipersonal_identification_img;
            $ipersonal_identification_img_name = time() . $request-> FirstName. $ipersonal_identification_img->getClientOriginalName();
            $ipersonal_identification_img->move('Userimages/ipersonal_identification_img/', $ipersonal_identification_img_name);
            $UserRequest->ipersonal_identification_img = 'Userimages/ipersonal_identification_img/' . $ipersonal_identification_img_name;
            }else{
                return $this->returnError('#E100',' الرجاء ادخال صورة الهوية الشخصية');
            }
            if($request->has('VacuumCard')){
            $VacuumCard = $request->VacuumCard;
            $VacuumCard_name = time() . $request-> FirstName. $VacuumCard->getClientOriginalName();
            $VacuumCard->move('Userimages/VacuumCard/', $VacuumCard_name);
            $UserRequest->VacuumCard = 'Userimages/VacuumCard/' . $VacuumCard_name;
            }else{
                return $this->returnError('#E100',' الرجاء ادخال صورة بطاقة النقابة');
            }
    
            if($request->has('ApprovalOpenClinic')){
            $ApprovalOpenClinic = $request->ApprovalOpenClinic;
            $ApprovalOpenClinic_name = time() . $request-> FirstName. $ApprovalOpenClinic->getClientOriginalName();
            $ApprovalOpenClinic->move('Userimages/ApprovalOpenClinic/', $ApprovalOpenClinic_name);
            $UserRequest->ApprovalOpenClinic = 'Userimages/ApprovalOpenClinic/' . $ApprovalOpenClinic_name;
            }else{
                return $this->returnError('#E100',' الرجاء ادخال صورة ترخيص صيدلية');
            }

        $UserRequest->save();
        return    $this->returnSuccessMessage('تم ارسال طلبك بنجاح');
    }
  

    public function profile(Request $request)
    {
        $auth_check = JWTAuth::parseToken()->authenticate();
        if ($auth_check) {
            $user = JWTAuth::authenticate($request->token);
            return $this->returnData('Pharmacist', $user);
        } else {

            return $this->returnError(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Sorry, token is an invalid'
            );
        }
    }
    public function UpdateProfile(Request $request)
    {
        $auth_check = JWTAuth::parseToken()->authenticate();
        if ($auth_check) {
            $user_id = JWTAuth::authenticate($request->token)->id;
            $user = Pharmacist::where(
                'id',
                $user_id
            )->update([
                "FirstName" => $request->FirstName,
                "LastName" => $request->FirstName,
                "FatherName" => $request->FirstName,
                "DateOfBirth" => $request->DateOfBirth,
                "city" => $request->city,
                "region" => $request->region,
                "phoneNumber" => $request->phoneNumber,
                "email" => $request->email,
                "password" => bcrypt($request->password),
            ]);

            return $this->returnSuccessMessage('تم التعديل بنجاح');
        } else {
            return $this->returnError(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Sorry, token is an invalid'
            );
        }
    }
    public function AllPhamacists()
    {    
        $phamacists=Pharmacist::all();
        return $this->returnData('Phamacists', $phamacists);
    }
    public function check_to_get_(Request $request)
    {
        $paitent = PatientPersonalInfo::where('ID_number', $request->ID_number)->first();
        if ($paitent == null) {
            return $this->sendError('there is no such ID', null);
        } else {
            $healthrecord = $paitent->HealthRecord;
            $reviews = Review::where('health_record_id', $healthrecord->id)->get();
            $prescriptionArray=array();
            foreach($reviews as $review){
            $prescriptions=Prescription::where('review_id',$review->id)->where('status','yes')->get();
            array_push($prescriptionArray,$prescriptions);
        }
        }
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => '',
            'prescriptions' => $prescriptions,
        ]); 
    }
    public function ShowPrescription(Request $request)
    {
       $paitent = PatientPersonalInfo::where('name', $request->name)
       ->where('family_name', $request->family_name)
       ->where('father_name', $request->father_name)->first();
       $health_record = HealthRecord::where('patient_personal_info_id', $paitent->id)->first();
       $health_record_id = $health_record->id;
        $reviews = Review::where('health_record_id', $health_record_id)->get();
        $prescriptionArray=array();
        foreach($reviews as $review){
        $prescriptions=Prescription::where('review_id',$review->id)->where('status','yes')->get();
        array_push($prescriptionArray,$prescriptions);
        }
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => '',
            'prescriptions' => $prescriptions,
        ]); 
    }
    public function sellPrescription(Request $request)
    {
        foreach($request->id as $id)
        $Prescription = Prescription::where(
            'id',
            $id
        )->update([
            "status" => 'no',
            ]);
        return $this->returnSuccessMessage('تم بيع الدواء بنجاح');
    }

}
