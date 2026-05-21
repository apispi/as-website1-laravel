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
        Schema::table('agents', function (Blueprint $table) {
            $table->json('features')->nullable()->after('description');
            $table->json('includes')->nullable()->after('features');
            $table->json('use_cases')->nullable()->after('includes');
            $table->json('pricing_plans')->nullable()->after('use_cases');
            $table->json('faqs')->nullable()->after('pricing_plans');
            $table->string('target_audience')->nullable()->after('faqs');
            $table->string('tagline')->nullable()->after('target_audience');
            $table->string('cta_headline')->nullable()->after('tagline');
            $table->string('cta_sub')->nullable()->after('cta_headline');
            $table->string('checkout_name')->nullable()->after('cta_sub');
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['features','includes','use_cases','pricing_plans','faqs','target_audience','tagline','cta_headline','cta_sub','checkout_name']);
        });
    }
};
