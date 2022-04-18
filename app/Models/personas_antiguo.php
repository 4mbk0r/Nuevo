<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personas_antiguo extends Model
{
    use HasFactory;
    protected $filetable = ['ci', 'nombre', 'celular'];
}
