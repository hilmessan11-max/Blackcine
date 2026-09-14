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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('amount_cents')->default(0);
            $table->foreignId('currency_id')->nullable()->constrained();
            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['currency_id']);
            $table->dropColumn(['order_id', 'amount_cents', 'currency_id', 'status', 'payment_method', 'transaction_id']);
        });
    }
};
