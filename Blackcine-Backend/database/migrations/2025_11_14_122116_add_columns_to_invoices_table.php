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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique()->nullable();
            $table->integer('total_cents')->default(0);
            $table->integer('tax_cents')->default(0);
            $table->integer('subtotal_cents')->default(0);
            $table->foreignId('currency_id')->nullable()->constrained();
            $table->string('status')->default('draft');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('due_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['currency_id']);
            $table->dropColumn(['order_id', 'invoice_number', 'total_cents', 'tax_cents', 'subtotal_cents', 'currency_id', 'status', 'issued_at', 'due_at']);
        });
    }
};
