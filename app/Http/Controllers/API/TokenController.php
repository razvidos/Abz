<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Cookie;

class TokenController extends Controller
{
    public function getToken(): array
    {
        try {
            $token = bin2hex(random_bytes(15));
            $expires = time() + 60 * 40;
            $path = '/api/users';
            setcookie('registration_token', $token, $expires, $path);

            $result["success"] = true;
            $result["registration_token"] = $token;

        } catch (Exception $e) {
            $result = ["success" => false];
        }
        return $result;
    }
}
