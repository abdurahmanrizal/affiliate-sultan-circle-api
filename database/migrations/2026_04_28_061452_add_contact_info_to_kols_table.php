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
        Schema::table('kols', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
            $table->string('number_whatsapp')->nullable()->after('email');
            $table->string('tiktok_instagram_account')->nullable()->after('number_whatsapp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kols', function (Blueprint $table) {
            $table->dropColumn(['email', 'number_whatsapp', 'tiktok_instagram_account']);
        });
    }
};
