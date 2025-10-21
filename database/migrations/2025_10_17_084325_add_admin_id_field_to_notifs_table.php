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
        Schema::table('notifs', function (Blueprint $table) {
            // tambahkan kolom admin_id setelah kolom id
            $table->unsignedBigInteger('admin_id')->after('id')->nullable();

            // ubah foreign key agar mengacu ke tabel users, bukan admins
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifs', function (Blueprint $table) {
            // hapus foreign key dulu baru kolomnya
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
    }
};
