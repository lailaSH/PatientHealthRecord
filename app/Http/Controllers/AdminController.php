<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ApproveEmail;
use App\Models\additionalDoctorInfo;
use App\Models\Hospital;
use App\Models\HospitalRequest;
use App\Models\Lab;
use App\Models\LabRequest;
use App\Models\Pharmacist;
use App\Models\PharmacistRequest;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\UserRequest;
use App\Models\UsersLogin;
use App\Models\XrayCenter;
use App\Models\XrayCenterRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    use GeneralTrait;
    public function UpdateProfile(Request $request)
    { 
        $auth_check = JWTAuth::parseToken()->authenticate();
        if($auth_check){
            $admin_id = JWTAuth::authenticate($request->token)->id;
            $admin= DB::table('admins')->where(
                'id',
                $admin_id
            )->update([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                ]);
                return $this->returnSuccessMessage('تم التعديل بنجاح');

            }
		else{
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR,
            'Sorry, token is an invalid');
            } 
    }
    public function ShowUserRequest($requestId)
    {
        
        $UserRequest = UserRequest::find($requestId);
        if($UserRequest!=null){
            return $this->returnData('User Request',$UserRequest);

        }else{
            return $this->returnError('E001','هذا الطلب غير موجود');
        }
    }
    public function ShowLabRequest($requestId)
    {
        
        $LabRequest = LabRequest::find($requestId);
        if($LabRequest!=null){
            return $this->returnData('User Request',$LabRequest);

        }else{
            return $this->returnError('E001','هذا الطلب غير موجود');
        }
    }
    public function ShowXrayRequest($requestId)
    {
        
        $XrayCenterRequest = XrayCenterRequest::find($requestId);
        if($XrayCenterRequest!=null){
            return $this->returnData('User Request',$XrayCenterRequest);

        }else{
            return $this->returnError('E001','هذا الطلب غير موجود');
        }
    }
    public function ShowAllRequests()
    {
        
        $UserRequests = UserRequest::all();
        $LabRequests= LabRequest::all();
        $XrayCenterRequests =XrayCenterRequest::all();
        $HospitalRequests=HospitalRequest::all();
        if($XrayCenterRequests==null && $LabRequests==null && $UserRequests==null&& $HospitalRequests==null)
        return $this->returnError('E001','لايوجد طلبات للعرض');
        
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => '',
            'DoctorRequests' => $UserRequests,
            'LabRequests'=>$LabRequests,
            'XrayCenterRequests'=>$XrayCenterRequests,
            'HospitalRequests'=>$HospitalRequests,
        ]);
        

    }
    public function ApproveUserRequest($requestId)
{
    $UserRequest = UserRequest::find($requestId);
    $user=new User();
    $user->FirstName = $UserRequest->FirstName;
    $user->FatherName = $UserRequest->FatherName;
    $user->LastName = $UserRequest->LastName;
    $user->DateOfBirth = $UserRequest->DateOfBirth;
    $user->city = $UserRequest->city;
    $user->region = $UserRequest->region;
    $user->phoneNumber = $UserRequest->phoneNumber;
    $user->email = $UserRequest->email;
    $user->type = $UserRequest->type;
    $password='123456';
    $user->password =bcrypt($password);
    $user->CertificateImage = $UserRequest->CertificateImage;
    $user->ipersonal_identification_img = $UserRequest->ipersonal_identification_img;
    $user->save();
    $user_id=User::all()->last();
    if($UserRequest->type=='Doctor'){
            
        $additionalDoctorInfo=new additionalDoctorInfo();
        $additionalDoctorInfo->idUser=$user_id->id;
        $additionalDoctorInfo->specialty=$UserRequest->specialty;
        $additionalDoctorInfo->CertificateSpecialty=$UserRequest->CertificateSpecialty;
        $additionalDoctorInfo->VacuumCard=$UserRequest->VacuumCard;
        $additionalDoctorInfo->ApprovalOpenClinic=$UserRequest->specApprovalOpenClinicialty;
        $additionalDoctorInfo->save();
        }
        $UserLogin=new UsersLogin();
        $UserLogin->UserID=$user_id->id;
        $UserLogin->email=$UserRequest->email;
        $UserLogin->Type='Doctor';
        $UserLogin->save();
            $UserRequest->delete();
        //   Mail::to($UserRequest->email)->send(new ApproveEmail);
            return      $this->returnSuccessMessage('تم الموافقة على طلب الاشتراك ');
}
public function ApproveLabRequest($requestId)
{
    $UserRequest = LabRequest::find($requestId);
    if($UserRequest!=null){
    $user=new Lab();
    $user->FirstName = $UserRequest->FirstName;
    $user->FatherName = $UserRequest->FatherName;
    $user->LastName = $UserRequest->LastName;
    $user->DateOfBirth = $UserRequest->DateOfBirth;
    $user->city = $UserRequest->city;
    $user->region = $UserRequest->region;
    $user->phoneNumber = $UserRequest->phoneNumber;
    $user->email = $UserRequest->email;
    $user->type = $UserRequest->type;
    $password='123456';
    $user->password =bcrypt($password);
    $user->CertificateImage = $UserRequest->CertificateImage;
    $user->ipersonal_identification_img = $UserRequest->ipersonal_identification_img;
    $user->specialty=$UserRequest->specialty;
    $user->CertificateSpecialty=$UserRequest->CertificateSpecialty;
    $user->VacuumCard=$UserRequest->VacuumCard;
    $user->ApprovalOpenClinic=$UserRequest->ApprovalOpenClinic;
    $user->save();
    $user_id=Lab::all()->last();
    $UserLogin=new UsersLogin();
    $UserLogin->UserID=$user_id->id;
    $UserLogin->email=$UserRequest->email;
    $UserLogin->Type='Lab';
    $UserLogin->save();
    $UserRequest->delete();
    //   Mail::to($UserRequest->email)->send(new ApproveEmail);
    return      $this->returnSuccessMessage('تم الموافقة على طلب الاشتراك ');
    }else
    return $this->returnError('#E300','هذا الطلب غير موجود');
}
public function ApproveXrayRequest($requestId)
{
    $UserRequest = XrayCenterRequest::find($requestId);
    if($UserRequest!=null){

    $user=new XrayCenter();
    $user->FirstName = $UserRequest->FirstName;
    $user->FatherName = $UserRequest->FatherName;
    $user->LastName = $UserRequest->LastName;
    $user->DateOfBirth = $UserRequest->DateOfBirth;
    $user->city = $UserRequest->city;
    $user->region = $UserRequest->region;
    $user->phoneNumber = $UserRequest->phoneNumber;
    $user->email = $UserRequest->email;
    $user->type = $UserRequest->type;
    $password='123456';
    $user->password =bcrypt($password);
    $user->CertificateImage = $UserRequest->CertificateImage;
    $user->ipersonal_identification_img = $UserRequest->ipersonal_identification_img;
    $user->VacuumCard=$UserRequest->VacuumCard;
    $user->CertificateSpecialty=$UserRequest->CertificateSpecialty;
    $user->ApprovalOpenClinic=$UserRequest->ApprovalOpenClinic;
    $user->save();
    $user_id=XrayCenter::all()->last();
    $UserLogin=new UsersLogin();
    $UserLogin->UserID=$user_id->id;
    $UserLogin->email=$UserRequest->email;
    $UserLogin->Type='Xray';
    $UserLogin->save();
    $UserRequest->delete();
    //   Mail::to($UserRequest->email)->send(new ApproveEmail);
    return      $this->returnSuccessMessage('تم الموافقة على طلب الاشتراك ');
}else
return $this->returnError('#E300','هذا الطلب غير موجود');

}
public function ApproveHospitalRequest($requestId){
    $HospitalRequest = HospitalRequest::find($requestId);
        if($HospitalRequest!=null)
        {$Hospital=new Hospital();
        $Hospital->HospitalName=$HospitalRequest->HospitalName;
        $Hospital->OwnerName=$HospitalRequest->OwnerName;
        $Hospital->city=$HospitalRequest->city;
        $Hospital->region=$HospitalRequest->region;
        $Hospital->phoneNumber=$HospitalRequest->phoneNumber;
        $Hospital->telephone=$HospitalRequest->telephone;
        $Hospital->email=$HospitalRequest->email;
        $password='123456';
        $Hospital->password =bcrypt($password);
        $Hospital->ipersonal_identification_img = $HospitalRequest->ipersonal_identification_img;
        $Hospital->HospitalLicense = $HospitalRequest->HospitalLicense;
        $Hospital->save();
        $HospitalID=Hospital::all()->last();

        $UserLogin=new UsersLogin();
        $UserLogin->UserID=$HospitalID->id;
        $UserLogin->email= $HospitalRequest->email;
        $UserLogin->Type='Hospital';
        $UserLogin->save();
        return      $this->returnSuccessMessage('تم الموافقة على طلب الاشتراك ');
    }else
    return $this->returnError('#E300','هذا الطلب غير موجود');


}
public function ApprovePharmacistRequest($requestId)
{
    $UserRequest = PharmacistRequest::find($requestId);
    if($UserRequest){
    $user=new Pharmacist();
    $user->FirstName = $UserRequest->FirstName;
    $user->FatherName = $UserRequest->FatherName;
    $user->LastName = $UserRequest->LastName;
    $user->DateOfBirth = $UserRequest->DateOfBirth;
    $user->city = $UserRequest->city;
    $user->region = $UserRequest->region;
    $user->phoneNumber = $UserRequest->phoneNumber;
    $user->email = $UserRequest->email;
    $password='123456';
    $user->password =bcrypt($password);
    $user->CertificateImage = $UserRequest->CertificateImage;
    $user->ipersonal_identification_img = $UserRequest->ipersonal_identification_img;
    $user->VacuumCard=$UserRequest->VacuumCard;
    $user->ApprovalOpenClinic=$UserRequest->specApprovalOpenClinicialty;
    $user->save();
    $user_id=Pharmacist::all()->last();
    $UserLogin=new UsersLogin();
    $UserLogin->UserID=$user_id->id;
    $UserLogin->email=$UserRequest->email;
    $UserLogin->Type='Pharmacist';
    $UserLogin->save();
    $UserRequest->delete();
    //   Mail::to($UserRequest->email)->send(new ApproveEmail);
    return      $this->returnSuccessMessage('تم الموافقة على طلب الاشتراك ');
    }else
    return $this->returnError('#E300','هذا الطلب غير موجود');

}
public function DeleteUserRequest($requestId)
{
    $UserRequest = UserRequest::find($requestId);
    if($UserRequest!=null)
    {
        $UserRequest->delete();
        return      $this->returnSuccessMessage('تم رفض طلب الاشتراك ');

    }else
    {
        return      $this->returnError('E100','الطلب غير موجود');
    }
}
public function DeleteLabRequest($requestId)
{
    $LabRequest = LabRequest::find($requestId);
    if($LabRequest!=null)
    {
        $LabRequest->delete();
        return      $this->returnSuccessMessage('تم رفض طلب الاشتراك ');

    }else
    {
        return      $this->returnError('E100','الطلب غير موجود');
    }
}
public function DeleteXaryRequest($requestId)
{
    $XrayCenterRequest = XrayCenterRequest::find($requestId);
    if($XrayCenterRequest!=null)
    {
        $XrayCenterRequest->delete();
        return      $this->returnSuccessMessage('تم رفض طلب الاشتراك ');

    }else
    {
        return      $this->returnError('E100','الطلب غير موجود');
    }
}
}