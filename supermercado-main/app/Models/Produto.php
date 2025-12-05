<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'imagem'
    ];

    // Casts para garantir tipos corretos
    protected $casts = [
        'preco' => 'decimal:2',
    ];
}