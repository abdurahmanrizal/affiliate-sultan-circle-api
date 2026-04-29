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
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('bin_binti')->nullable();
            $table->text('alamat_domisili');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('nik_ktp', 16);
            $table->string('status_perkawinan');
            $table->string('pekerjaan');
            $table->string('referral_code');
            $table->enum('status', ['booking', 'paid', 'rejected'])->default('booking');
            $table->foreignId('kol_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamaahs');
    }
};
