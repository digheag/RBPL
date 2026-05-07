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
        Schema::create('appoinment_schedules', function (Blueprint $table) {
            $table->id();
            $table->dateTime('schedule');
            $table->boolean('is_agen_approve_schedule')->nullable();
            $table->boolean('is_seller_approve_schedule')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appoinment_schedules');
    }
};
