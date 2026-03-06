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

        Schema::create('cv_email_marketing', function (Blueprint $table) {
            $table->increments('em_id');
            $table->string('em_titulo', 100);
            $table->unsignedInteger('em_limite_envios')->nullable();
            $table->unsignedInteger('em_template_id')->index();
            $table->json('em_filtros_aplicados')->nullable();
            $table->enum('em_status', ['pendente', 'finalizado', 'executando','erro'])->default('pendente')->index();
            $table->timestamps();

        });

    }

    public function down(): void
    {
        Schema::dropIfExists('cv_email_marketing');
    }
};
