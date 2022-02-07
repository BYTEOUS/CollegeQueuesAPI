<?php

class Request {
    public static function begin(array $middlewares = []) {
        // Middlewares
        foreach($middlewares as &$middleware) {
            call_user_func($middleware, getallheaders());
        }
    
        // Handle request
        header("Access-Control-Allow-Origin: http://authentication-jwt/");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, PATCH");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }
    public static function getAuthHeader() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } 
        else if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }

        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public static function getFields() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        if($method == 'GET')
            return $_GET;
        else
            return json_decode(file_get_contents('php://input'), true);
    }
    public static function requireFields(array $fields) {
        $empty = [];
        $fieldsInRequest = Request::getFields();
        
        if(!isset($fieldsInRequest)) {
            Request::finishErrorServer('Invalid fields');
            return false;
        }

        // Check fields
        foreach($fields as &$fieldName) {
            if(!isset($fieldsInRequest[$fieldName])) {
                $empty[$fieldName] = 'Field ' . $fieldName . ' can not be blank';
            }
        }

        // Error if some fields is empty
        if(count($empty) > 0) {
            Request::finishErrorValidation($empty);
            return false;
        }
        else { return true; }
    }

    public static function requireMode(array $modesId){
        $auth = Request::getAuthHeader();

        if($auth) {
            $decoded = json_decode(Auth::decodeJwt($auth), true);
            
            $allowed = false;
            foreach($rolesId as $roleId) {
                if($decoded && $decoded['role'] == strval($roleId)) {
                    $allowed = true;
                }
            }

            if($allowed) {
                return true;
            }
            else {
                Request::finishErrorForbidden();
                return false;
            }
        }
        else {
            Request::finishErrorUnauthorized();
            return false;
        }
    }
    
    public static function finishSuccess(array $result, int $code = 200) {
        Request::result($result, $code);
    }
    public static function finishSuccessData(array $data, int $code = 200) {
        Request::result(array(
            'data' => $data
        ), $code);
    }

    public static function finishError(string $message, int $code) {
        Request::result(array(
            'error' => array(
                'code' => $code,
                'message' => $message
            )
        ), $code);
    }
    public static function finishErrorUnauthorized() {
        Request::finishError('Login failed', 403);
    }
    public static function finishErrorForbidden() {
        Request::finishError('Forbidden for you', 403);
    }
    public static function finishErrorValidation(array $fieldsErrors) {
        Request::result(array(
            'error' => array(
                'code' => 422,
                'message' => 'Validation error',
                'errors' => $fieldsErrors
            )
        ), 422);
    }
    public static function finishErrorServer($message) {
        Request::finishError('Server error: ' . $message, 500);
    }

    private static function result(array $json, int $code = 200) {
        http_response_code($code);
        echo json_encode($json);
    }
} 