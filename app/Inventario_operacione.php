<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario_operacione extends Model
{
    protected $fillable = ['referencia', 'type', 'existencia', 'insumo'];
}
