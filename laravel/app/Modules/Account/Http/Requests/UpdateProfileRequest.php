<?php

namespace App\Modules\Account\Http\Requests;

use App\Modules\Base\BaseRequest;
use Auth;
use Illuminate\Validation\Rules\Password;

/**
 * Validação do próprio perfil. O id vem SEMPRE do token (Auth::id()),
 * nunca da requisição — o usuário só consegue alterar a si mesmo.
 */
class UpdateProfileRequest extends BaseRequest
{
    public function authorize()
    {
        return !empty($this->user());
    }

    public function rules()
    {
        $id = Auth::id();

        return [
            'name'     => 'required|max:200',
            'username' => "required|regex:/^[a-z0-9]*$/|unique:users,username,{$id},id,deleted_at,NULL|max:100",
            'email'    => "required|unique:users,email,{$id},id,deleted_at,NULL|email|max:255",
            'password' => ['confirmed', 'max:30', ($this->password || $this->password_confirmation) ? Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised() : 'nullable'],
        ];
    }

    public function attributeNames()
    {
        return [
            'name'     => 'nome',
            'username' => 'usuário',
            'email'    => 'e-mail',
            'password' => 'senha',
        ];
    }

    public function messages()
    {
        return [
            'password.min'       => 'A senha deve ter pelo menos :min caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.max'       => 'A senha não pode ter mais que :max caracteres.',
        ];
    }
}
