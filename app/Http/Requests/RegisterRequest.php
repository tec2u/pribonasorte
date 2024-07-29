<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'zip' => ['required'],
            'address' => ['required'],
            'number' => ['required'],
            'city' => ['required'],
            // 'state'         => ['required'],
            'password' => ['required', 'min:8', 'max:16'],
            'checkpass' => ['required', 'min:8', 'max:16']
        ];
    }

    public function messages()
    {
        return [

            'name.required' => 'The name is mandatory submission!',
            'name.string' => 'The name cannot contain numbers!',
            'last_name.required' => 'The last name is mandatory submission!',
            'last_name.string' => 'The last name cannot contain numbers!',
            'phone.required' => 'The telephone number is mandatory!',
            'email.required' => 'The user is mandatory to fill in!',
            'email.email' => 'User needs to be a valid email!',
            'password.required' => 'Password is mandatory!',
            'password.min' => 'The password must be at least :min characters long!',
            'password.max' => 'The password must have a maximum of :max characters!',
            'checkpass.required' => 'Confirm your password!',
            'checkpass.min' => 'The password must be at least :min characters long!',
            'checkpass.max' => 'The password must have a maximum of :max characters!',
        ];
    }
}
