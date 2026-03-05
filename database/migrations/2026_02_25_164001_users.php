<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::create('cv_users', function (Blueprint $table) {
            //$table->id();
            $table->increments('us_id');
            $table->string('us_nome', length: 80);
            $table->string('us_email', length: 80)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('us_password', 255);
            $table->enum('us_status', ['1','2'])->index()->default(1);
            $table->string('us_remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', length:80)->primary();
            $table->string('token')->index();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');

    }
};
