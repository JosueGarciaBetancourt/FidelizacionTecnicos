<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function changeGeneralVariables(Request $request)
    {
        $validatedData = $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        // Buscar la configuración en la base de datos
        $settingField = Setting::where('key', $validatedData['key'])->first();

        if (!$settingField) {
            return redirect()->back()->with('error', 'Configuración no encontrada.');
        }

        // Actualizar el valor de la configuración
        $settingField->update([
            'value' => $validatedData['value'],
        ]);

        // Si la clave es 'emailDomain', actualizar correos de los usuarios
        if ($validatedData['key'] === "emailDomain") {
            $newDomain = $validatedData['value'];
        
            User::query()->update([
                'email' => DB::raw("CONCAT(SUBSTRING_INDEX(email, '@', 1), '@" . addslashes($newDomain) . "')")
            ]);
        }

        return redirect()->route('usuarios.create')->with('successDominioCorreoUpdate', 'Dominio de correo guardado correctamente.');
    }
}
