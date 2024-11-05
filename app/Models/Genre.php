<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;
    //Relacion con modelo Author
    protected $fillable = ['name', 'description'];

    //Un Generero puede tener muchos libros asignados
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
