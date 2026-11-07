<?php

namespace App\Support;

use Closure;

/** One applicant audience: its form, validation, tokens and page wording. Defined in Audiences. */
final readonly class Audience
{
    /**
     * @param  array<string, int>  $fields  Free-text fields and their length limits.
     * @param  list<string>  $checkboxes
     * @param  array<string, int>  $lists  List fields and their item limits.
     * @param  Closure(array): array  $rules  Validation rules for the submitted input.
     * @param  array<string, string>  $messages
     */
    public function __construct(
        public string $slug,
        public string $noun,
        public string $nameField,
        public string $pageTitle,
        public string $pageDescription,
        public string $receiptThanks,
        public string $reviewFocus,
        public array $fields,
        public array $checkboxes,
        public array $lists,
        private Closure $rules,
        public array $messages,
        public string $secretKey,
        public bool $followsEditionStatus = true,
        public bool $legacyTokens = false,
    ) {}

    public function rules(array $input): array
    {
        return ($this->rules)($input);
    }

    public function formView(): string
    {
        return 'applications.'.strtolower($this->noun);
    }

    /** Makers and organisations open with the edition; volunteers can apply until the event is over. */
    public function isOpen(array $edition): bool
    {
        if ($this->followsEditionStatus) {
            return $edition['status'] === 'open';
        }

        return $edition['status'] !== 'done'
            && now('Europe/Brussels')->toDateString() <= ($edition['endDate'] ?? $edition['startDate']);
    }

    /** Shown when a form is posted while this audience is closed. */
    public function closedMessage(array $edition): string
    {
        if (! $this->followsEditionStatus) {
            return 'Volunteer applications for '.$edition['year'].' are closed. Your application has not been sent. Contact the organising team if you need help.';
        }

        return $edition['status'] === 'preparing'
            ? 'Applications aren’t open yet, so your application has not been sent. Contact the organising team if you need help.'
            : 'Applications are now closed. Your application has not been sent. Contact the organising team if you need help.';
    }

    /** Maker tokens predate the shared helper; their scope and signature stay unprefixed so open forms keep working. */
    public function tokenScope(string $value): string
    {
        return $this->legacyTokens ? $value : $this->slug.':'.$value;
    }
}
