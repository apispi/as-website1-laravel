<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('title');
            $table->text('description');
            $table->string('category', 100)->nullable();
            $table->string('format', 100)->nullable();
            $table->string('duration', 100)->nullable();
            $table->string('level', 100)->nullable();
            $table->unsignedSmallInteger('modules_count')->nullable();
            $table->string('price', 50)->nullable();
            $table->string('price_unit', 100)->nullable();
            $table->string('badge', 50)->nullable();
            $table->json('topics')->nullable();
            $table->json('includes')->nullable();
            $table->string('instructor')->nullable();
            $table->string('instructor_role')->nullable();
            $table->string('checkout_name')->nullable();
            $table->unsignedInteger('checkout_amount')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('cta_headline')->nullable();
            $table->string('cta_sub')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
