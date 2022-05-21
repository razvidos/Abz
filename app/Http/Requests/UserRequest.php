<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        if ($request->isMethod('get')) {
            return true;
        } elseif ($request->isMethod('post')) {
            return Cookie::has('registration_token');
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => 'required|min:2|max:60',
            "email" => 'required|email',
            "phone" => 'required|regex:^[\+]{0,1}380([0-9]{9})^',
            "photo" => "required|file|max:5242880|mimes:jpg,jpeg|dimensions:width=70,height=70",
            "position_id" => 'required|integer|min:1',
        ];
    }
}
