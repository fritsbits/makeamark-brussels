<?php

use App\Support\VolunteerRoles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->json('roles')->nullable();
            $table->json('assigned_roles')->nullable();
        });

        // Requested roles come from the submitted answers, which stay as submitted.
        DB::table('applications')->where('audience', 'volunteers')->select(['id', 'answers'])->orderBy('id')->each(function (object $row): void {
            $roles = VolunteerRoles::normalise(json_decode($row->answers ?? 'null', true)['roles'] ?? []);
            DB::table('applications')->where('id', $row->id)->update(['roles' => json_encode($roles)]);
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['roles', 'assigned_roles']);
        });
    }
};
