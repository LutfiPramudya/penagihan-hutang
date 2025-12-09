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

            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

            $table->unsignedBigInteger('profil_id')->nullable();
            $table->integer('amount');
            $table->integer('tenor_bulan')->nullable();
            $table->decimal('bunga_persen', 5, 2)->nullable();
            $table->decimal('total_pengembalian', 15, 2)->nullable();
            $table->decimal('cicilan_per_bulan', 15, 2)->nullable();
            $table->dateTime('tanggal_pengajuan')->nullable();
            $table->dateTime('disetujui_pada')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->date('date');
            $table->string('status')->default('belum lunas');
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('debts');
    }
};
