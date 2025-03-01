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
         //dd($this->all());
     
         return [
            'id' => ['required', 'integer', 'exists:users,id'], 
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:100', Rule::unique('users', 'email')->ignore($this->id)], 
            'password' => ['nullable', 'string', 'min:7', 'max:20'], 
            'idPerfilUsuario' => ['required', 'integer', 'exists:PerfilesUsuarios,idPerfilUsuario'], 
            'DNI' => ['nullable', 'string', 'size:8', Rule::unique('users', 'DNI')->ignore($this->id)],
            'personalName' => ['nullable', 'string'], 
            'surname' => ['nullable', 'string'], 
            'fechaNacimiento' => ['nullable', 'date'], 
            'correoPersonal' => ['nullable', 'string', 'lowercase', 'email', 'max:100', Rule::unique('users', 'correoPersonal')->ignore($this->id)], 
            'celularPersonal' => ['nullable', 'string', 'regex:/^[0-9]{9}$/', Rule::unique('users', 'celularPersonal')->ignore($this->id)], 
            'celularCorporativo' => ['nullable', 'string', 'regex:/^[0-9]{9}$/'], 
         ];
     }
}
