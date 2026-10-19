@php
    $previousLanguages = old('languages', []);
    $previousLanguages = is_array($previousLanguages) ? array_filter($previousLanguages, 'is_string') : [];
@endphp
<form class="application-form" method="POST" action="{{ route('audience.apply.submit', 'organisations') }}"
    x-data="{
        submitting: false, missing: [],
        submit(event) {
            if (this.submitting) { event.preventDefault(); return; }
            const required = [...this.$el.querySelectorAll('ui-checkbox[required]')];
            this.missing = required.filter(control => !control.checked).map(control => control.dataset.validationName);
            required.forEach(control => control.setAttribute('aria-invalid', control.checked ? 'false' : 'true'));
            const languages = [...this.$el.querySelectorAll('ui-checkbox')].filter(control => control.getAttribute('name') === 'languages[]');
            const hasLanguage = languages.some(control => control.checked);
            languages.forEach(control => control.setAttribute('aria-invalid', hasLanguage ? 'false' : 'true'));
            if (!hasLanguage) this.missing.push('languages');
            if (this.missing.length) {
                event.preventDefault();
                (required.find(control => !control.checked) || languages[0]).focus();
                return;
            }
            this.submitting = true;
        }
    }"
    @submit="submit($event)" @pageshow.window="submitting = false"
    @change="if ($event.target.matches('ui-checkbox') && $event.target.checked) {
        missing = missing.filter(name => name !== $event.target.dataset.validationName);
        $event.target.setAttribute('aria-invalid', 'false');
        if ($event.target.getAttribute('name') === 'languages[]') {
            missing = missing.filter(name => name !== 'languages');
            [...$el.querySelectorAll('ui-checkbox')].filter(control => control.getAttribute('name') === 'languages[]').forEach(control => control.setAttribute('aria-invalid', 'false'));
        }
    }">
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
                                <a href="#{{ str_starts_with($field, 'languages.') ? 'languages' : $field }}">{{ $error }}</a>
                            @endif
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif
    <fieldset class="application-form__group">
        <legend>About the organisation</legend>
        <x-application.field name="organisation_name" label="Organisation name" autocomplete="organization" required maxlength="255" />
        <x-application.checkbox name="youth_brussels_eligibility" id="youth_brussels_eligibility" required :checked="in_array(old('youth_brussels_eligibility'), ['1', 1, true], true)">We work with young people in Brussels.</x-application.checkbox>
        <x-application.field name="mission" label="What is your organisation's mission?" rows="4" required maxlength="5000" />
        <x-application.field name="website" label="Website" type="url" maxlength="2000" placeholder="https://" />
        <x-application.field name="social_media" label="Social media links" rows="2" maxlength="2000" />
    </fieldset>

    <fieldset class="application-form__group">
        <legend>Your challenge</legend>
        <flux:text class="application-form__hint">For example, a visual identity, campaign, communications strategy or social media assets.</flux:text>
        <x-application.field name="challenge" label="What would you like makers to help you work on?" rows="6" required maxlength="5000" />
    </fieldset>

    <fieldset class="application-form__group">
        <legend>Contact</legend>
        <div class="application-form__short-row">
            <x-application.field name="contact_name" label="Contact name" autocomplete="name" required maxlength="255" />
            <x-application.field name="email" label="Email address" type="email" autocomplete="email" required maxlength="255" />
        </div>
        <x-application.field name="phone" label="Phone number" type="tel" autocomplete="tel" required maxlength="80" />
        <div id="languages" tabindex="-1" role="group" aria-labelledby="languages-label" aria-describedby="languages-error" x-bind:aria-describedby="missing.includes('languages') ? 'languages-error languages-client-error' : 'languages-error'">
            <flux:label id="languages-label">Which languages can your contact person work in? <span aria-hidden="true">*</span></flux:label>
            <div class="application-form__checks">
                @foreach (['English', 'Dutch', 'French'] as $language)
                    <x-application.checkbox name="languages[]" id="language-{{ $loop->index }}" :value="$language" :checked="in_array($language, $previousLanguages, true)" :show-error="false" group-error="languages-error">{{ $language }}</x-application.checkbox>
                @endforeach
            </div>
            <flux:error name="languages" id="languages-error" :icon="false" />
            <flux:text id="languages-client-error" class="application-form__client-error" role="alert" x-cloak x-show="missing.includes('languages')">Choose at least one language to continue.</flux:text>
        </div>
    </fieldset>

    <fieldset class="application-form__group">
        <legend>Time commitment</legend>
        <flux:text class="application-form__hint">Please confirm that your organisation can take part in these steps if selected.</flux:text>
        @foreach ([
            'assets_ready' => 'We can provide makers with the information and materials they need to work on our challenge.',
            'planning_meeting' => 'We can join a planning meeting before the event.',
            'feedback_session' => 'We can take part in a feedback session during the make-a-thon.',
            'evening_presentation' => 'We can present the work in the evening.',
        ] as $key => $commitment)
            <x-application.checkbox :name="$key" :id="$key" required :checked="in_array(old($key), ['1', 1, true], true)">{{ $commitment }}</x-application.checkbox>
        @endforeach
    </fieldset>

    <div class="application-form__send">
        <flux:button class="application-form__submit" type="submit" variant="primary" :loading="false" x-bind:disabled="submitting" x-bind:aria-busy="submitting.toString()">
            <span x-text="submitting ? 'Sending application…' : 'Send organisation application'">Send organisation application</span>
        </flux:button>
        <flux:text class="application-form__sending" role="status" x-cloak x-show="submitting">Sending your application. Please wait for confirmation.</flux:text>
    </div>
</form>
