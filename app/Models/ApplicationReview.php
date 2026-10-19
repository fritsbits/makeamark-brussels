<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

#[Fillable(['application_id', 'user_id', 'rating', 'comment'])]
class ApplicationReview extends Model
{
    protected static function booted(): void
    {
        static::saving(function (ApplicationReview $review) {
            Validator::make($review->getAttributes(), ['rating' => ['required', 'integer', 'between:1,5']])->validate();
            if (! Application::whereKey($review->application_id)->where('audience', 'organisations')->exists()) {
                throw ValidationException::withMessages(['application_id' => 'Only organisation applications can be reviewed.']);
            }
            if ($review->exists && $review->isDirty(['application_id', 'user_id'])) {
                throw ValidationException::withMessages(['user_id' => 'An assessment cannot be transferred to another reviewer or application.']);
            }
        });
    }

    protected function casts(): array
    {
        return ['rating' => 'integer'];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
