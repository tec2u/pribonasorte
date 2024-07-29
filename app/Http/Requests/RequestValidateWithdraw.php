<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestValidateWithdraw extends FormRequest
{
    public function rules()
    {
        return [
            'value'          => ['required'],
            'account_name'   => ['required', 'string'],
            'address'        => ['required'],
            'account_number' => ['required'],
            'bank_name'      => ['required'],
            'iban'           => ['required'],
            'swift'          => ['required'],
        ];
    }

    public function messages()
    {
        return [

            'value.required'           => 'The Value is mandatory submission!',
            'account_name.required'    => 'The Account Name is mandatory submission!',
            'account_name.string'      => 'The Account Name cannot contain numbers!',
            'address.required'         => 'The Address is mandatory submission!',
            'account_number.required'  => 'The Account Number is mandatory submission!',
            'bank_name.required'       => 'The Bank Name is mandatory submission!',
            'iban.required'            => 'The Iban Number is mandatory submission!',
            'swift.required'           => 'The Swift Number is mandatory submission!',
        ];
    }
}
