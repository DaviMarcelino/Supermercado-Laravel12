<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'total',
        'imposto',
        'subtotal',
        'usuario'
    ];

    /**
     * Itens do pedido (antes: detalles)
     */
    public function itens()
    {
        return $this->hasMany(ItemPedido::class);
    }
}
