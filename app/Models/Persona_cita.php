<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona_cita extends Model
{
    use HasFactory;
    protected $filetable = ['ci', 'nombre', 'celular'];

}
