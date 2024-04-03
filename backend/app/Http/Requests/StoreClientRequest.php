<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    // Determine se o usuário está autorizado a fazer esta requisição.
    public function authorize()
    {
        return true; // Alterar conforme a lógica de autorização necessária.
    }

    // Regras de validação para criar um novo cliente.
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
             'photo' => 'required|image',
        ];
    }
}
