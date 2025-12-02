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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nome
            $table->string('email')->unique(); // email único
            $table->timestamp('email_verified_at')->nullable(); // email verificado
            $table->string('password'); // senha
            $table->boolean('is_admin')->default(false); // campo de admin
            $table->rememberToken(); // token de sessão
            $table->timestamps(); // created_at e updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // email como chave primária
            $table->string('token'); // token de reset
            $table->timestamp('created_at')->nullable(); // criado em
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // id da sessão
            $table->foreignId('user_id')->nullable()->index(); // id do usuário
            $table->string('ip_address', 45)->nullable(); // IP de acesso
            $table->text('user_agent')->nullable(); // navegador/dispositivo
            $table->longText('payload'); // dados da sessão
            $table->integer('last_activity')->index(); // última atividade
        });
    }

    /**
     * Reverte as migrações.
     */
    public function down(): void
    {
        Schema::dropIfExists('users'); // remove tabela users
        Schema::dropIfExists('password_reset_tokens'); // remove tabela de reset
        Schema::dropIfExists('sessions'); // remove tabela de sessões
    }
};
