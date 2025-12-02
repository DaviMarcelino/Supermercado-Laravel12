<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalhePedidosTable extends Migration
{
    public function up()
    {
        Schema::create('detalhe_pedidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');

            $table->integer('quantidade');
            $table->decimal('preco', 8, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalhe_pedidos');
    }
}
