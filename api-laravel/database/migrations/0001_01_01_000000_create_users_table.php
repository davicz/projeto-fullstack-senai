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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('cpf', 11)->unique();
            $table->string('password')->nullable(); 
            $table->string('phone', 11)->nullable();
            $table->string('cep', 8)->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('locality', 30)->nullable();
            $table->string('neighborhood', 40)->nullable();
            $table->string('street', 100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};