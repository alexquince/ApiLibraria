<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{

    //metodo para consultar  todos los usuarios
    public function index()
    {
        $users = User::all();

        if ($users->isEmpty()) {

            $data =
                [
                    'message' => 'No se encontraron usuarios',
                    'status' => 200
                ];

            return response()->json($data, 404);
        }

        return response()->json($users, 200);
    }


    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $validator = Validator::make($request->all(), [
            'nameCompleted' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'city' => 'string|nullable',
        ]);

        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación de datos', 'errors' => $validator->errors(), 'status' => 422], 422);
        }

        // Crear el usuario con los datos validados
        $user = User::create([
            'nameCompleted' => $request->nameCompleted,
            'email' => $request->email,
            'password' => $request->password, // Se encriptará automáticamente gracias al mutador en el modelo
            'city' => $request->city,
        ]);

        // Verificar si la creación del usuario falla
        if (!$user) {
            return response()->json(['message' => 'Error al crear el usuario', 'status' => 500], 500);
        }

        // Devolver respuesta de éxito
        return response()->json(['message' => 'Usuario creado correctamente', 'status' => 201], 201);
    }

    //Obteniendo un usuario por su id
    public function getUserById($id)
    {

        $user = User::find($id);

        if (!$user) {
            $data =
                [
                    'message' => 'Usuario no encontrado',
                    'status' => 404
                ];

            return response()->json($data, 404);
        }

        return response()->json($user, 200);
    }

    //Eliminado un usuario por su id
    public function deleteUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            $data =
                [
                    'message' => 'Usuario no encontrado',
                    'status' => 404
                ];

            return response()->json($data, 404);
        }

        $user->delete();

        $data =
            [
                'message' => 'Usuario eliminado',
                'status' => 200
            ];

        return response()->json($data, 200);
    }

    //Actualizando un usuario
    public function updateUserById(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            $data =
                [
                    'message' => 'Usuario no encontrado',
                    'status' => 404
                ];

            return response()->json($data, 404);
        }

        //validando datos recibidos
        $validator = Validator::make(
            $request->all(),
            [
                'nameCompleted' => 'required',
                'email' => 'required|email|unique:user',
                'city' => 'required'
            ]
        );

        if ($validator->fails()) {
            $data =
                [
                    'message' => 'Error en la validación de los datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
            return response()->json($data, 400);
        }


        $user->nameCompleted = $request->nameCompleted;
        $user->email = $request->email;
        $user->city = $request->city;

        $user->save();
        $data =
            [
                'message' => 'Usuario actualizado correctamente',
                'user'=> $user,
                'status' => 200
            ];
        return response()->json($data, 200);
    }

    
    //Actualizando un usuario
    public function updateUserpartialById(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            $data =
                [
                    'message' => 'Usuario no encontrado',
                    'status' => 404
                ];

            return response()->json($data, 404);
        }

        //validando datos recibidos
        $validator = Validator::make(
            $request->all(),
            [
                'nameCompleted' => 'max:300',
                'email' => 'email|unique:user',
                'city' => 'max:200'
            ]
        );

        if ($validator->fails()) {
            $data =
                [
                    'message' => 'Error en la validación de los datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
            return response()->json($data, 400);
        }

        if ($request->has('nameCompleted')) {
            $user->nameCompleted = $request->nameCompleted;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('city')) {
            $user->city = $request->city;
        }



        $user->save();
        $data =
            [
                'message' => 'Usuario actualizado correctamente',
                'user'=> $user,
                'status' => 200
            ];
        return response()->json($data, 200);
    }
}
