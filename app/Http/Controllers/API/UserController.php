<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return UserResource
     */
    public function store(UserRequest $request)
    {
        $created_user = User::create($request->validated());
        $file_name_format = "user-photo-%s.%s";
        $file_name = sprintf(
            $file_name_format,
            $created_user->id,
            $request->file('photo')->getClientOriginalExtension()
        );
        $path = $request->file('photo')->storeAs('avatars/1', $file_name);
        setcookie('registration_token', '', 0, '/api/users');

        return new UserResource($created_user);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return UserResource
     */
    public function show($id)
    {
        return new UserResource(User::findOrFail($id));
    }
}
