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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->restrictOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unsignedInteger('prep_time')->nullable()->comment('Zubereitungszeit in Minuten');
            $table->unsignedInteger('cook_time')->nullable()->comment('Koch-/Backzeit in Minuten');
            $table->unsignedInteger('servings')->nullable()->comment('Anzahl Portionen');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable();
            $table->string('image_url', 500)->nullable();
            $table->json('tags')->nullable()->comment('Flexibel: ["vegan", "unter-30-min"]');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
