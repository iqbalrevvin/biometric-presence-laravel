<?php
    namespace App\Helpers;

    class GlobalHelper {
        public static function createResponse($success, $message, $data=null, $additional_data=null){
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            if($data){
                $response += [
                    'output_data' => $data
                ];
            }
            if($additional_data){
                $response += [
                    'additional_data' => $additional_data
                ];
            }
            return response()->json($response);
        }
    }