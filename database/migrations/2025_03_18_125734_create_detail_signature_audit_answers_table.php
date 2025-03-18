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
        Schema::create('detail_signature_audit_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audit_answer_id');
            $table->foreign('audit_answer_id')->references('id')->on('audit_answers')->onDelete('cascade');
            $table->string('auditor_signature');
            $table->string('auditee_signature');
            $table->string('facilitator_signature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_signature_audit_answers');
    }
};
