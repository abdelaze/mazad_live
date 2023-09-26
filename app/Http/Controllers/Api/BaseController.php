<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

class BaseController extends Controller {
    public function sendResponse( $result, $message ) {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json( $response, 200 );
    }

    public function sendError( $error, $errorMessages = [], $code = 200 ) {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        $response[ 'data' ] = $errorMessages;

        return response()->json( $response, $code );
    }
}
