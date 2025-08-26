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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'posts_user_id'
            )->onDelete('cascade');
            $table->date('tanggal');
            $table->string('jam_masuk');
            $table->string('jam_keluar')->default('-');
            $table->foreignId('status_id')->constrained(
                table: 'statuses',
                indexName: 'posts_status_id'
            )->nullable();
            $table->text('keterangan')->default('-');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
