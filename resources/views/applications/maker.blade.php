@php
    $previousThemes = old('themes', []);
    $previousThemes = is_array($previousThemes) ? array_filter($previousThemes, 'is_string') : [];
@endphp
<form class="application-form" method="POST" action="{{ route('audience.apply.submit', 'makers') }}"
    x-data="{
        submitting: false, missing: [],
        submit(event) {
            if (this.submitting) { event.preventDefault(); return; }
            const required = [...this.$el.querySelectorAll('ui-checkbox[required]')];
            this.missing = required.filter(control => !control.checked).map(control => control.dataset.validationName);
            required.forEach(control => control.setAttribute('aria-invalid', control.checked ? 'false' : 'true'));
            if (this.missing.length) {
                event.preventDefault();
                required.find(control => !control.checked).focus();
                return;
            }
            this.submitting = true;
        }
    }"
    @submit="submit($event)" @pageshow.window="submitting = false"
    @change="if ($event.target.matches('ui-checkbox') && $event.target.checked) { missing = missing.filter(name => name !== $event.target.dataset.validationName); $event.target.setAttribute('aria-invalid', 'false'); }">
    @csrf
    <input type="hidden" name="_submission_token" value="{{ $submissionToken ?? '' }}">
    <noscript><style>.application-form__enhanced { display: none !important; }</style></noscript>
    @if ($errors->any())
        <div class="application-form__errors" role="alert" tabindex="-1" x-init="$nextTick(() => $el.focus())">
            <flux:heading level="3">Some details need your attention.</flux:heading>
            <ul>
                @foreach ($errors->messages() as $field => $messages)
                    @foreach ($messages as $error)
                        <li>
                            @if (in_array($field, ['submission', '_submission_token'], true))
                                {{ $error }}
                            @else
                                <a href="#{{ str_starts_with($field, 'themes.') ? 'themes' : $field }}">{{ $error }}</a>
                            @endif
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif
    <fieldset class="application-form__group">
        <legend>About you</legend>
        <div class="application-form__short-row">
            <x-application.field name="name" label="Your name" autocomplete="name" required maxlength="255" />
            <x-application.field name="email" label="Email address" type="email" autocomplete="email" required maxlength="255" />
        </div>
        <x-application.field name="motivation" label="Why would you like to take part?" rows="4" required maxlength="5000" />
        <x-application.field name="referral" label="Where did you hear about Make a Mark?" maxlength="1000" />
    </fieldset>

    <fieldset class="application-form__group">
        <legend>Languages</legend>
        <flux:text class="application-form__hint">Choose an optional level from 1 (beginner) to 5 (fluent).</flux:text>
        <div class="application-form__language-row">
            @foreach (['dutch' => 'Dutch', 'english' => 'English', 'french' => 'French'] as $key => $language)
                @php $previousLevel = is_scalar(old($key)) ? (string) old($key) : ''; @endphp
                <x-application.field :name="$key" :label="$language" type="select">
                    <flux:select.option value="" :selected="$previousLevel === ''">Choose a level</flux:select.option>
                    @foreach (range(1, 5) as $level)
                        <flux:select.option :value="$level" :selected="$previousLevel === (string) $level">{{ $level }}{{ $level === 1 ? ' · beginner' : ($level === 5 ? ' · fluent' : '') }}</flux:select.option>
                    @endforeach
                </x-application.field>
            @endforeach
        </div>
        <x-application.field name="other_languages" label="Other languages" maxlength="1000" />
    </fieldset>

    <fieldset class="application-form__group">
        <legend>About your work</legend>
        <x-application.field name="portfolio" label="Portfolio or examples of your work" type="url" required maxlength="2000" placeholder="https://" />
        <x-application.field name="social_media" label="Social media links" rows="2" maxlength="2000" />
        <x-application.field name="main_skills" label="Your main skills" rows="3" required maxlength="3000" />
        <x-application.field name="backup_skills" label="Other skills you could bring" rows="2" maxlength="3000" />
        <x-application.field name="other_skills" label="Other skills" rows="2" maxlength="3000" />
    </fieldset>

    <fieldset class="application-form__group">
        <legend>Preferences</legend>
        <x-application.field name="team_preferences" label="Team preferences or anything that would help us match you" rows="2" maxlength="2000" />
        <div id="themes" tabindex="-1" aria-labelledby="themes-label">
            <flux:label id="themes-label">Are there social themes you would be especially interested in?</flux:label>
            <div class="application-form__checks">
                @foreach (['Poverty reduction and social security', 'Culture and community building', 'Health and well-being', 'Housing and living conditions', 'Local economy and employment', 'Environment and sustainability', 'Education and youth development', 'Social inclusion and diversity', 'Sports and recreation', 'Refugee and migrant support'] as $theme)
                    <x-application.checkbox name="themes[]" id="theme-{{ $loop->index }}" :value="$theme" :checked="in_array($theme, $previousThemes, true)" :show-error="false">{{ $theme }}</x-application.checkbox>
                @endforeach
            </div>
            <flux:error name="themes" id="themes-error" :icon="false" />
        </div>
    </fieldset>

    <fieldset class="application-form__group">
        <legend>Practical details</legend>
        <x-application.checkbox name="date_confirmed" id="date_confirmed" required :checked="in_array(old('date_confirmed'), ['1', 1, true], true)">I have Saturday 27 March 2027 in my calendar.</x-application.checkbox>
        <x-application.field name="meeting_availability" label="When could you join a planning meeting before the event?" rows="3" required maxlength="2000" />
        <x-application.checkbox name="full_commitment" id="full_commitment" required :checked="in_array(old('full_commitment'), ['1', 1, true], true)">If selected, I can take part for the full event and join a planning meeting.</x-application.checkbox>
    </fieldset>

    <div class="application-form__send">
        <flux:button class="application-form__submit" type="submit" variant="primary" :loading="false" x-bind:disabled="submitting" x-bind:aria-busy="submitting.toString()">
            <span x-text="submitting ? 'Sending application…' : 'Send maker application'">Send maker application</span>
        </flux:button>
        <flux:text class="application-form__sending" role="status" x-cloak x-show="submitting">Sending your application. Please wait for confirmation.</flux:text>
    </div>
</form>
