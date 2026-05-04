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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable(); // nama pembeli, opsional
            $table->enum('status', ['pending', 'proses', 'selesai', 'batal'])->default('pending');
            $table->unsignedBigInteger('total')->default(0); // total harga dalam rupiah
            $table->text('notes')->nullable(); // catatan pesanan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
