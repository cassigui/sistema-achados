<?php
namespace App\Modules\Account\Http\Requests;

use App\Modules\Base\BaseRequest;

class LogInRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function attributeNames(): array
    {
        return [
            'email'    => 'E-mail',
            'password' => 'Senha',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Informe o seu e-mail.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'Informe a sua senha.',
        ];
    }
}
