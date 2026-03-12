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
        Schema::create('cv_contas', function (Blueprint $table) {
            $table->increments('co_id');
            $table->char('co_codigo', 2)->index();
            $table->string('co_nome', 100);
            $table->string('co_url');
            $table->unsignedSmallInteger('co_limite')->nullable()->default(null);
            $table->string('co_url_anuncio');
            $table->boolean('co_envio_manual')->default(false);
            $table->unsignedInteger('co_template_email_id')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_contas');
    }
};

