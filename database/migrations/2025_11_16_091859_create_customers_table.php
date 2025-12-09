<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('alamat_ktp');
            $table->string('nik', 20);
            $table->string('tempat_lahir', 100);
            $table->text('note')->nullable();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', '-']);
            $table->string('rt', 5);
            $table->string('rw', 5);
            $table->string('kelurahan', 100);
            $table->string('kecamatan', 100);
            $table->string('agama', 50);
            $table->string('status_perkawinan', 50);
            $table->string('pekerjaan', 100);
            $table->string('kewarganegaraan', 50);
            $table->string('masa_berlaku', 50);
            $table->string('foto_ktp', 255);
            $table->string('pendidikan_terakhir', 100);
            $table->decimal('gaji_per_bulan', 15, 2);
            $table->string('nama_bank', 100);
            $table->string('pemilik_rekening', 255);
            $table->string('nomor_rekening', 50);
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->decimal('limit_pinjaman', 15, 2)->default(0.00);
            $table->dateTime('disetujui_pada')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            $table->unique('nik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
