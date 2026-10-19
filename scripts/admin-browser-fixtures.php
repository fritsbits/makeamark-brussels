<?php

use App\Models\Application as Submission;
use App\Models\Team;
use App\Models\User;
use App\Services\Matchmaking;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

if (! $app->environment(['local', 'testing'])) {
    fwrite(STDERR, "Browser fixtures require local or testing environment.\n");
    exit(1);
}

$input = json_decode(stream_get_contents(STDIN), true, flags: JSON_THROW_ON_ERROR);
$prefix = $input['prefix'] ?? '';
if (! preg_match('/^browser-[a-f0-9]{16}$/D', $prefix)) {
    throw new RuntimeException('Invalid fixture prefix.');
}

$users = fn () => User::where('email', 'like', $prefix.'-%@example.invalid');
$applications = fn () => Submission::where('email', 'like', $prefix.'-%@example.invalid');

function fixtureAssert(bool $condition, string $message): void
{
    if (! $condition) {
        throw new RuntimeException($message);
    }
}

try {
    $result = match ($input['action'] ?? '') {
        'create' => DB::transaction(function () use ($prefix): array {
            $accounts = [];
            foreach (['super', 'admin'] as $role) {
                $password = bin2hex(random_bytes(24)).'Aa!7';
                $user = new User;
                $user->forceFill([
                    'name' => $prefix.' '.$role,
                    'email' => $prefix.'-'.$role.'@example.invalid',
                    'password' => $password,
                    'is_admin' => true,
                    'is_super_admin' => $role === 'super',
                    'is_active' => true,
                ])->save();
                $accounts[$role] = ['id' => $user->id, 'email' => $user->email, 'password' => $password];
            }
            $organisation = Submission::create([
                'audience' => 'organisations', 'edition_year' => 2027, 'status' => 'selected',
                'contact_name' => $prefix.' contact', 'email' => $prefix.'-org@example.invalid',
                'answers' => ['organisation_name' => $prefix.' organisation', 'mission' => 'Synthetic browser verification.', 'challenge' => 'Original fixture challenge'],
                'refined_brief' => 'Initial synthetic brief', 'skills_needed' => ['graphic-design', 'web-design'],
            ]);
            $makers = [];
            foreach (['one', 'two'] as $index => $label) {
                $makers[] = Submission::create([
                    'audience' => 'makers', 'edition_year' => 2027, 'status' => 'selected',
                    'contact_name' => $prefix.' maker '.$label, 'email' => $prefix.'-maker-'.$label.'@example.invalid',
                    'answers' => ['motivation' => 'Synthetic browser verification.', 'english' => '5'],
                    'main_skills' => [$index === 0 ? 'graphic-design' : 'web-design'], 'backup_skills' => ['illustrations'],
                ]);
            }
            $team = app(Matchmaking::class)->save(new Team, [
                'name' => $prefix.' team', 'edition_year' => 2027, 'organisation_application_id' => $organisation->id,
                'status' => 'draft', 'notes' => 'Initial synthetic team',
            ], [$makers[0]->id]);

            return ['accounts' => $accounts, 'organisation' => $organisation->id, 'makers' => array_map(fn ($maker) => $maker->id, $makers), 'team' => $team->id];
        }),
        'check' => (function () use ($prefix, $users, $applications, $input): array {
            $organisation = $applications()->where('audience', 'organisations')->sole();
            $admin = $users()->where('email', $prefix.'-admin@example.invalid')->sole();
            $team = Team::where('organisation_application_id', $organisation->id)->sole();
            $maker = $applications()->where('email', $prefix.'-maker-one@example.invalid')->sole();
            $review = $organisation->reviews()->where('user_id', $admin->id)->first();
            fixtureAssert($organisation->answers['challenge'] === 'Original fixture challenge', 'Original application changed.');
            if (($input['stage'] ?? '') === 'organisation') {
                fixtureAssert($organisation->refined_brief === $prefix.' revised briefing', 'Briefing did not persist.');
                fixtureAssert($review?->rating === 4 && $review->comment === $prefix.' independent assessment', 'Independent review did not persist.');
            } elseif (($input['stage'] ?? '') === 'maker') {
                fixtureAssert($maker->status === 'confirmed', 'Maker selection did not persist.');
                fixtureAssert($maker->internal_notes === $prefix.' selected in browser', 'Maker notes did not persist.');
            } elseif (($input['stage'] ?? '') === 'team-first') {
                fixtureAssert($team->notes === $prefix.' first browser save', 'First team save did not persist.');
                fixtureAssert($team->makers()->count() === 2, 'Second maker assignment did not persist.');
            } elseif (($input['stage'] ?? '') === 'team-second') {
                fixtureAssert($team->notes === $prefix.' second browser save', 'Second team save did not persist.');
                fixtureAssert($team->lock_version === 3, 'Unexpected version after second team edit: '.$team->lock_version.'.');
            } else {
                throw new RuntimeException('Unknown verification stage.');
            }

            return ['verified' => $input['stage']];
        })(),
        'cleanup' => DB::transaction(function () use ($users, $applications): array {
            $ids = $applications()->pluck('id');
            Team::whereIn('organisation_application_id', $ids)->delete();
            $applications()->delete();
            $userIds = $users()->pluck('id');
            DB::table('sessions')->whereIn('user_id', $userIds)->delete();
            User::withoutEvents(fn () => $users()->get()->each->delete());

            return ['cleaned' => true];
        }),
        default => throw new RuntimeException('Unknown fixture action.'),
    };
} catch (Throwable $exception) {
    fwrite(STDERR, ($input['action'] === 'create' ? 'Could not create browser fixtures.' : $exception->getMessage())."\n");
    exit(1);
}

echo json_encode($result, JSON_THROW_ON_ERROR);
