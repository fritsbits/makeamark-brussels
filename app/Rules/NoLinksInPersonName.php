<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoLinksInPersonName implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && preg_match('~https?://|www\.~i', trim($value))) {
            $fail("Please enter a person's name without a link.");
        }
    }
}
