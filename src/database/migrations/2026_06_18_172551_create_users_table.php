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
            $table->string('username', 50)->unique();
            $table->string('email', 255)->unique();
            $table->string('password_hash', 255); // Hinweis: Laravel Auth nutzt oft 'password'
            $table->enum('role', ['user', 'contributor', 'admin'])->default('user');
            $table->enum('status', ['active', 'banned', 'pending'])->default('active');
            $table->timestamps();
            $table->softDeletes(); // Erstellt die deleted_at Spalte
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
