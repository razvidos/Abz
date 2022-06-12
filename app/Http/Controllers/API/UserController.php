<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator as ValidatorObject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tinify as Tinify;

class UserController extends Controller
{
    protected static string $default_PhotoPath = '/avatars/1/';
    protected static int $default_page_count = 5;
    protected bool $isFail = false;
    protected ValidatorObject $validator;

    /**
     * @return int
     */
    public static function getDefaultPageCount(): int
    {
        return self::$default_page_count;
    }

    /**
     * @return string
     */
    private function getPhotoPath(): string
    {
        return self::$default_PhotoPath;
    }

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

        return $this->myPaginator($users_table, $parameters);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request): Response
    {
        $response = $this->storeValidated($request);
        if ($this->isFail) {
            return $response;
        }

        $created_user = User::create($this->validator->validated());
        setcookie('registration_token', '', 0, '/api/users');

        $file_name_format = "user-photo-%s.%s";
        $original_extension = $request->file('photo')->getClientOriginalExtension();
        $file_name = sprintf(
            $file_name_format,
            $created_user->id,
            $original_extension
        );
        $path_to_photo = null;
        try {
            Tinify\setKey(env("TINIFY_API_KEY"));
            Tinify\validate();

            $source = Tinify\fromBuffer($request->file('photo')->get());
            $resized = $source->resize(array(
                "method" => "cover",
                "width" => 70,
                "height" => 70
            ));
            if (Storage::put('public' . $this->getPhotoPath() . $file_name, $resized->toBuffer())) {
                $path_to_photo = 'storage' . $this->getPhotoPath() . $file_name;
            }
        } catch (Tinify\Exception $e) {
            if ($request->file('photo')->storePubliclyAs('public' . $this->getPhotoPath(), $file_name)) {
                $path_to_photo = 'storage' . $this->getPhotoPath() . $file_name;
            }
        }
        if ($path_to_photo) {
            $created_user->photo = $path_to_photo;
            $created_user->save();
        }

        $response = [
            'success' => true,
            'user_id' => $created_user->id,
            'message' => __('api.User.message.200')
        ];
        return response($response);
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
            $message = __('api.Validation.failed');
            $fails = ["user_id" => __('api.User.fails.user_id_IsNotInteger')];
            $statusCode = 400;

        } else {
            $user = new UserResource(User::find($id));

            if (!$user->resource) {
                $message = __('api.User.message.404');
                $fails = ["user_id" => __('api.User.fails.404')];
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

    protected function ensureTokenIsValid(Request $request): Response
    {
        $response["success"] = false;
        $this->isFail = false;
        if ($request->isMethod('get')) {
            return response([]);
        } elseif ($request->isMethod('post')) {
            if (Cookie::has('registration_token')) {
                return response([]);
            } else {
                $status = 401;
                $response['message'] = __('api.User.message.401');
            }
        } else {
            $status = 405;
        }
        $this->isFail = true;
        return response($response, $status);
    }

    /**
     * @param Request $request
     * @return Response
     */
    protected function storeValidated(Request $request): Response
    {
        $response = $this->ensureTokenIsValid($request);
        if ($this->isFail) {
            return $response;
        }

        $rules = [
            "name" => 'required|min:2|max:60',
            "email" => 'required|unique:users,email|email',
            "phone" => 'required|unique:users,phone|regex:^[\+]{0,1}380([0-9]{9})^',
            "photo" => "required|file|max:5242880|mimes:jpg,jpeg|dimensions:width_min=70,height_min=70",
            "position_id" => 'required|integer|nullable|exists:user_positions,id',
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
                $response["message"] = __('api.User.validation.unUniqueEmailOrPhone');
                $this->isFail = true;
                return response($response, 409);
            }

            $errors_messages = $this->validator->errors()->getMessages();

            $response["message"] = __('api.Validation.failed');
            $response["fails"] = $errors_messages;

            $this->isFail = true;
            return response($response, 422);
        }

        return response($response, 500);
    }

    /**
     * @param Request $request
     * @return array|Response
     */
    protected function indexValidated(Request $request)
    {
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

            $response["message"] = __('api.Validation.failed');
            $response["fails"] = $errors_messages;

            $this->isFail = true;
            return response($response, 422);
        }

        return [];
    }

    /**
     * @param $users_table
     * @param $parameters
     * @return Response
     */
    protected function myPaginator($users_table, $parameters): Response
    {
        $user_paginator = $users_table->paginate($parameters['count']);
        if(!isset($parameters['offset'])) {
            if(
                1 > $parameters['page']
                || $parameters['page'] > $user_paginator->lastPage()
            ) {
                $response = [
                    "success" => false,
                    "message" => __('api.Pagination.message.404')
                ];
                return response($response, 404);
            }
        }
        $users = $user_paginator->all();

        $paginator_range = $user_paginator->getUrlRange(
            $parameters['page'] - 1 ,
            $parameters['page'] + 1);
        $paginator_range[$user_paginator->lastPage() + 1] = null;
        $paginator_range[0] = null;
        $next_url = $paginator_range[$parameters['page'] + 1];
        $prev_url = $paginator_range[$parameters['page'] - 1];

        $response["success"] = true;
        $response["total_pages"] = $user_paginator->lastPage();
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
