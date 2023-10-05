<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CarreraController extends Controller
{
    /**
     * Muestra una lista de universidades.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carreras = Carrera::all();
        return response()->json($carreras, 200);
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
            'descripcion' => 'nullable|string',
            'anio' => 'required|integer',
        ];
    
        // 2. Personalizar los mensajes de error (opcional)
        $messages = [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de caracteres.',
            'nombre.max' => 'El campo nombre no debe superar los :max caracteres.',
            'descripcion.string' => 'El campo descripción debe ser una cadena de caracteres.',
            'anio.required' => 'El campo año es obligatorio.',
            'anio.integer' => 'El campo año debe ser un número entero.',
        ];
    
        // 3. Realizar la validación
        try {
            $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    
        // 4. Manejar la lógica para almacenar la Carrera
        $carrera = Carrera::create($request->all());
    
        // 5. Devolver una respuesta JSON con los datos de la Carrera y el código de estado HTTP 201 (Created)
        return response()->json($carrera, 201);
    }
    

    /**
     * Muestra los detalles de una universidad específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carrera = Carrera::where('id', $id)->firstOrFail();
        return response()->json($carrera, 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carrera $carrera)
    {
        //
    }

    /**
     * Actualiza una universidad específica en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 1. Definir las reglas de validación
        $rules = [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'anio' => 'required|integer',
        ];

        // 2. Personalizar los mensajes de error (opcional)
        $messages = [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de caracteres.',
            'nombre.max' => 'El campo nombre no debe superar los :max caracteres.',
            'descripcion.string' => 'El campo descripción debe ser una cadena de caracteres.',
            'anio.required' => 'El campo año es obligatorio.',
            'anio.integer' => 'El campo año debe ser un número entero.',
        ];

        // 3. Realizar la validación
        try {
            $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // 4. Encontrar y actualizar la Carrera
        $carrera = Carrera::findOrFail($id);
        $carrera->update($request->all());

        // 5. Devolver una respuesta JSON con los datos actualizados de la Carrera y el código de estado HTTP 200 (OK)
        return response()->json($carrera, 200);
    }

    

    /**
     * Elimina una universidad específica de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 1. Buscar la Carrera por su ID
        $carrera = Carrera::findOrFail($id);

        // 2. Eliminar la Carrera
        $carrera->delete();

        // 3. Devolver una respuesta JSON con el código de estado HTTP 204 (No Content)
        return response()->json(null, 204);
    }

}
