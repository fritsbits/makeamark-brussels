<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('submission_key', 64)->nullable()->unique();
            $table->timestamp('receipt_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique(['submission_key']);
            $table->dropColumn(['submission_key', 'receipt_sent_at']);
        });
    }
};
