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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->integer('quantity');
            $table->string('total_price');
            $table->string('source')->nullable(); // e.g., supplier or customer
            $table->string('note')->nullable();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // e.g., pending, completed, canceled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
