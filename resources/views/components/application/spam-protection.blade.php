<input type="hidden" name="_form_started" value="{{ $started }}">
<div hidden aria-hidden="true">
    <input type="text" name="website_confirmation" value="" tabindex="-1" autocomplete="off" aria-hidden="true">
</div>
<div data-application-verification data-enabled="{{ config('services.turnstile.enabled') ? 'true' : 'false' }}"
    data-sitekey="{{ config('services.turnstile.site_key') }}" data-audience="{{ $audience }}"></div>
<input type="hidden" name="cf-turnstile-response" value="">
<div class="application-form__errors" data-verification-error role="alert" tabindex="-1" hidden>
    <flux:text data-verification-message></flux:text>
    <a href="mailto:hello@letsmakeamark.brussels">Contact us to apply</a>
</div>
@if (config('services.turnstile.enabled'))
    <noscript><flux:text>This form needs JavaScript for spam protection. Enable JavaScript or <a href="mailto:hello@letsmakeamark.brussels">contact us to apply</a>.</flux:text></noscript>
@endif
