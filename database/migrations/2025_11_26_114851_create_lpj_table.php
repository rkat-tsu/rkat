<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    if (!Schema::hasTable('lpj')) {
        Schema::create('lpj', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rkat_id');
            $table->string('judul', 150)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file_lpj')->nullable();
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->timestamps();
        });

        Schema::table('lpj', function (Blueprint $table) {
            $table->foreign('rkat_id')->references('id')->on('rkat_headers')->onDelete('cascade');
            $table->foreign('dibuat_oleh')->references('id')->on('users')->nullOnDelete();
        });
    }
}};