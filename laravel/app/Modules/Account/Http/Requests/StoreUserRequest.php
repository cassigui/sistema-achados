<?php
namespace App\Modules\Account\Http\Requests;

use App\Modules\Base\BaseRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => 'required|string|max:200',
            'email'    => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => [
                'required',
                'confirmed',
                'max:30',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ];
    }

    public function attributeNames()
    {
        return [
            'name'     => 'Nome completo',
            'email'    => 'E-mail',
            'password' => 'Senha',
        ];
    }

    public function messages()
    {
        return [
            'name.required'      => 'O campo nome é obrigatório.',
            'email.required'     => 'O campo e-mail é obrigatório.',
            'email.email'        => 'Informe um e-mail válido.',
            'email.unique'       => 'Este e-mail já está cadastrado.',
            'password.required'  => 'A senha é obrigatória.',
            'password.min'       => 'A senha deve ter pelo menos :min caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.max'       => 'A senha não pode ter mais de :max caracteres.',
        ];
    }
}
