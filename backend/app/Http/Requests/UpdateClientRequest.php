<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize()
    {
        // Permitir a todos os usuários autenticados fazer atualizações
        // Modifique conforme necessário para sua lógica de autorização
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('clients')->ignore($this->client)
            ],
            'phone' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'photo' => 'sometimes|image',
        ];
    }
}
