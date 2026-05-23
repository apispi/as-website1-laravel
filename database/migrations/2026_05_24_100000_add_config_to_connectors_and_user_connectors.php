<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('connectors', function (Blueprint $table) {
            // Array of field definitions: [{key, label, type, required, placeholder, hint}]
            $table->json('config_schema')->nullable()->after('oauth_extra_params');
        });

        Schema::table('user_connectors', function (Blueprint $table) {
            // User's config values, encrypted at rest: {key: value, ...}
            $table->text('config')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('connectors', function (Blueprint $table) {
            $table->dropColumn('config_schema');
        });
        Schema::table('user_connectors', function (Blueprint $table) {
            $table->dropColumn('config');
        });
    }
};
