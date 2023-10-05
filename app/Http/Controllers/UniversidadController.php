<?php

namespace App\Http\Controllers;

use App\Models\Universidad;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Carrera;
class UniversidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recupera todas las universidades de la base de datos
        $universidades = Universidad::all();
    
        // Devuelve las universidades en formato JSON con un código de estado HTTP 200 (OK)
        return response()->json($universidades, 200);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Almacena una nueva universidad en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Definir las reglas de validación
        $rules = [
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string',
            'descripcion' => 'nullable|string',
        ];
    
        // 2. Personalizar los mensajes de error (opcional)
        $messages = [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de caracteres.',
            'nombre.max' => 'El campo nombre no debe superar los :max caracteres.',
            'direccion.required' => 'El campo dirección es obligatorio.',
            'direccion.string' => 'El campo dirección debe ser una cadena de caracteres.',
            'descripcion.string' => 'El campo descripción debe ser una cadena de caracteres.',
        ];
    
        // 3. Realizar la validación
        try {
            $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    
        // 4. Crear una nueva universidad
        $nuevaUniversidad = Universidad::create($request->all());
    
        // 5. Devolver una respuesta JSON con los datos de la nueva universidad y el código de estado HTTP 201 (Created)
        return response()->json($nuevaUniversidad, 201);
    }
    
    /**
     * Muestra los detalles de una universidad específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Buscar la universidad por su ID
        $universidad = Universidad::findOrFail($id);

        // Devolver los detalles de la universidad en formato JSON con un código de estado HTTP 200 (OK)
        return response()->json($universidad, 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Universidad $universidade)
    {
        //
    }


    // public function update(Request $request, $id)
    // {
    //     // 1. Definir las reglas de validación
    //     $rules = [
    //         'nombre' => 'required|string|max:100',
    //         'direccion' => 'required|string',
    //         'descripcion' => 'nullable|string',
    //     ];
    
    //     // 2. Personalizar los mensajes de error (opcional)
    //     $messages = [
            // 'nombre.required' => 'El campo nombre es obligatorio.',
            // 'nombre.string' => 'El campo nombre debe ser una cadena de caracteres.',
            // 'nombre.max' => 'El campo nombre no debe superar los :max caracteres.',
            // 'direccion.required' => 'El campo dirección es obligatorio.',
            // 'direccion.string' => 'El campo dirección debe ser una cadena de caracteres.',
            // 'descripcion.string' => 'El campo descripción debe ser una cadena de caracteres.',
    //     ];
    
    //     // 3. Realizar la validación
    //     try {
    //         $request->validate($rules, $messages);
    //     } catch (ValidationException $e) {
    //         return response()->json(['errors' => $e->errors()], 422);
    //     }
    
    //     // 4. Buscar la universidad por su ID
    //     $universidad = Universidad::findOrFail($id);
    
    //     // 5. Actualizar los detalles de la universidad con los datos de la solicitud
    //     $universidad->update($request->all());
    
    //     // 6. Devolver una respuesta JSON con los datos actualizados de la universidad y el código de estado HTTP 200 (OK)
    //     return response()->json($universidad, 200);
    // }
    

    public function destroy($id)
    {
        // 1. Buscar la universidad por su ID
        $universidad = Universidad::findOrFail($id);
    
        // 2. Desasociar todas las carreras de la universidad
        $universidad->carreras()->detach();
    
        // 3. Eliminar la universidad
        $universidad->delete();
    
        // 4. Devolver una respuesta JSON con el mensaje de éxito y el código de estado HTTP 204 (No Content)
        return response()->json(['message' => 'Universidad eliminada con éxito'], 204);
    }


    public function crearUniversidadYAsociarCarreras(Request $request)
    {
        // 1. Definir las reglas de validación
        $rules = [
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string',
            'descripcion' => 'nullable|string',
            'carreras_ids' => 'array', // Asegurarse de que se reciban como un arreglo
            'carreras_ids.*' => 'exists:carreras,id', // Asegurarse de que las carreras existan
        ];
    
        // 2. Personalizar los mensajes de error (opcional)
        $messages = [
            // Mensajes de validación aquí...
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de caracteres.',
            'nombre.max' => 'El campo nombre no debe superar los :max caracteres.',
            'direccion.required' => 'El campo dirección es obligatorio.',
            'direccion.string' => 'El campo dirección debe ser una cadena de caracteres.',
            'descripcion.string' => 'El campo descripción debe ser una cadena de caracteres.',
        ];
    
        // 3. Realizar la validación
        try {
            $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    
        // 4. Crear la universidad
        $universidad = Universidad::create($request->all());
    
        // 5. Asociar carreras a la universidad utilizando sync para evitar duplicados
        $carrerasIds = $request->input('carreras_ids');
        $universidad->carreras()->sync($carrerasIds);
    
        // 6. Devolver una respuesta JSON con los datos de la universidad y el código de estado HTTP 201 (Created)
        return response()->json($universidad, 201);
    }
    
    public function update(Request $request, $id)
    {
        // 1. Buscar la universidad por su ID
        $universidad = Universidad::findOrFail($id);

        // 2. Obtener las asociaciones actuales de carreras de la universidad y convertirlas a un arreglo
        $asociacionesActuales = $universidad->carreras->pluck('id')->toArray();

        // 3. Definir las reglas de validación para la edición de universidad
        $rules = [
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string',
            'descripcion' => 'nullable|string',
            'carreras_ids' => 'array', // Asegurarse de que se reciban como un arreglo
            'carreras_ids.*' => 'exists:carreras,id', // Asegurarse de que las carreras existan
        ];

        // 4. Personalizar los mensajes de error (opcional)
        $messages = [
            // Mensajes de validación aquí...
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de caracteres.',
            'nombre.max' => 'El campo nombre no debe superar los :max caracteres.',
            'direccion.required' => 'El campo dirección es obligatorio.',
            'direccion.string' => 'El campo dirección debe ser una cadena de caracteres.',
            'descripcion.string' => 'El campo descripción debe ser una cadena de caracteres.',
        ];

        // 5. Realizar la validación
        try {
            $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // 6. Actualizar los atributos de la universidad
        $universidad->update($request->all());

        // 7. Obtener las nuevas asociaciones de carreras desde la solicitud
        $nuevasAsociaciones = $request->input('carreras_ids', []);

        // 8. Calcular las asociaciones que se deben agregar y quitar
        $asociacionesAgregar = array_diff($nuevasAsociaciones, $asociacionesActuales);
        $asociacionesQuitar = array_diff($asociacionesActuales, $nuevasAsociaciones);

        // 9. Sincronizar las asociaciones para agregar y quitar
        if (!empty($asociacionesAgregar)) {
            $universidad->carreras()->attach($asociacionesAgregar);
        }
        
        if (!empty($asociacionesQuitar)) {
            $universidad->carreras()->detach($asociacionesQuitar);
        }

        // 10. Devolver una respuesta JSON con los datos actualizados de la universidad y el código de estado HTTP 200 (OK)
        return response()->json($universidad, 200);
    }


    public function listarUniversidadesConCarreras()
    {
        // Obtener todas las universidades con sus carreras asociadas
        $universidadesConCarreras = Universidad::with('carreras')->get();
    
        // Devolver una respuesta JSON con las universidades y sus carreras
        return response()->json($universidadesConCarreras, 200);
    }
}
