@php
    $previousRoles = old('roles', []);
    $posts = is_array($previousRoles) && in_array('social_media', $previousRoles, true);
@endphp
<form class="application-form" method="POST" action="{{ route('audience.apply.submit', 'volunteers') }}"
    x-data="{
        submitting: false, missing: [], posts: @js($posts),
        controls(name) { return [...this.$el.querySelectorAll('ui-checkbox')].filter(control => control.getAttribute('name') === name + '[]'); },
        submit(event) {
            if (this.submitting) { event.preventDefault(); return; }
            const required = [...this.$el.querySelectorAll('ui-checkbox[required]')];
            this.missing = required.filter(control => !control.checked).map(control => control.dataset.validationName);
            required.forEach(control => control.setAttribute('aria-invalid', control.checked ? 'false' : 'true'));
            const groups = ['roles', ...(this.posts ? ['languages'] : []), 'day_parts'].map(name => ({ name, controls: this.controls(name) }));
            groups.forEach(({ name, controls }) => {
                const chosen = controls.some(control => control.checked);
                controls.forEach(control => control.setAttribute('aria-invalid', chosen ? 'false' : 'true'));
                if (!chosen) this.missing.push(name);
            });
            if (this.missing.length) {
                event.preventDefault();
                const group = groups.find(group => this.missing.includes(group.name));
                (group ? group.controls[0] : required.find(control => !control.checked)).focus();
                return;
            }
            this.submitting = true;
        }
    }"
    @submit="submit($event)" @pageshow.window="submitting = false"
    @change="if ($event.target.matches('ui-checkbox')) {
        const group = $event.target.getAttribute('name');
        if (group === 'roles[]') posts = controls('roles').some(control => control.checked && control.value === 'social_media');
        if (!$event.target.checked) return;
        missing = missing.filter(name => name !== $event.target.dataset.validationName);
        $event.target.setAttribute('aria-invalid', 'false');
        if (['roles[]', 'languages[]', 'day_parts[]'].includes(group)) {
            missing = missing.filter(name => name !== group.slice(0, -2));
            controls(group.slice(0, -2)).forEach(control => control.setAttribute('aria-invalid', 'false'));
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
                                <a href="#{{ preg_replace('/\.\d+$/', '', $field) }}">{{ $error }}</a>
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
        <x-application.field name="phone" label="Mobile number" type="tel" autocomplete="tel" required maxlength="80" hint="Only for reaching you during selection and on the day itself, if email doesn't get through." />
        <x-application.choice-group name="roles" prefix="role" label="Which roles would you like?" hint="Choose one or more." :options="\App\Support\VolunteerRoles::options()" client-error="Choose at least one role to continue." />
    </fieldset>

    <fieldset class="application-form__group">
        <legend>About your work</legend>
        <x-application.field name="portfolio" label="Examples of your work" type="url" required maxlength="2000" placeholder="https://" hint="A portfolio, a channel, or an account you've run." />
        <x-application.field name="role_notes" label="Anything we should know for your role?" rows="3" maxlength="2000" hint="Equipment, tools or events you've covered. Please leave out health or other sensitive details." />
        <div x-show="posts">
            <x-application.choice-group name="languages" prefix="language" label="Which languages can you post in?" hint="We post in English, Dutch and French." :options="['English' => 'English', 'Dutch' => 'Dutch', 'French' => 'French']" client-error="Choose at least one language to continue." />
        </div>
    </fieldset>

    <fieldset class="application-form__group">
        <legend>Availability</legend>
        <x-application.checkbox name="date_confirmed" id="date_confirmed" required :checked="in_array(old('date_confirmed'), ['1', 1, true], true)">I have {{ $edition['dates'] }} in my calendar.</x-application.checkbox>
        <x-application.choice-group name="day_parts" prefix="day-part" label="When can you be there?" hint="Choose one or both." :options="\App\Support\VolunteerRoles::dayParts()" client-error="Choose when you can be there to continue." />
    </fieldset>

    <div class="application-form__send">
        <flux:button class="application-form__submit" type="submit" variant="primary" :loading="false" x-bind:disabled="submitting" x-bind:aria-busy="submitting.toString()">
            <span x-text="submitting ? 'Sending application…' : 'Send volunteer application'">Send volunteer application</span>
        </flux:button>
        <flux:text class="application-form__sending" role="status" x-cloak x-show="submitting">Sending your application. Please wait for confirmation.</flux:text>
    </div>
</form>
