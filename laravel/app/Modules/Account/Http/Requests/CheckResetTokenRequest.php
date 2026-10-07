<?php

namespace App\Modules\Account\Http\Requests;

use App\Modules\Base\BaseRequest;

class CheckResetTokenRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'token' => 'required|string|size:6',
        ];
    }

    public function attributeNames()
    {
        return [
            'email' => 'e-mail',
            'token' => 'code',
        ];
    }

    public function messages()
    {
        return [
            'token.size' => 'THE CODE MUST CONTAIN 6 DIGITS.',
        ];
    }
}
