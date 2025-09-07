<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('deleted_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_user_id')->index();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('is_admin')->default(0);
            $table->unsignedBigInteger('fungsi_id'); // ✅ Tambahkan ini
            $table->dateTime('tanggal_keluar')->nullable();
            $table->json('full_data')->nullable(); // Backup data JSON lengkap

            // Kolom log penghapusan
            $table->dateTime('deleted_by_system_at')->nullable();
            $table->unsignedBigInteger('deleted_by_admin_id')->nullable();
            $table->dateTime('deleted_by_admin_at')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deleted_users');
    }
};
