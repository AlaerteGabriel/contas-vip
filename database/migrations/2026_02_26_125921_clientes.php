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
        Schema::create('cv_clientes', function (Blueprint $table) {
            $table->increments('cl_id');
            $table->string('cl_nome', 100);
            $table->string('cl_usuario', 80)->nullable();
            $table->string('cl_email', 100)->unique()->index();
            $table->string('cl_email_envio', 100)->index();
            $table->char('cl_cel', length: 15)->nullable();
            $table->boolean('cl_banido')->default(false)->index();
            $table->string('cl_obs')->nullable();
            $table->timestamps();

            //$table->foreign('tr_us_id')->references('us_id')->on('fi_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('cv_clientes');
    }
};
