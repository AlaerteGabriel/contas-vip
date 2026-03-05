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
        Schema::create('cv_configuracoes', function (Blueprint $table) {
            $table->increments('co_id');
            $table->string('co_key', 100)->index();
            $table->text('co_value');
            $table->string('co_group')->default('geral');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_configuracoes');
    }
};
