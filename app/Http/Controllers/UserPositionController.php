<?php

namespace App\Http\Controllers;

use App\Models\UserPosition;
use Illuminate\Contracts\View\View;

class UserPositionController
{
    /**
     * @return View
     */
    public function index(): View
    {
        $positions = UserPosition::all();

        return view('user_positions', compact(['positions']));
    }
}
