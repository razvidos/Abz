<?php

namespace App\Http\Resources;

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class UserCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = null;

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toArray($request): Response
    {
        $response = [
            "success" => true,
            "count" => $request->get('count', UserController::getDefaultPageCount()),
            'users' => $this->collection,
        ];
        return response($response);
    }
}
