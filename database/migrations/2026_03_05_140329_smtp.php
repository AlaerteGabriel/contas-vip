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
        Schema::create('cv_smtp', function (Blueprint $table) {
            $table->increments('sm_id');
            $table->string('sm_nome', 80)->index();
            $table->string('sm_email_remetente', 100)->index();
            $table->string('sm_host', 80);
            $table->string('sm_login', 80);
            $table->string('sm_senha', 80);
            $table->unsignedSmallInteger('sm_porta');
            $table->enum('sm_protocolo', ['tls','none','ssl']);
            $table->boolean('sm_padrao')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_smtp');
    }
};
