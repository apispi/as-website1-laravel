<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agent_skill', function (Blueprint $table) {
            $table->string('name')->nullable()->after('skill_id');
            $table->text('description')->nullable()->after('name');
            $table->string('category')->nullable()->after('description');
            $table->timestamp('refreshed_at')->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('agent_skill', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'category', 'refreshed_at']);
        });
    }
};
