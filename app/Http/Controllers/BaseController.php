<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    ####### success ########
    public function sendResponse($result, $message)
    {
        //array with key and value
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        //return response with success (200)
        return response()->json($response, 200);
    }


    ######## error ########

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        //if error array not empty
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
