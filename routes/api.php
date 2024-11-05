<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\userController;
use App\Http\Controllers\Api\bookController;
use App\Http\Controllers\Api\authorController;
use App\Http\Controllers\Api\genreController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware(['auth:sanctum', 'ensure.token'])->get('/validate-token', [AuthController::class, 'validateToken']);
//User
Route::get('/users', [userController::class, 'index'] );

Route::get('/user/{idUser}', [userController::class,'getUserById'] );

Route::post('/user', [userController::class,'store'] );

Route::put('/user/{idUser}', [userController::class,'updateUserById'] );

Route::patch('/user/{idUser}', [userController::class,'updateUserPartialById'] );

Route::delete('/user/{idUser}', [userController::class,'deleteUserById'] );


//Books
Route::get('/books', [bookController::class, 'index'] );

Route::get('/book/{idBook}', [bookController::class,'getBookById'] );

Route::post('/book', [bookController::class,'store'] );

Route::put('/book/{idBook}', [bookController::class,'updateBookById'] );

Route::patch('/book/{idBook}', [bookController::class,'updateBookPartialById'] );

Route::delete('/book/{idBook}', [bookController::class,'deleteBookById'] );



//Authors
Route::get('/authors', [authorController::class, 'index'] );

Route::get('/author/{idAuthor}', [authorController::class,'getAuthorById'] );

Route::post('/author', [authorController::class,'store'] );

Route::patch('/author/{idAuthor}', [authorController::class,'updateAuthorPartialById'] );

Route::delete('/author/{idAuthor}', [authorController::class,'deleteAuthorById'] );


//Genres
Route::get('/genres', [genreController::class, 'index'] );

Route::get('/genre/{idGenre}', [genreController::class,'getGenreById'] );

Route::post('/genre', [genreController::class,'store'] );

Route::patch('/genre/{idGenre}', [genreController::class,'updateGenrePartialById'] );

Route::delete('/genre/{idGenre}', [genreController::class,'deleteGenreById'] );