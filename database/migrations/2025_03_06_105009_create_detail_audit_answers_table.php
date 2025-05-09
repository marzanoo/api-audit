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
        Schema::create('detail_audit_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_answer_id')->constrained()->onDelete('cascade');
            $table->foreignId('variabel_form_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_audit_answers');
    }
};
