<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class login_model extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'telefono',
        'email',
        'calle',
        'numero',
        'codigo_postal',
        'pais',
        'estado',
        'ciudad',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthIdentifierName()
    {
        return 'email';
    }
}