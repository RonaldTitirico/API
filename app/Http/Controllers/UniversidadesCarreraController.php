<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Universidad;
use App\Models\Carrera;
use App\Models\UniversidadesCarrera;
use Illuminate\Validation\ValidationException;
class UniversidadesCarreraController extends Controller
{
        public function listarCarrerasUniversidad($universidadId)
    {
        $universidad = Universidad::findOrFail($universidadId);

        // Obtener todas las carreras asociadas a la universidad
        $carreras = $universidad->carreras;

        return response()->json($carreras, 200);
    }

    public function agregarCarreraUniversidad($universidadId, $carreraId)
    {
        $universidad = Universidad::findOrFail($universidadId);
        $carrera = Carrera::findOrFail($carreraId);

        // Asociar la carrera a la universidad
        $universidad->carreras()->attach($carrera);

        return response()->json(['message' => 'Carrera agregada a la universidad'], 200);
    }

    public function eliminarCarreraUniversidad($universidadId, $carreraId)
    {
        $universidad = Universidad::findOrFail($universidadId);
        $carrera = Carrera::findOrFail($carreraId);

        // Desasociar la carrera de la universidad
        $universidad->carreras()->detach($carrera);

        return response()->json(['message' => 'Carrera eliminada de la universidad'], 200);
    }

    public function registrarRelacionUniversidadCarreras(Request $request, $universidadId)
    {
        // 1. Validar que el ID de la universidad exista
        $universidad = Universidad::find($universidadId);
    
        if (!$universidad) {
            return response()->json(['message' => 'La universidad con el ID especificado no existe.'], 404);
        }
    
        // 2. Obtener los IDs de las carreras a relacionar desde la solicitud
        $carrerasIds = $request->input('carreras_ids');
    
        // 3. Validar que al menos una carrera se proporcionó en la solicitud
        if (empty($carrerasIds)) {
            return response()->json(['message' => 'Debes proporcionar al menos una carrera para relacionar con la universidad.'], 422);
        }
    
        // 4. Registrar las relaciones en la tabla universidades_carreras
        foreach ($carrerasIds as $carreraId) {
            // Verificar si la relación ya existe para evitar duplicados
            if (!UniversidadesCarrera::where('id_universidad', $universidadId)->where('id_carrera', $carreraId)->exists()) {
                // Crear una nueva relación en la tabla universidades_carreras
                UniversidadesCarrera::create([
                    'id_universidad' => $universidadId,
                    'id_carrera' => $carreraId,
                ]);
            }
        }
    
        return response()->json(['message' => 'Relaciones entre universidad y carreras registradas exitosamente.'], 200);
    }
    public function desasociarRelacionUniversidadCarreras(Request $request, $universidadId)
    {
        // 1. Validar que el ID de la universidad exista
        $universidad = Universidad::find($universidadId);

        if (!$universidad) {
            return response()->json(['message' => 'La universidad con el ID especificado no existe.'], 404);
        }

        // 2. Obtener los IDs de las carreras a desasociar desde la solicitud
        $carrerasIds = $request->input('carreras_ids');

        // 3. Validar que al menos una carrera se proporcionó en la solicitud
        if (empty($carrerasIds)) {
            return response()->json(['message' => 'Debes proporcionar al menos una carrera para desasociar de la universidad.'], 422);
        }

        // 4. Desasociar las carreras de la universidad
        foreach ($carrerasIds as $carreraId) {
            // Verificar si la relación existe antes de intentar desasociarla
            if (UniversidadesCarrera::where('id_universidad', $universidadId)->where('id_carrera', $carreraId)->exists()) {
                // Desasociar la carrera de la universidad
                UniversidadesCarrera::where('id_universidad', $universidadId)->where('id_carrera', $carreraId)->delete();
            }
        }

        return response()->json(['message' => 'Carreras desasociadas de la universidad exitosamente.'], 200);
    }

}
