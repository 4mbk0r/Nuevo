<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona_atencion extends Model
{
    use HasFactory;
    protected $filetable = [

        "ci", "nombre", "ap_paterno", "ap_materno", "celular", "correo", "expendido", "fecha_nacimiento", "direccion" , "sexo", "nom_municipio" ];
   
}
