<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Response;

class TokenController extends Controller
{
    protected static int $minutes_to_expires = 40;

    public function getToken(): Response
    {
        try {
            $token = bin2hex(random_bytes(15));
            $path = '/api/users';

            $result["success"] = true;
            $result["registration_token"] = $token;
            return response($result)
                ->cookie(
                    'registration_token',
                    $token,
                    self::getMinutesToExpires(),
                    $path);

        } catch (Exception $e) {
            return response(["success" => false], 500);
        }
    }

    /**
     * @return int
     */
    public static function getMinutesToExpires(): int
    {
        return self::$minutes_to_expires;
    }
}
