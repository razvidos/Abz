<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
    protected static string $default_Photo = 'storage/avatars/1/user-photo-0.jpg';


    /**
     * @return string
     */
    private function getDefaultPhoto(): string
    {
        return self::$default_Photo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $response = (new API\UserController)->index($request);
        $content = json_decode($response->getContent());
        foreach ($content->users as $index => $user) {
            if (!$user->photo) {
                $content->users[$index]->photo = $this->getDefaultPhoto();
            }
        }
        return view('users.index', compact(['content']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $response = (new API\TokenController)->getToken();
        $cookies = $response->headers->getCookies();
        foreach ($cookies as $cookie) {
            Cookie::queue(
                $cookie->getName(),
                $cookie->getValue(),
                API\TokenController::getMinutesToExpires()
            );
        }
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return View|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = (new API\UserController)->store($request);
        $content = json_decode($response->getContent());
        if (!$content->success) {
            return $response;
        }
        return $this->show(User::find($content->user_id));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show(User $user): View
    {
        if (!$user->photo) {
            $user->photo = $this->getDefaultPhoto();
        }
        return view('users.show', compact('user'));
    }
}
