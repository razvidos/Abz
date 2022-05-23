<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserPosition;
use Illuminate\Http\Response;
use function response;

class UserPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $response = [
            "success" => true,
            "positions" => UserPosition::all(),
        ];
        return response($response);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $position = UserPosition::find($id);

        if (!$position) {
            $response = [
                "success" => false,
                "message" => "Positions not found"
            ];
            return response($response, 404);
        } else {
            $response = [
                "success" => true,
                "position" => $position
            ];
            return response($response);
        }
    }
}
