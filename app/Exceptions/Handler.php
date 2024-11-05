<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Este método permite registrar los callbacks para manejar excepciones en la aplicación.

        $this->reportable(function (Throwable $e) {
            // Puedes usar este espacio para definir cualquier lógica que quieras ejecutar
            // cuando ocurra una excepción, como enviar un reporte a un servicio externo.
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            // Maneja las excepciones de autenticación. 
            // Devuelve una respuesta JSON con un mensaje de error y un código de estado 401.
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autenticado'], 401);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            // Maneja las excepciones de método no permitido.
            // Devuelve una respuesta JSON con un mensaje de error y un código de estado 405.
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Método no permitido'], 405);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            // Maneja las excepciones de recurso no encontrado.
            // Devuelve una respuesta JSON con un mensaje de error y un código de estado 404.
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No encontrado'], 404);
            }
        });
    }
}
