<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class UserPositionController
{
    /**
     * @return View
     */
    public function index(): View
    {
        $response = (new Api\UserPositionController)->index();
        $content = json_decode($response->getContent());
        $positions = $content->positions;

        return view('user_positions', compact(['positions']));
    }
}
