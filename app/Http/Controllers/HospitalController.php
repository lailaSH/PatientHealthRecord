<?php

namespace App\Http\Controllers;

use App\Models\additionalDoctorInfo;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\HospitalDoctor;
use App\Models\HospitalRequest;
use App\Models\User;
use App\Models\UsersLogin;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class HospitalController extends Controller
{
    use GeneralTrait;

    public function SendHospitalRequest(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [ 
        'HospitalName'=>'required',
        'OwnerName' => 'required',
        'city' => 'required',
        'region' => 'required',
        'phoneNumber' => 'required',
        'telephone' => 'required',
        'email'=>'required',
        'ipersonal_identification_img'=>'required',
        'HospitalLicense'=>'required'

        ]);  

        if ($validator->fails()) {  
            return $this->returnError('401',$validator->errors());
        }
        $Hospital=new HospitalRequest();
        $Hospital->HospitalName=$request->HospitalName;
        $Hospital->OwnerName=$request->OwnerName;
        $Hospital->city=$request->city;
        $Hospital->region=$request->region;
        $Hospital->phoneNumber=$request->phoneNumber;
        $Hospital->telephone=$request->telephone;
        $Hospital->email=$request->email;

        $email=UsersLogin::where('email',$request->email)->first();
        if($email!=null)
        return $this->returnError('#E100',' البريد الالكتروني مستخدم مسبقا');
        

        $ipersonal_identification_img = $request->ipersonal_identification_img;
        $ipersonal_identification_img_name = time() . $request-> FirstName. $ipersonal_identification_img->getClientOriginalName();
        $ipersonal_identification_img->move('Hospitalimages/ipersonal_identification_img/', $ipersonal_identification_img_name);
        $Hospital->ipersonal_identification_img = 'Hospitalimages/ipersonal_identification_img/' . $ipersonal_identification_img_name;
    
        $HospitalLicense = $request->HospitalLicense;
        $HospitalLicense_name = time() . $request-> FirstName. $HospitalLicense->getClientOriginalName();
        $HospitalLicense->move('Hospitalimages/HospitalLicense/', $HospitalLicense_name);
        $Hospital->HospitalLicense = 'Hospitalimages/HospitalLicense/' . $HospitalLicense_name;
        $Hospital->save();
        return    $this->returnSuccessMessage('تم ارسال طلبك بنجاح');

    }

    public function profile(Request $request)
    {
        $auth_check = JWTAuth::parseToken()->authenticate();
        if ($auth_check) {
            $user = JWTAuth::authenticate($request->token);    
            return $this->returnData('Lab', $user);
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
            $Hospital_id = JWTAuth::authenticate($request->token)->id;
            $Hospital = Hospital::where(
                'id',
                $Hospital_id
            )->update([
                "HospitalName" => $request->HospitalName,
                "OwnerName" => $request->OwnerName,
                "phoneNumber" => $request->phoneNumber,
                "telephone" => $request->telephone,
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

    public function addDoctor(Request $request)
    {
        $Hospital = Auth::user();
        $user=new User();
        $user->FirstName = $request->FirstName;
        $user->FatherName = $request->FatherName;
        $user->LastName = $request->LastName;
        $user->DateOfBirth = $request->DateOfBirth;
        $user->city = $request->city;
        $user->region = $request->region;
        $user->phoneNumber = $request->phoneNumber;
        $user->email = $request->email;
        $user->type = $request->type;
        $password='123456';
        $user->password =bcrypt($password);
      
        $ipersonal_identification_img = $request->ipersonal_identification_img;
        $ipersonal_identification_img_name = time() . $request-> FirstName. $ipersonal_identification_img->getClientOriginalName();
        $ipersonal_identification_img->move('Userimages/ipersonal_identification_img/', $ipersonal_identification_img_name);
        $user->ipersonal_identification_img = 'Userimages/ipersonal_identification_img/' . $ipersonal_identification_img_name;
       
        $CertificateImage = $request->CertificateImage;
        $CertificateImage_name = time() . $request-> FirstName. $CertificateImage->getClientOriginalName();
        $CertificateImage->move('Userimages/CertificateImage/', $CertificateImage_name);
        $user->CertificateImage = 'Userimages/CertificateImage/' . $CertificateImage_name;
        $user->save();
        
        $user_id=User::all()->last();
        if($request->type=='Doctor'){
                
            $additionalDoctorInfo=new additionalDoctorInfo();
            $additionalDoctorInfo->idUser=$user_id->id;
            $additionalDoctorInfo->specialty=$request->specialty;
            $additionalDoctorInfo->CertificateSpecialty=$request->CertificateSpecialty;
            $additionalDoctorInfo->save();
        }
        $HospitalDoctor=new HospitalDoctor();
        $HospitalDoctor->DoctorID=$user_id->id;
        $HospitalDoctor->HospitalID=$Hospital->id;
        $HospitalDoctor->save();

        $UserLogin=new UsersLogin();
        $UserLogin->UserID=$user_id->id;
        $UserLogin->email= $request->email;
        $UserLogin->Type='Doctor';
        $UserLogin->save();

        return $this->returnSuccessMessage('تم اضافة الطبيب بنجاح');

    }
    public function ShowDoctors()
    {
        $Hospital = Auth::user();
        $HospitalDoctors=HospitalDoctor::where('HospitalID',$Hospital->id)->get();
        $AllDoctor=array();
        
        if(count($HospitalDoctors)!=0){
        foreach($HospitalDoctors as $HospitalDoctor)
        {
        $Doctors=User::where('id',$HospitalDoctor->DoctorID)->first();
        array_push($AllDoctor,$Doctors);
        }
        return $this->returnData('Doctors',$AllDoctor);
     }else
     return $this->returnError('E100','لا يوجد اطباء للعرض');
    }
    public function ShowDoctor($id)
    {
        $Hospital = Auth::user();
        $HospitalDoctors=HospitalDoctor::where('HospitalID',$Hospital->id)->where('DoctorID',$id)->first();
        if($HospitalDoctors)
        {
            $Doctor=User::where('id',$id)->first();
        }else{
            return $this->returnError('E100','هذا ليس طبيب في المشفى');
        }
        return $this->returnData('Doctor',$Doctor);
    
    }
    public function DeleteDoctor($id)
    {
        $Hospital = Auth::user();
        $HospitalDoctors=HospitalDoctor::where('HospitalID',$Hospital->id)->where('DoctorID',$id)->first();
        if($HospitalDoctors)
        {
            $Doctor=User::where('id',$id)->first();
            $HospitalDoctors->delete();
            $Doctor->delete();
        }
        return $this->returnSuccessMessage('تم حذف الطبيب من سجلات الشفى');
    
    }


}
