<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    // Método para consultar todos los libros
    public function index()
    {
        $libros = Book::all();

        // Verificar si no hay libros disponibles
        if ($libros->isEmpty()) {
            return $this->bookNotFound();
        }

        // Devolver la lista de libros en formato JSON
        return response()->json($libros, 200);
    }

    // Método para guardar un libro
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:250',
                'ISBN' => 'required|string|unique:books',
                'editorial' => 'required|string',
                'authorId' => 'required|exists:authors,id',
                'genreId' => 'required|exists:genres,id',
                'userCreatedId' => 'required|exists:users,id'
            ]
        );

        // Verificar si la validación falla
        if ($validator->fails()) {
            return $this->errorValidatingData($validator->errors());
        }

        // Crear el libro con los datos validados
        $libro = Book::create($request->all());

        // Verificar si la creación del libro falla
        if (!$libro) {
            return response()->json(['message' => 'Error al crear el libro', 'status' => 500], 500);
        }

        // Devolver respuesta de éxito
        return response()->json(['message' => 'Libro creado correctamente', 'status' => 201], 201);
    }

    // Método para obtener un libro por su id
    public function getBookById($id)
    {
        $libro = Book::find($id);

        // Verificar si el libro no existe
        if (!$libro) {
            return $this->bookNotFound();
        }

        // Devolver el libro en formato JSON
        return response()->json($libro, 200);
    }

    // Método para eliminar un libro por su id
    public function deleteBookById($id)
    {
        $libro = Book::find($id);

        // Verificar si el libro no existe
        if (!$libro) {
            return $this->bookNotFound();
        }

        // Eliminar el libro
        $libro->delete();

        // Devolver respuesta de éxito
        return response()->json(['message' => 'Libro eliminado correctamente', 'status' => 200], 200);
    }

    // Método para actualizar un libro parcialmente por su id
    public function updateBookPartialById(Request $request, $id)
    {
        $libro = Book::find($id);

        // Verificar si el libro no existe
        if (!$libro) {
            return $this->bookNotFound();
        }

        // Validar los datos recibidos
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'max:250',
                'ISBN' => 'unique:books',
                'editorial' => 'max:200',
                'autorId' => 'integer|exists:authors,id',
                'generoId' => 'integer|exists:genres,id',
                'userCreatedId' => 'integer|exists:users,id'
            ]
        );

        // Verificar si la validación falla
        if ($validator->fails()) {
            return $this->errorValidatingData($validator->errors());
        }

        // Actualizar los campos proporcionados
        if ($request->has('title')) {
            $libro->title = $request->title;
        }

        if ($request->has('ISBN')) {
            $libro->ISBN = $request->ISBN;
        }

        if ($request->has('editorial')) {
            $libro->editorial = $request->editorial;
        }

        if ($request->has('autorId')) {
            $libro->autorId = $request->autorId;
        }

        if ($request->has('generoId')) {
            $libro->generoId = $request->generoId;
        }

        if ($request->has('userCreatedId')) {
            $libro->userCreatedId = $request->userCreatedId;
        }

        // Guardar los cambios en el libro
        $libro->save();

        // Devolver respuesta de éxito con los datos del libro actualizado
        $data = [
            'message' => 'Libro actualizado correctamente',
            'libro' => $libro,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    // Método privado para manejar libros no encontrados
    private function bookNotFound()
    {
        return response()->json(['message' => 'Libro no encontrado', 'status' => 404], 404);
    }

    // Método privado para manejar errores de validación de datos
    private function errorValidatingData($errors)
    {
        return response()->json(['message' => 'Error en la validación de datos', 'errors' => $errors, 'status' => 422], 422);
    }
}
