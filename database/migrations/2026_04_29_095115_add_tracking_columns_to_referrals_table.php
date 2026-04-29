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
        Schema::table('referrals', function (Blueprint $table) {
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->foreignId('departure_schedule_id')->nullable()->constrained()->onDelete('set null');
            $table->string('visitor_hash')->nullable();
            $table->boolean('is_unique')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropForeign(['departure_schedule_id']);
            $table->dropColumn(['ip', 'user_agent', 'departure_schedule_id', 'visitor_hash', 'is_unique']);
        });
    }
};
