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
        Schema::table('jamaahs', function (Blueprint $table) {
            $table->boolean('having_passport')->nullable()->after('status');
            $table->foreignId('departure_schedule_id')->nullable()->after('having_passport')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaahs', function (Blueprint $table) {
            $table->dropColumn(['having_passport', 'keberangkatan']);
        });
    }
};
