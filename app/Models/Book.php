<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'ISBN',
        'editorial',
        'authorId',
        'genreId',
        'userCreatedId'
    ];

    //Relacion con modelo Author, Un libro solo puede tener un Author asignado
    public function author()
    {
        return $this->belongsTo(Author::class);
    }


    //Relacion con modelo User, Un libro solo puede tener un usuario asignado
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
