<?php

namespace App\Http\Controllers;

use App\Http\Resources\prequest as Resourcesprequest;
use Illuminate\Support\Facades\Validator;
use App\Models\prequest;
use Illuminate\Http\Request;
use Notification;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTAuth as JWTAuthJWTAuth;

class PrequestController extends BaseController
{

    public function prequests_show()
    {
    
        $prequest = prequest::all();
        return $this->sendResponse(Resourcesprequest::collection($prequest), 'prequests retrieved Successfully!');
        }
        


    public function create()
    {
        //
    }


    public function store_request(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            ''

        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $prequest = new prequest();
        $prequest->name = $request->name;
        $prequest->father_name = $request->father_name;
        $prequest->family_name = $request->family_name;
        $prequest->email = $request->email;
        $prequest->gender = $request->gender;
        $prequest->date_of_birth = $request->date_of_birth;
        $prequest->city = $request->address;
        $prequest->phone_number = $request->phone_number;
        $prequest->phone_number2 = $request->phone_number2;
        $prequest->ID_number = $request->ID_number;
        ///////////////////////

        $ipersonal_identification_img = $request->ipersonal_identification_img;
        $ipersonal_identification_img_name = time() . $request-> name. $ipersonal_identification_img->getClientOriginalName();
        $ipersonal_identification_img->move('PIimages/', $ipersonal_identification_img_name);
        $prequest->ipersonal_identification_img = 'PIimages/' . $ipersonal_identification_img_name;
    

        ////////////////////////////////////////////////////////////////////
        $prequest->family_health_history = $request->family_health_history;

        $prequest->save();
        return $this->sendResponse(new Resourcesprequest($prequest), 'patient request created successfully');
    }


    public function show(prequest $prequest)
    {
        //
    }

    public function update(Request $request, prequest $prequest)
    {
        //
    }

    public function destroy_prequest($prequest_id)
    {
        $prequest = prequest::find($prequest_id);
        $prequest->delete();
        return $this->sendResponse(null, 'patient request deleted successfully');
    }
}
