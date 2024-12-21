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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->date('tanggal');
            $table->bigInteger('jumlah');
            $table->foreignId('customer_id')
                ->constrained('customer', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('faktur_id')
                ->constrained('faktur', 'id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->boolean('status')
                ->default(0);
            $table->text('keterangan')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
