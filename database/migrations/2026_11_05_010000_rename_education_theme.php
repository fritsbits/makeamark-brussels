<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const OLD = 'Education and youth development';

    private const NEW = 'Education and learning';

    public function up(): void
    {
        $this->rename(self::OLD, self::NEW);
    }

    public function down(): void
    {
        $this->rename(self::NEW, self::OLD);
    }

    /** Themes match by exact label, so both the column and the submitted answers follow the rename. */
    private function rename(string $from, string $to): void
    {
        $swap = fn (mixed $themes): array => array_values(array_unique(array_map(fn ($theme) => $theme === $from ? $to : $theme, is_array($themes) ? $themes : [])));

        DB::table('applications')->select(['id', 'themes', 'answers'])
            ->where('themes', 'like', '%'.$from.'%')->orWhere('answers', 'like', '%'.$from.'%')
            ->orderBy('id')->each(function (object $row) use ($swap): void {
                $update = [];
                if ($row->themes !== null) {
                    $update['themes'] = json_encode($swap(json_decode($row->themes, true)));
                }
                $answers = json_decode($row->answers ?? 'null', true);
                if (is_array($answers) && is_array($answers['themes'] ?? null)) {
                    $answers['themes'] = $swap($answers['themes']);
                    $update['answers'] = json_encode($answers);
                }
                if ($update) {
                    DB::table('applications')->where('id', $row->id)->update($update);
                }
            });
    }
};
