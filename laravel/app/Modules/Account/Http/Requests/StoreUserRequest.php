<?php

namespace App\Modules\Account\Http\Requests;

use App\Modules\Base\BaseRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends BaseRequest
{
    public function authorize()
    {
        return !empty($this->user());
    }

    public function rules()
    {

        $id = $this->segment(3);
        return [
            'name'            => 'required|max:200',
            'access_level_id' => 'required|numeric',
            'email'           => "required|unique:users,email,{$id},id,deleted_at,NULL|email|max:255",
            'username'        => "required|regex:/^[a-z0-9]*$/|unique:users,username,{$id},id,deleted_at,NULL|max:100",
            'password'        => ['required', 'confirmed', 'max:30', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'active'          => 'required|boolean',
        ];
    }

    public function attributeNames()
    {
        return [
            'access_level_id' => 'Nível de acesso',
            'username'        => 'Usuário',
        ];
    }

    public function messages()
    {
        return [
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.max' => 'A senha não pode ter mais que :max caracteres.',
        ];
    }
}
