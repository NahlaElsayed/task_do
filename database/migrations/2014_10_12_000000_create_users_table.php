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
            $table->string('fname')->length(256);
            $table->string('lname')->length(256);
            $table->string('email')->length(256)->unique();
            $table->string('phone')->length(256)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->length(256)->nullable();
            $table->string('code')->length(256)->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->rememberToken()->nullable();
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
