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
        Schema::create('variabel_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tema_form_id')->constrained()->onDelete('cascade');
            $table->string('variabel');
            $table->string('standar_variabel');
            $table->string('standar_foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variabel_forms');
    }
};
