<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePedidosTable extends Migration
{
    public function up()
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');

            $table->integer('cantidad');
            $table->decimal('precio', 8, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalle_pedidos');
    }
}
