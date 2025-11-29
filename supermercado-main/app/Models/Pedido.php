<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['total', 'igv', 'subtotal', 'usuario'];

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}

