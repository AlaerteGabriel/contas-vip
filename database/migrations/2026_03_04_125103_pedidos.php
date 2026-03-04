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
        //
        //
        Schema::create('cv_pedidos', function (Blueprint $table) {
            $table->increments('pe_id');
            $table->unsignedInteger('pe_cliente_id')->index();
            $table->string('pe_sku')->index();
            $table->string('pe_detalhes')->nullable();
            $table->string('pe_servico')->nullable()->index();
            $table->string('pe_tipo_venda')->nullable()->index();
            $table->date('pe_data_inicio')->index();
            $table->date('pe_data_termino')->index();
            $table->smallInteger('pe_periodo')->unsigned();
            $table->date('pe_data_pedido')->index();
            $table->string('pe_obs')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('cv_pedidos');
    }
};
