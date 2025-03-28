<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(request()->routeIs('api.user.register') && request()->method() === 'POST'){
            return [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:3|max:20'
            ];
        }
        if(request()->routeIs('api.user.login') && request()->method() === 'POST'){
            return [
                'email' => 'required|email',
                'password' => 'required|string|min:3|max:20'
            ];
        }
    }
}
