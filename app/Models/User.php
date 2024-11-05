<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Nombre de la tabla en la base de datos
    protected $table = 'users';

    // Atributos que se pueden asignar en masa
    protected $fillable = [
        'nameCompleted',
        'email',
        'password',
        'city'
    ];

    // Atributos que deben ocultarse para arrays
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Atributos que deben ser convertidos a tipos nativos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación con el modelo Book
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // Mutador para encriptar la contraseña antes de guardarla
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
