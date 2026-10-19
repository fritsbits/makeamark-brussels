<?php

namespace App\Models;

use App\Support\Skills;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Validation\ValidationException;

#[Fillable(['audience', 'edition_year', 'contact_name', 'email', 'answers', 'status', 'internal_notes', 'refined_brief', 'skills_needed', 'main_skills', 'backup_skills', 'submission_key'])]
class Application extends Model
{
    protected $attributes = ['status' => 'applied'];

    protected static function booted(): void
    {
        static::creating(function (Application $application) {
            foreach (['skills_needed', 'main_skills', 'backup_skills'] as $field) {
                if (! array_key_exists($field, $application->getAttributes())) {
                    $application->{$field} = $application->audience === 'makers' && $field !== 'skills_needed'
                        ? Skills::normalise($application->answers[$field] ?? null)
                        : [];
                }
            }
        });
        static::updating(function (Application $application) {
            if (! $application->isDirty(['status', 'audience', 'edition_year'])) {
                return;
            }
            if ($application->teams()->exists() || Team::where('organisation_application_id', $application->id)->exists()) {
                if (! in_array($application->status, ['selected', 'confirmed'], true)
                    || $application->isDirty(['audience', 'edition_year'])) {
                    throw ValidationException::withMessages(['status' => 'Remove this application from its team before changing its eligibility or edition.']);
                }
            }
        });
    }

    protected function casts(): array
    {
        return ['answers' => 'array', 'edition_year' => 'integer', 'skills_needed' => 'array', 'main_skills' => 'array', 'backup_skills' => 'array', 'receipt_sent_at' => 'datetime'];
    }

    protected function displayName(): Attribute
    {
        return Attribute::get(fn () => $this->answers['organisation_name'] ?? $this->contact_name);
    }

    protected function averageRating(): Attribute
    {
        return Attribute::get(fn () => $this->reviews()->avg('rating'));
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ApplicationReview::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'application_team')->withTimestamps();
    }

    public function organisationTeam(): HasOne
    {
        return $this->hasOne(Team::class, 'organisation_application_id');
    }
}
