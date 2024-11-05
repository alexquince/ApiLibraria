<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class authorController extends Controller
{
     // Método para consultar todos los Autores
     public function index()
     {
         $authors = Author::all();
 
         // Verificar si no hay autores disponibles
         if ($authors->isEmpty()) {
             return $this->authorNotFound();
         }
 
         // Devolver la lista de Autores en formato JSON
         return response()->json($authors, 200);
     }
 
     // Método para guardar un Autor
     public function store(Request $request)
     {

         // Validar los datos de la solicitud
         $validator = Validator::make(
             $request->all(),
             [
                 'name' => 'required|string|max:250',
                 'biography' => 'required|string',
                 'nationality' => 'required|string|max:150'
             ]
         );
 
         // Verificar si la validación falla
         if ($validator->fails()) {
             return $this->errorValidatingData($validator->errors());
         }
 
         // Crear el Autor con los datos validados
         $author = Author::create($request->all());
 
         // Verificar si la creación del Autor falla
         if (!$author) {
             return response()->json(['message' => 'Error al crear el Autor', 'status' => 500], 500);
         }
 
         // Devolver respuesta de éxito
         return response()->json(['message' => 'Autor creado correctamente', 'status' => 201], 201);
     }
 
     // Método para obtener una entidad por su id
     public function getAuthorById($id)
     {
         $author = Author::find($id);
 
         // Verificar si la entidad no existe
         if (!$author) {
             return $this->authorNotFound();
         }
 
         // Devolver la entidad en formato JSON
         return response()->json($author, 200);
     }
 
     // Método para eliminar un registro por su id
     public function deleteAuthorById($id)
     {
         $author = Author::find($id);
 
         // Verificar si el registro no existe
         if (!$author) {
             return $this->authorNotFound();
         }
 
         // Eliminar el registro
         $author->delete();
 
         // Devolver respuesta de éxito
         return response()->json(['message' => 'Autor eliminado correctamente', 'status' => 200], 200);
     }
 
     // Método para actualizar un registro parcialmente por su id
     public function updateAuthorPartialById(Request $request, $id)
     {
         $author = Author::find($id);
 
         // Verificar si el registro no existe
         if (!$author) {
             return $this->authorNotFound();
         }
 
         // Validar los datos recibidos
         $validator = Validator::make(
             $request->all(),
             [
                'name' => 'string|max:250',
                'biography' => 'string',
                'nationality' => 'string|max:150'
             ]
         );
 
         // Verificar si la validación falla
         if ($validator->fails()) {
             return $this->errorValidatingData($validator->errors());
         }
 
         // Actualizar los campos proporcionados
         if ($request->has('name')) {
             $author->name = $request->name;
         }
 
         if ($request->has('biography')) {
             $author->biography = $request->biography;
         }
 
         if ($request->has('nationality')) {
             $author->nationality = $request->nationality;
         }
         
         // Guardar los cambios registrados
         $author->save();
 
         // Devolver respuesta de éxito con los datos del registro actualizado
         $data = [
             'message' => 'Autor actualizado correctamente',
             'libro' => $author,
             'status' => 200
         ];
         return response()->json($data, 200);
     }
 
     // Método privado para manejar registros no encontrados
     private function authorNotFound()
     {
         return response()->json(['message' => 'Autor no encontrado', 'status' => 404], 404);
     }
 
     // Método privado para manejar errores de validación de datos
     private function errorValidatingData($errors)
     {
         return response()->json(['message' => 'Error en la validación de datos', 'errors' => $errors, 'status' => 422], 422);
     }
}
