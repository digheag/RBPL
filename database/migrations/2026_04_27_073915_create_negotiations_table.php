<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\NullableType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained();
            $table->foreignId('seller_id')->constrained('users');
            $table->foreignId('agent_id')->constrained();
            $table->foreignId('buyer_id')->constrained('users');
            $table->float('offer_price');
            $table->string('description');
            $table->boolean('is_agen_approve')->nullable();
            $table->boolean('is_seller_approve')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negotiations');
    }
};
