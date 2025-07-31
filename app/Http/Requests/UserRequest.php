<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $user = $this->route('user');

        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.($user ? $user->id : null),
            'description' => 'required|string',
            'password' => 'required_if:password,!=,null|min:6'
        ];
    }

    // mensagem personalizada
    
    // public function messages(): array
    // {
    //     return [
    //         'name.required' => "Campo nome é obrigatório.",
    //         'email.required' => "Campo e-mail é obrigatório.",
    //         'email.email' => "Necessário um e-mail válido.",
    //         'email.unique' => "E-mail já cadastrado.",
    //         'password.required_if' => "Campo senha é obrigatório.",
    //         'password.min' => "Campo senha deve ter :min ou mais caracteres."
    //     ];
    // }
}
