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

        Schema::create('cv_email_marketing_logs', function (Blueprint $table) {
            $table->increments('eml_id');
            $table->unsignedInteger('eml_email_marketing_id')->index();
            $table->unsignedInteger('eml_user_id')->index();
            $table->string('eml_email_destino', 80); // É bom salvar o e-mail aqui caso o usuário mude de e-mail no futuro, o log permanece intacto
            $table->enum('eml_status', ['fila', 'falhou', 'enviado'])->default('fila'); // na_fila, enviado, falhou

            $table->text('eml_msg')->nullable(); // Essencial: se o e-mail der erro (ex: caixa cheia), o Laravel salva o motivo aqui
            $table->timestamp('eml_enviado_em')->nullable(); // Data e hora exata que o provedor aceitou o e-mail
            $table->timestamps();

            $table->foreign('eml_email_marketing_id')->references('em_id')->on('cv_email_marketing')->onDelete('cascade');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('cv_email_marketing_logs');
    }
};
