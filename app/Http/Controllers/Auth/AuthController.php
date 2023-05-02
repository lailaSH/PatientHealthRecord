<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\UsersLogin;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;



class AuthController extends Controller
{
    use GeneralTrait;
    //Done
    public function registerUser(Request $request)
    {
 
        $validator = Validator::make($request->all(), 
        [ 
        'id'=>'required|unique:users',
        'fullName' => 'required',
        'DateOfBirth' => 'required',
        'city' => 'required',
        'region' => 'required',
        'password'=>'required|min:8',
        'type'=>'required',
        ]);  

        if ($validator->fails()) {  
            return $this->returnError('401',$validator->errors());
        }
        $user = new User();
        $user->id = $request->id;
        $user->fullName = $request->fullName;
        $user->DateOfBirth = $request->DateOfBirth;
        $user->city = $request->city;
        $user->region = $request->region;
        $user->phoneNumber = $request->phoneNumber;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->type = $request->type;
        $user->save();
        return $this->returnData('user',$user);
    }
    //Done
    public function login(Request $request)
    {
        //validation
        $user=UsersLogin::where('email',$request->email)->first();
        if($user->Type=='admin'){
            $rules= [
                'email' => 'required|exists:admins,email',
                'password' => 'required'
            ];
            $validator=Validator::make($request->all(),$rules);
            $data=$request->only(['email','password']);
            $token= Auth::guard('admin')->attempt($data);
            if(!$token)
            {
                return $this->returnError(Response::HTTP_UNAUTHORIZED,'Invalid  Password');

            }
            $admin= Auth::guard('admin')->user();
            $admin ->type='admin';
            $admin ->api_token=$token;
            return $this->returnData('user',$admin);
            }
            if($user->Type=='Doctor')
            {
                //validation
                $rules= [
                    'email' => 'required|exists:users,email',
                    'password' => 'required'
                ];
                $validator=Validator::make($request->all(),$rules);
                if($validator->fails()){
                    return $this->returnError('401',$validator->errors());
                }
                $data=$request->only(['email','password']);
                $token= Auth::guard('user')->attempt($data);
                if(!$token)
                {
                    return $this->returnError(Response::HTTP_UNAUTHORIZED,'Invalid  Password');
                }
                $user= Auth::guard('user')->user();
                $user ->api_token=$token;
                return $this->returnData('user',$user);   
            }
            if($user->Type=='Lab')
            {
                //validation
                $rules= [
                    'email' => 'required|exists:labs,email',
                    'password' => 'required'
                ];
                $validator=Validator::make($request->all(),$rules);
                if($validator->fails()){
                    return $this->returnError('401',$validator->errors());
                }
                $data=$request->only(['email','password']);
                $token= Auth::guard('lab')->attempt($data);
                if(!$token)
                {
                    return $this->returnError(Response::HTTP_UNAUTHORIZED,'Invalid  Password');
                }
                $user= Auth::guard('lab')->user();
                $user ->api_token=$token;
                return $this->returnData('user',$user);   
            }
            if($user->Type=='Xray')
            {
                //validation
                $rules= [
                    'email' => 'required|exists:xray_centers,email',
                    'password' => 'required'
                ];
                $validator=Validator::make($request->all(),$rules);
                if($validator->fails()){
                    return $this->returnError('401',$validator->errors());
                }
                $data=$request->only(['email','password']);
                $token= Auth::guard('xray_center')->attempt($data);
                if(!$token)
                {
                    return $this->returnError(Response::HTTP_UNAUTHORIZED,'Invalid  Password');
                }
                $user= Auth::guard('xray_center')->user();
                $user ->api_token=$token;
                return $this->returnData('user',$user);   
            }
            if($user->Type=='Hospital')
            {
                //validation
                $rules= [
                    'email' => 'required|exists:hospitals,email',
                    'password' => 'required'
                ];
                $validator=Validator::make($request->all(),$rules);
                if($validator->fails()){
                    return $this->returnError('401',$validator->errors());
                }
                $data=$request->only(['email','password']);
                $token= Auth::guard('hospital')->attempt($data);
                if(!$token)
                {
                    return $this->returnError(Response::HTTP_UNAUTHORIZED,'Invalid  Password');
                }
                $user= Auth::guard('hospital')->user();
                $user ->api_token=$token;
                return $this->returnData('hospital',$user);   
           
            }
            if($user->Type=='Pharmacist')
            {
                //validation
                $rules= [
                    'email' => 'required|exists:pharmacists,email',
                    'password' => 'required'
                ];
                $validator=Validator::make($request->all(),$rules);
                if($validator->fails()){
                    return $this->returnError('401',$validator->errors());
                }
                $data=$request->only(['email','password']);
                $token= Auth::guard('pharmacist')->attempt($data);
                if(!$token)
                {
                    return $this->returnError(Response::HTTP_UNAUTHORIZED,'Invalid  Password');
                }
                $user= Auth::guard('pharmacist')->user();
                $user ->api_token=$token;
                return $this->returnData('pharmacist',$user);   
           
            }
    }

    //Done
    public function logout(Request $request)
    {
        $token= $request->token;
        if($token)
        {        JWTAuth::setToken($token)->invalidate();
        
            return $this->returnSuccessMessage('User logged out successfully');   
        }
        else
        {      
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR,'Sorry, the user cannot be logged out');
        }
    }
    public function profile(Request $request)
    { 
        $auth_check = JWTAuth::parseToken()->authenticate();
        if($auth_check){
			$user = JWTAuth::authenticate($request->token);
            return $this->returnData('user',$user);   
		}else{
            return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR,
            'Sorry, token is an invalid');
            } 
    }
    }
