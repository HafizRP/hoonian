<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('invoice_number')->unique()->nullable()->after('status');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('amount'); // Tax percentage
            $table->decimal('tax_amount', 15, 2)->default(0)->after('tax_rate');
            $table->decimal('total_amount', 15, 2)->nullable()->after('tax_amount');

            // Payment Details
            $table->string('payment_method')->nullable()->after('status');
            $table->timestamp('paid_at')->nullable()->after('payment_method');
            $table->timestamp('due_date')->nullable()->after('paid_at');

            // Additional Info
            $table->text('notes')->nullable()->after('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number',
                'tax_rate',
                'tax_amount',
                'total_amount',
                'payment_method',
                'paid_at',
                'due_date',
                'notes'
            ]);
        });
    }
};
