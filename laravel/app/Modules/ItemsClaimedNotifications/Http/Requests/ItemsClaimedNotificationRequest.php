<?php

namespace App\Modules\ItemsClaimedNotifications\Http\Requests;

use App\Modules\Base\BaseRequest;

class ItemsClaimedNotificationRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'category'    => ['required', 'in:eletronicos,documentos,vestuario,outros'],
            'status'      => ['required', 'in:perdido,encontrado,devolvido'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function attributeNames()
    {
        return [
            'title'       => 'Título do ItemsClaimedNotification',
            'description' => 'Descrição',
            'category'    => 'Categoria',
            'status'      => 'Status',
            'image'       => 'Foto do ItemsClaimedNotification',
        ];
    }

    public function messages()
    {
        return [
            'title.required'       => 'O título do itemsClaimedNotification é obrigatório.',
            'title.max'            => 'O título não pode ter mais de 150 caracteres.',
            'description.required' => 'A descrição é obrigatória.',
            'category.required'    => 'Selecione uma categoria válida.',
            'category.in'          => 'A categoria selecionada é inválida.',
            'status.required'      => 'Selecione o tipo do itemsClaimedNotification.',
            'status.in'            => 'O status selecionado é inválido.',
            'image.image'          => 'O arquivo enviado deve ser uma imagem.',
            'image.mimes'          => 'A imagem deve estar no formato JPG, PNG ou WEBP.',
            'image.max'            => 'A imagem não pode ultrapassar 2MB.',
        ];
    }
}