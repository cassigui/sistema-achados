<?php

namespace App\Modules\Comments\Http\Requests;

use App\Modules\Base\BaseRequest;

class CommentRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    public function attributeNames()
    {
        return [
            'content' => 'Comentário',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'Escreva uma mensagem antes de enviar.',
            'content.min'      => 'O comentário deve ter pelo menos 3 caracteres.',
            'content.max'      => 'O comentário não pode ter mais de 1000 caracteres.',
        ];
    }
}