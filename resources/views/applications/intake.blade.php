@unless ($introRendered ?? false)
    @include('applications.privacy-note')
@endunless
@if ($submitted)
    <div class="application-receipt">
        <div class="application-receipt__celebration">
            <span class="application-receipt__stamp" aria-hidden="true"><flux:icon.check class="application-receipt__check" /></span>
            <flux:heading level="2" id="application-heading" class="h-display application-receipt__title">Application received.</flux:heading>
            <flux:text class="application-receipt__thanks">{{ $audience === 'makers' ? 'Thanks for putting your hand up.' : 'Thanks for bringing your challenge.' }}</flux:text>
        </div>
        <div class="application-receipt__details">
            <flux:text class="application-receipt__saved" role="status">Your {{ $audience === 'makers' ? 'maker' : 'organisation' }} application has been saved for the {{ $edition['year'] }} edition.</flux:text>
            <div class="application-receipt__next">
                <flux:heading level="3" class="h-display application-receipt__subtitle">What happens next</flux:heading>
                <flux:text>The organising team will review {{ $audience === 'makers' ? 'your skills and availability' : 'your organisation and challenge' }} as part of selection. Applying does not guarantee a place.</flux:text>
            </div>
            <div class="application-receipt__delivery">
                @if ($emailSent)
                    <flux:text>A receipt email has been sent to the address you provided.</flux:text>
                @else
                    <flux:text>No receipt email has been sent. You don’t need to apply again.</flux:text>
                @endif
                <flux:text>Questions? <a href="mailto:{{ $site['email'] }}">Email the organising team</a>.</flux:text>
            </div>
            <x-pill href="/">Back to the edition</x-pill>
        </div>
    </div>
@elseif ($edition['status'] === 'open')
    @unless ($introRendered ?? false)
        @include('applications.context')
    @endunless
    @include($audience === 'makers' ? 'applications.maker' : 'applications.organisation')
@else
    <div class="application__notice" role="status">
        <strong>{{ $edition['pill']['label'] }}</strong>
        <p>Applications are not open right now. Please check this page for updates.</p>
        @if ($errors->has('submission'))<p role="alert">{{ $errors->first('submission') }}</p>@endif
        <p>Questions? <a href="mailto:{{ $site['email'] }}">Email the organising team</a>.</p>
    </div>
@endif
@if (! ($introRendered ?? false) && $edition['status'] !== 'open' && ! $submitted)
    @include('applications.context')
@endif
