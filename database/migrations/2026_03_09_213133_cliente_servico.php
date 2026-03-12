<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cv_clientes_servicos', function (Blueprint $table) {
            $table->increments('cs_id');
            $table->unsignedInteger('cs_cliente_id')->index();
            $table->unsignedInteger('cs_servico_id')->index();
            $table->unsignedInteger('cs_pedido_id')->index()->default(0);
            $table->unsignedInteger('cs_template_email_id')->index()->default(0);
            $table->date('cs_data_inicio')->index()->nullable()->default(null);
            $table->date('cs_data_termino')->index()->nullable()->default(null);
            $table->string('cs_cod_email', 5)->nullable()->default(null);
            $table->boolean('cs_aviso_vencimento')->default(true);
            $table->enum('cs_status', ['ativo', 'expirado', 'suspenso'])->index()->default('ativo');

            $table->foreign('cs_cliente_id')->references('cl_id')->on('cv_clientes')->onDelete('cascade');
            $table->foreign('cs_servico_id')->references('se_id')->on('cv_servicos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_clientes_servicos');
    }
};
