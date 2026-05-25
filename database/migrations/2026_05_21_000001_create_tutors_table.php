<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->decimal('hourly_rate', 8, 2)->default(0.00);
            $table->text('bio')->nullable();
            $table->integer('experience')->default(0); // in years
            $table->string('qualification')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->boolean('is_verified')->default(false);
            $table->string('zoom_link')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
