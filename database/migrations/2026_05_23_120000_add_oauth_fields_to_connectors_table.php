<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('connectors', function (Blueprint $table) {
            $table->boolean('is_oauth')->default(false)->after('website_url');
            $table->string('oauth_client_id')->nullable()->after('is_oauth');
            $table->text('oauth_client_secret')->nullable()->after('oauth_client_id');
            $table->string('oauth_auth_url')->nullable()->after('oauth_client_secret');
            $table->string('oauth_token_url')->nullable()->after('oauth_auth_url');
            $table->text('oauth_scopes')->nullable()->after('oauth_token_url');
            $table->json('oauth_extra_params')->nullable()->after('oauth_scopes');
        });
    }

    public function down(): void
    {
        Schema::table('connectors', function (Blueprint $table) {
            $table->dropColumn([
                'is_oauth', 'oauth_client_id', 'oauth_client_secret',
                'oauth_auth_url', 'oauth_token_url', 'oauth_scopes', 'oauth_extra_params',
            ]);
        });
    }
};
