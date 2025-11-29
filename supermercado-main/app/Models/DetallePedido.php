<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    public function pedido()
{
    return $this->belongsTo(Pedido::class);
}

public function producto()
{
    return $this->belongsTo(Producto::class);
}
protected $fillable = ['pedido_id', 'producto_id', 'cantidad', 'precio'];

}
