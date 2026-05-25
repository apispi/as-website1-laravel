<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('connectors', function (Blueprint $table) {
            $table->string('version', 50)->nullable()->after('description');
            $table->string('vendor', 255)->nullable()->after('version');
            $table->string('owner', 255)->nullable()->after('vendor');
            $table->string('status', 50)->default('active')->after('is_active');
            $table->string('sla_tier', 50)->nullable()->after('status');
            $table->json('environment')->nullable()->after('sla_tier');
            $table->json('tags')->nullable()->after('environment');
        });
    }

    public function down(): void
    {
        Schema::table('connectors', function (Blueprint $table) {
            $table->dropColumn(['version', 'vendor', 'owner', 'status', 'sla_tier', 'environment', 'tags']);
        });
    }
};
