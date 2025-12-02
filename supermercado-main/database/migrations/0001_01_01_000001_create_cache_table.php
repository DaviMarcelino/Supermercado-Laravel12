<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações.
     */
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary(); // chave do cache
            $table->mediumText('value'); // valor armazenado
            $table->integer('expiration'); // tempo de expiração
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // chave do lock
            $table->string('owner'); // dono do lock
            $table->integer('expiration'); // expiração do lock
        });
    }

    /**
     * Reverte as migrações.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache'); // remove tabela cache
        Schema::dropIfExists('cache_locks'); // remove tabela cache_locks
    }
};
