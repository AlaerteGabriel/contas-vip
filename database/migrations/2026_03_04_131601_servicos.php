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
        Schema::create('cv_servicos', function (Blueprint $table) {
            $table->increments('se_id');
            $table->unsignedInteger('se_contas_id');
            $table->string('se_nome', 80)->index();
            $table->string('se_cod')->nullable()->index();
            $table->string('se_email_vinculado')->nullable()->index()->default(null);
            $table->string('se_username')->index()->nullable()->default(null);
            $table->string('se_senha_atual');
            $table->string('se_senha_anterior')->nullable();
            $table->date('se_data_renovacao')->index()->nullable();
            $table->enum('se_tipo', ['Free', 'Premium'])->index()->default('Free');
            $table->text('se_obs')->nullable();
            $table->enum('se_status', ['fechada', 'davez','banida','desligada', 'ativa'])->index()->default(null);
            $table->date('se_data_update')->nullable()->index()->default(null);
            $table->unsignedInteger('se_qtd_assinantes');
            $table->unsignedInteger('se_limite')->nullable()->index()->default(null);
            $table->unsignedInteger('se_qtd_update')->default(0);
            $table->timestamps();

            $table->foreign('se_contas_id')->references('co_id')->on('cv_contas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('cv_servicos');
    }
};
