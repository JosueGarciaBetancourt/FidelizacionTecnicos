<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            /*'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 
                Rule::unique(User::class)->ignore($this->user()->id)],*/
            'password' => ['nullable', 'string', 'min:7'], 
            'idPerfilUsuario' => ['required', 'integer', 'exists:PerfilesUsuarios,idPerfilUsuario'], 
        ];
    }
}
