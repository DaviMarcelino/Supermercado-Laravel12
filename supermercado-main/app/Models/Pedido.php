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

    public function itens()
    {
        return $this->hasMany(ItemPedido::class);
    }
}