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
        Schema::create('cv_templates_email', function (Blueprint $table) {
            $table->increments('te_id');
            $table->string('te_codigo', 100)->index();
            $table->string('te_assunto', 200);
            $table->text('te_modelo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_templates_email');
    }

};
