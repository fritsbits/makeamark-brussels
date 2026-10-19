<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'edition_year', 'organisation_application_id', 'status', 'notes'])]
class Team extends Model
{
    protected $attributes = ['status' => 'draft', 'lock_version' => 0];

    protected function casts(): array
    {
        return ['edition_year' => 'integer', 'lock_version' => 'integer'];
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'organisation_application_id');
    }

    public function makers(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'application_team')->withTimestamps();
    }
}
