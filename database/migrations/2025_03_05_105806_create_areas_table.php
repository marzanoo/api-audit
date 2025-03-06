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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lantai_id');    
            $table->string('area');
            $table->string('pic_area');
            $table->timestamps();

            $table->foreign('lantai_id')->references('id')->on('lantais')->onDelete('cascade');
            $table->foreign('pic_area')->references('emp_id')->on('karyawans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
