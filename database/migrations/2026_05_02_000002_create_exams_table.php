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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['quiz','midterm','final','other'])->default('other');
            $table->integer('max_score')->default(100);
            $table->dateTime('scheduled_at')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_online')->default(false);
            $table->text('instructions')->nullable();
            $table->timestamps();

            $table->index(['classroom_id','subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};

