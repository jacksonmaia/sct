<?php

namespace App\api;

class ApiError
{
    public static function errorMessage($message, $code)
    {
        return [
            'msg' => $message, // Mesagem
            'code' => $code, //Codigos internos da API ou do HTTP
        ];

    }
}
