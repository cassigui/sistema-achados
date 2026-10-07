<?php

namespace App\Modules\Account\Http\Requests;

use App\Modules\Base\BaseRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'                 => 'required|email|max:255',
            'token'                 => 'required|string|size:6',
            'password'              => ['required', 'confirmed', 'max:30', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'password_confirmation' => 'required|same:password|min:6|max:30',
        ];
    }

    public function attributeNames()
    {
        return [
            'email'                 => 'E-mail',
            'token'                 => 'Código',
            'password'              => 'Senha',
            'password_confirmation'  => 'Confirmação de senha',
        ];
    }

    public function messages()
    {
        return [
            'token.size' => 'Code min 6 digits.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.max' => 'A senha não pode ter mais que :max caracteres.',
            'password_confirmation.required' => 'A confirmação da senha é obrigatória.',
            'password_confirmation.same' => 'A confirmação da senha deve ser igual à senha.',
            'password_confirmation.min' => 'A confirmação da senha deve ter pelo menos :min caracteres.',
            'password_confirmation.max' => 'A confirmação da senha não pode ter mais que :max caracteres.',
        ];
    }
}
