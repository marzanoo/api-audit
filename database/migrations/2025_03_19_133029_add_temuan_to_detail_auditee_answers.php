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
        Schema::table('detail_auditee_answers', function (Blueprint $table) {
            $table->decimal('temuan', 5, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_auditee_answers', function (Blueprint $table) {
            $table->dropColumn('temuan');
        });
    }
};
