<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\UserRequest;
use App\Models\UsersLogin;

class UserController extends Controller
{
    use GeneralTrait;

    public function profile(Request $request)
    {
        $auth_check = JWTAuth::parseToken()->authenticate();
        if ($auth_check) {
            $user = JWTAuth::authenticate($request->token);
            return $this->returnData('user', $user);
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
            $user = DB::table('users')->where(
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
    ////new
    public function Send_request(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [ 
        'FirstName' => 'required',
        'FatherName' => 'required',
        'LastName' => 'required',
        'DateOfBirth' => 'required',
        'city' => 'required',
        'type'=>'required',
        'CertificateImage'=>'required',
        'ipersonal_identification_img'=>'required'
        ]); 

        if ($validator->fails()) {  
            return $this->returnError('401',$validator->errors());
        }
        $UserRequest = new UserRequest();
        $UserRequest->FirstName = $request->FirstName;
        $UserRequest->FatherName = $request->FatherName;
        $UserRequest->LastName = $request->LastName;
        $UserRequest->DateOfBirth = $request->DateOfBirth;
        $UserRequest->city = $request->city;
        $UserRequest->region = $request->region;
        $UserRequest->phoneNumber = $request->phoneNumber;
        $UserRequest->email = $request->email;
        $UserRequest->type = $request->type;
        $UserRequest->specialty = $request->specialty;
        
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

        if($request->has('CertificateSpecialty')){
        $CertificateSpecialty = $request->CertificateSpecialty;
        $CertificateSpecialty_name = time() . $request-> FirstName. $CertificateSpecialty->getClientOriginalName();
        $CertificateSpecialty->move('Userimages/CertificateSpecialty/', $CertificateSpecialty_name);
        $UserRequest->CertificateSpecialty = 'Userimages/CertificateSpecialty/' . $CertificateSpecialty_name;
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
            return $this->returnError('#E100',' الرجاء ادخال صورة ترخيص العيادة');
        }

        
        if($request->CertificateSpecialty==null && $request->specialty!=null && $request->type=='طبيب'){
            return $this->returnError('#100','يرجى ادخال شهادة الاختصاص');
        }
    
        $UserRequest->save();
        return    $this->returnSuccessMessage('تم ارسال طلبك بنجاح');
    }
}
