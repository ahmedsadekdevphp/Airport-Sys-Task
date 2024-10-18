<?php
require_once '../core/ResponseCode.php'; 

class Response
{
    public static function jsonResponse(array $responseArray)
    {
        header('Content-Type: application/json');

        // Set default values
        $statusCode = $responseArray['status'] ?? HTTP_OK;
        http_response_code($statusCode);
        
        // Prepare response
        $response = [
            'status' => $statusCode,
        ];

        // Add data if provided
        if (isset($responseArray['data'])) {
            $response['data'] = $responseArray['data'];
        }

        // Add message if provided
        if (isset($responseArray['message'])) {
            $response['message'] = $responseArray['message'];
        }

        echo json_encode($response);
        exit;
    }
}
