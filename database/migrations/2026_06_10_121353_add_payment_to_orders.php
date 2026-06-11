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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'waiting_verification', 'paid', 'rejected'])
                ->default('unpaid')
                ->after('status');
            $table->string('payment_proof')->nullable()->after('payment_status'); // path foto bukti
            $table->string('payment_method')->nullable()->after('payment_proof'); // QRIS, Transfer, dll
            $table->text('payment_note')->nullable()->after('payment_method'); // catatan dari kasir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_proof', 'payment_method', 'payment_note']);
        });
    }
};
