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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); // ID da job
            $table->string('queue')->index(); // nome da fila
            $table->longText('payload'); // dados da job
            $table->unsignedTinyInteger('attempts'); // quantas tentativas já foram feitas
            $table->unsignedInteger('reserved_at')->nullable(); // quando foi reservada
            $table->unsignedInteger('available_at'); // quando estará disponível
            $table->unsignedInteger('created_at'); // quando foi criada
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary(); // ID do batch
            $table->string('name'); // nome do batch
            $table->integer('total_jobs'); // total de jobs
            $table->integer('pending_jobs'); // jobs pendentes
            $table->integer('failed_jobs'); // jobs que falharam
            $table->longText('failed_job_ids'); // IDs das jobs que falharam
            $table->mediumText('options')->nullable(); // opções extras
            $table->integer('cancelled_at')->nullable(); // quando foi cancelado
            $table->integer('created_at'); // quando foi criado
            $table->integer('finished_at')->nullable(); // quando terminou
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // ID
            $table->string('uuid')->unique(); // identificador único
            $table->text('connection'); // conexão usada
            $table->text('queue'); // fila da job
            $table->longText('payload'); // dados da job
            $table->longText('exception'); // erro ocorrido
            $table->timestamp('failed_at')->useCurrent(); // data da falha
        });
    }

    /**
     * Reverte as migrações.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs'); // remove tabela jobs
        Schema::dropIfExists('job_batches'); // remove tabela job_batches
        Schema::dropIfExists('failed_jobs'); // remove tabela failed_jobs
    }
};
