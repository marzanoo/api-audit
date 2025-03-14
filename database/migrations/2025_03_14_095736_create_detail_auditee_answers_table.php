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
        Schema::create('detail_auditee_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_audit_answer_id');
            $table->foreign('detail_audit_answer_id')->references('id')->on('detail_audit_answers')->onDelete('cascade');
            $table->string('auditee');
            $table->foreign('auditee')->references('emp_id')->on('karyawans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_auditee_answers');
    }
};
