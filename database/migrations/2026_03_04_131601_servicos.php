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
            $table->string('se_nome', 80)->index();
            $table->string('se_cod')->nullable()->index();
            $table->string('se_email_vinculado')->nullable()->index();
            $table->string('se_username')->index();
            $table->string('se_senha_atual');
            $table->string('se_senha_anterior')->nullable();
            $table->date('se_data_renovacao')->index();
            $table->enum('se_status', ['Free', 'Premium'])->index()->default('Free');
            $table->date('se_data_ult_assinatura')->nullable()->index()->default(null);
            $table->unsignedInteger('se_qtd_assinantes');
            $table->enum('se_ass_hoje', ['Sim', 'Nao'])->index()->default('Nao');
            $table->timestamps();
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
