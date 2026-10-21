<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained()->restrictOnDelete();
            $table->string('payment_method');
            $table->string('status')->default('pending')->index();
            $table->decimal('amount', 12, 2);
            $table->string('transaction_id')->nullable()->unique();
            $table->json('gateway_response')->nullable();
            $table->string('idempotency_key');
            $table->timestamps();

            $table->unique(['order_id', 'idempotency_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
