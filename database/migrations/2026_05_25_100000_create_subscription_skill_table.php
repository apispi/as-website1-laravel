<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->timestamp('refreshed_at')->nullable();
            $table->unique(['subscription_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_skill');
    }
};
