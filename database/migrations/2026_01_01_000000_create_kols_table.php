<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kols', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('referral_code')->unique();
            $table->integer('total_click')->default(0);
            $table->integer('total_register')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kols');
    }
};