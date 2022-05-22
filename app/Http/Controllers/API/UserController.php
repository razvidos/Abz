<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator as ValidatorObject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected static int $default_page_count = 5;
    protected bool $isFail = false;
    protected ValidatorObject $validator;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        $response = $this->indexValidated($request);
        if ($this->isFail) {
            return $response;
        }

        $parameters = $this->validator->validated();
        $users_table = DB::table('users')->orderBy('id');

        if (isset($parameters['offset'])) {
            $users = $users_table
                ->offset($parameters['offset'])
                ->limit($parameters['count'])
                ->get();
            $response = response(new UserCollection($users));
        } else {
            $response = $this->myPaginator($users_table, $parameters);
        }

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function store(Request $request): array
    {
        $response = $this->storeValidated($request);
        if ($this->isFail) {
            return $response;
        }

        $created_user = User::create($this->validator->validated());
        setcookie('registration_token', '', 0, '/api/users');

        $file_name_format = "user-photo-%s.%s";
        $file_name = sprintf(
            $file_name_format,
            $created_user->id,
            $request->file('photo')->getClientOriginalExtension()
        );
        $request->file('photo')->storeAs('avatars/1', $file_name);


        return [
            'success' => true,
            'user_id' => $created_user->id,
            'message' => "New user successfully registered"
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param int|string $id
     * @return Response
     */
    public function show($id): Response
    {
        if (!is_numeric($id)) {
            $message = "Validation failed";
            $fails = ["user_id" => "The user_id must be an integer."];
            $statusCode = 400;

        } else {
            $user = new UserResource(User::find($id));

            if (!$user->resource) {
                $message = "The user with the requested identifier does not exist";
                $fails = ["user_id" => "User not found"];
                $statusCode = 404;
            } else {
                $response = [
                    "success" => true,
                    "user" => $user
                ];
                return response($response);
            }
        }
        $response = [
            "success" => false,
            "message" => $message,
            "fails" => $fails
        ];
        return response($response, $statusCode);
    }

    protected function ensureTokenIsValid(Request $request)
    {
        $response["status"] = false;
        $this->isFail = false;
        if ($request->isMethod('get')) {
            return [];
        } elseif ($request->isMethod('post')) {
            if (Cookie::has('registration_token')) {
                return [];
            } else {
                $status = 401;
                $response['message'] = 'The token expired.';
            }
        } else {
            $status = 405;
        }
        $this->isFail = true;
        return response($response, $status);
    }

    protected function storeValidated(Request $request)
    {

        $response = $this->ensureTokenIsValid($request);
        if ($this->isFail) {
            return $response;
        }

        $rules = [
            "name" => 'required|min:2|max:60',
            "email" => 'required|unique:users,email|email',
            "phone" => 'required|unique:users,phone|regex:^[\+]{0,1}380([0-9]{9})^',
            "photo" => "required|file|max:5242880|mimes:jpg,jpeg|dimensions:width=70,height=70",
            "position_id" => 'required|integer|min:1',
        ];
        $messages = [
            'unique' => 'unique',
        ];

        $this->validator = Validator::make($request->all(), $rules, $messages);

        if ($this->validator->fails()) {
            $response = ["success" => false];
            if ($this->validator->errors()->hasAny(['email', 'phone'])
                && in_array(
                    'unique', [
                        $this->validator->errors()->first('email'),
                        $this->validator->errors()->first('phone')
                    ]
                )) {
                $response["message"] = "User with this phone or email already exist";
                $this->isFail = true;
                return $response;
            }

            $errors_messages = $this->validator->errors()->getMessages();

            $response["message"] = "Validation failed";
            $response["fails"] = $errors_messages;

            return $response;
        }

        return [];
    }

    protected function indexValidated(Request $request)
    {

        $response = $this->ensureTokenIsValid($request);
        if ($this->isFail) {
            return $response;
        }

        $rules = [
            "page" => 'required|min:1',
            "offset" => 'integer|min:0',
            "count" => 'integer|min:1|max:100',
        ];
        $messages = [
        ];

        $parameters = $request->all();
        $parameters['page'] = $request->get('page');
        $parameters['count'] = $request->get('count', self::$default_page_count);

        $this->validator = Validator::make($parameters, $rules, $messages);

        if ($this->validator->fails()) {
            $response = ["success" => false];

            $errors_messages = $this->validator->errors()->getMessages();

            $response["message"] = "Validation failed";
            $response["fails"] = $errors_messages;

            return $response;
        }

        return [];
    }

    /**
     * @return int
     */
    public static function getDefaultPageCount(): int
    {
        return self::$default_page_count;
    }

    protected function myPaginator($users_table, $parameters): Response
    {
        $user_paginator = $users_table->paginate($parameters['count']);
        $users = $user_paginator->all();

        $paginator_range = $user_paginator->getUrlRange(
            $parameters['page'] - 1 ,
            $parameters['page'] + 1);
        $paginator_range[$user_paginator->lastPage() + 1] = null;
        $paginator_range[0] = null;
        $next_url = $paginator_range[$parameters['page'] + 1];
        $prev_url = $paginator_range[$parameters['page'] - 1];

        $response["success"] = true;
        $response["total_pages"] = count($users);
        $response["total_users"] = $user_paginator->total();
        $response["count"] = $parameters['count'];
        $response["page"] = $parameters['page'];
        $response["links"] =
            [
                'next_url' => $next_url ? $next_url . "&count=" . $parameters['count'] : null,
                'prev_url' => $prev_url ? $prev_url . "&count=" . $parameters['count'] : null,

            ];
        $response["user_paginator"] = $user_paginator;
        $response["users"] = $users;
        return response($response);
    }
}
