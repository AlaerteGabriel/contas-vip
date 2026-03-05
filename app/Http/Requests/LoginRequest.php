<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'login' => 'required|email',
            'pass' => 'required',
            'remember' => 'nullable|int'
        ];
    }

    public function messages() : array
    {
        return [
            'email.required' => 'Informe seu email de login!',
            'email.email' => 'Informe um email válido!',
            'pass.required' => 'Senha obrigatória!'
        ];
    }
}
