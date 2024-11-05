<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class genreController extends Controller
{
    // Método para consultar todos los Generos
    public function index()
    {
        $genres = Genre::all();

        // Verificar si no hay registros disponibles
        if ($genres->isEmpty()) {
            return $this->genreNotFound();
        }

        // Devolver la lista de registros en formato JSON
        return response()->json($genres, 200);
    }

    // Método para guardar un Genero
    public function store(Request $request)
    {

        // Validar los datos de la solicitud
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:250',
                'description' => 'required|string'
            ]
        );

        // Verificar si la validación falla
        if ($validator->fails()) {
            return $this->errorValidatingData($validator->errors());
        }

        // Crear el Genero con los datos validados
        $genre = Genre::create($request->all());

        // Verificar si la creación del Genero falla
        if (!$genre) {
            return response()->json(['message' => 'Error al crear el Genero', 'status' => 500], 500);
        }

        // Devolver respuesta de éxito
        return response()->json(['message' => 'Genero creado correctamente', 'status' => 201], 201);
    }

    // Método para obtener una entidad por su id
    public function getGenreById($id)
    {
        $genre = Genre::find($id);

        // Verificar si la entidad no existe
        if (!$genre) {
            return $this->genreNotFound();
        }

        // Devolver la entidad en formato JSON
        return response()->json($genre, 200);
    }

    // Método para eliminar un registro por su id
    public function deleteGenreById($id)
    {
        $genre = Genre::find($id);

        // Verificar si el registro no existe
        if (!$genre) {
            return $this->genreNotFound();
        }

        // Eliminar el registro
        $genre->delete();

        // Devolver respuesta de éxito
        return response()->json(['message' => 'Genero eliminado correctamente', 'status' => 200], 200);
    }

    // Método para actualizar un registro parcialmente por su id
    public function updateGenrePartialById(Request $request, $id)
    {
        $genre = Genre::find($id);

        // Verificar si el registro no existe
        if (!$genre) {
            return $this->genreNotFound();
        }

        // Validar los datos recibidos
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'string|max:250',
                'description' => 'string'
            ]
        );

        // Verificar si la validación falla
        if ($validator->fails()) {
            return $this->errorValidatingData($validator->errors());
        }

        // Actualizar los campos proporcionados
        if ($request->has('name')) {
            $genre->name = $request->name;
        }

        if ($request->has('description')) {
            $genre->description = $request->description;
        }

        // Guardar los cambios registrados
        $genre->save();

        // Devolver respuesta de éxito con los datos del registro actualizado
        $data = [
            'message' => 'Genero actualizado correctamente',
            'libro' => $genre,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    // Método privado para manejar registros no encontrados
    private function genreNotFound()
    {
        return response()->json(['message' => 'Genero no encontrado', 'status' => 404], 404);
    }

    // Método privado para manejar errores de validación de datos
    private function errorValidatingData($errors)
    {
        return response()->json(['message' => 'Error en la validación de datos', 'errors' => $errors, 'status' => 422], 422);
    }
}
