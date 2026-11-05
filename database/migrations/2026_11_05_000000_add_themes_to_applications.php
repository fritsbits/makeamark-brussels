<?php

use App\Support\Themes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->json('themes')->nullable();
        });

        // Backfill from the submitted answers, which stay as submitted.
        DB::table('applications')->select(['id', 'answers'])->orderBy('id')->each(function (object $row): void {
            $themes = Themes::normalise(json_decode($row->answers ?? 'null', true)['themes'] ?? []);
            DB::table('applications')->where('id', $row->id)->update(['themes' => json_encode($themes)]);
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('themes');
        });
    }
};
