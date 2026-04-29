<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kol_id')->constrained()->cascadeOnDelete();
            $table->string('referral_code');
            $table->string('user_name')->nullable();
            $table->enum('status', ['clicked', 'registered'])->default('clicked');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};