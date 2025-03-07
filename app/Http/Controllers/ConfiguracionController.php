<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;

class ConfiguracionController extends Controller
{
    public function changeSettingsVariables(Request $request)
    {
        $validatedData = $request->validate([
            'keys' => 'required|array',
            'values' => 'required|array',
            'keys.*' => 'required|string',
            'values.*' => 'required|string',
            'originConfig' => 'nullable|string',
        ]);

        // Iterar sobre los valores enviados
        foreach ($validatedData['keys'] as $index => $key) {
            $value = $validatedData['values'][$index];

            // Actualizar o crear la configuración en la BD
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);

            // Si cambia el dominio de correo, actualizar los emails
            if ($key === 'emailDomain') {
                $this->updateUserEmails($value);
            }
        }

        switch ($validatedData['originConfig']) {
            case "originProfileOwn":
                return redirect()->route('usuarios.create')->with('successDominioCorreoUpdate', 'Dominio de correo actualizado correctamente.');
            default:
                return redirect()->route('configuracion.create')->with('success', 'Configuraciones actualizadas correctamente.');
        }
    }

    private function updateUserEmails(string $newDomain)
    {
        User::all()->each(function ($user) use ($newDomain) {
            $username = strstr($user->email, '@', true); // Obtener parte antes del @
            $user->update(['email' => $username . '@' . $newDomain]);
        });
    }

}
