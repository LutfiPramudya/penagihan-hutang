<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();

            // Relasi ke customers
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

            // Nominal hutang
            $table->integer('amount'); 
            
            // penghapusan hutang
            $table->integer('amount');

            // Tanggal hutang
            $table->date('date');

            // Status pembayarannya
            $table->string('status')->default('belum lunas');

            // Catatan tambahan
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('debts');
    }
};
