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
            $table->foreignId('departure_schedule_id')->nullable()->after('keberangkatan')->constrained()->onDelete('set null');
            $table->dropColumn('keberangkatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaahs', function (Blueprint $table) {
            $table->dropForeign(['departure_schedule_id']);
            $table->dropColumn('departure_schedule_id');
            $table->string('keberangkatan')->nullable()->after('having_passport');
        });
    }
};
