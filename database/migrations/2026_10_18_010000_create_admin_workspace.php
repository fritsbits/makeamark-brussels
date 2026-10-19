<?php

use App\Support\Skills;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_super_admin')->default(false);
            $table->boolean('is_active')->default(true);
        });
        Schema::table('applications', function (Blueprint $table) {
            $table->string('status', 32)->default('applied')->index();
            $table->text('internal_notes')->nullable();
            $table->text('refined_brief')->nullable();
            $table->json('skills_needed')->nullable();
            $table->json('main_skills')->nullable();
            $table->json('backup_skills')->nullable();
        });
        DB::table('applications')->orderBy('id')->eachById(function (object $application) {
            $answers = json_decode($application->answers, true) ?? [];
            DB::table('applications')->where('id', $application->id)->update([
                'skills_needed' => '[]',
                'main_skills' => json_encode($application->audience === 'makers' ? Skills::normalise($answers['main_skills'] ?? null) : []),
                'backup_skills' => json_encode($application->audience === 'makers' ? Skills::normalise($answers['backup_skills'] ?? null) : []),
            ]);
        });
        Schema::create('application_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['application_id', 'user_id']);
        });
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('edition_year')->index();
            $table->foreignId('organisation_application_id')->unique()->constrained('applications')->restrictOnDelete();
            $table->string('status', 32)->default('draft');
            $table->text('notes')->nullable();
            $table->unsignedInteger('lock_version')->default(0);
            $table->timestamps();
        });
        Schema::create('application_team', function (Blueprint $table) {
            $table->foreignId('application_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_team');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('application_reviews');
        Schema::table('applications', fn (Blueprint $table) => $table->dropColumn(['status', 'internal_notes', 'refined_brief', 'skills_needed', 'main_skills', 'backup_skills']));
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['is_admin', 'is_super_admin', 'is_active']));
    }
};
