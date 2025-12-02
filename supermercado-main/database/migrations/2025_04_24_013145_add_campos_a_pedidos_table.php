<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->decimal('subtotal', 8, 2)->after('id');
            $table->decimal('imposto', 8, 2)->after('subtotal');
            $table->string('usuario')->after('imposto');
        });
    }

    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'imposto', 'usuario']);
        });
    }
};
