<x-layouts.app :site="$site" page-title="Risoprint studio · Make a Mark" page-description="An internal photo treatment tool.">
    <main id="main" class="riso" x-data="risoStudio" tabindex="-1">
        <header class="riso__header">
            <a href="/" class="riso__brand" aria-label="Make a Mark home">Make a Mark<span>Brussels</span></a>
            <span class="riso__tool-label">Photo tools / Risoprint</span>
            <flux:button class="riso__button" x-on:click="$refs.photoInput.click()">Change photo</flux:button>
            <input x-ref="photoInput" type="file" accept="image/jpeg,image/png,image/webp" x-on:change="upload($event)" hidden aria-label="Choose a photo">
            <input x-ref="settingsInput" type="file" accept="application/json,.json" x-on:change="importSettings($event)" hidden aria-label="Choose treatment settings">
        </header>

        <div class="riso__intro">
            <flux:heading level="1" class="riso__title">A little ink.<br>A lot of character.</flux:heading>
            <flux:text class="riso__description">Your photo, through two inks. <br>Find the grain, colour and happy imperfections<br class="riso__desktop-break"> that make it yours.</flux:text>
        </div>

        <div class="riso__workspace">
            <section class="riso__preview" aria-label="Photo preview" :aria-busy="busy">
                <div class="riso__preview-bar">
                    <span class="riso__preview-label">Print preview</span>
                    <div class="riso__compare" role="group" aria-label="Preview mode">
                        <button type="button" x-on:click="compare(false)" :aria-pressed="!original">Risoprint</button>
                        <button type="button" x-on:click="compare(true)" :aria-pressed="original">Original</button>
                    </div>
                </div>
                <div class="riso__artboard">
                    <canvas x-ref="preview" class="riso__canvas" role="img" aria-label="Live preview of your photo with the selected risoprint treatment"></canvas>
                    <span class="riso__loading" x-show="!ready">Preparing your print…</span>
                </div>
                <div class="riso__photo-meta"><span x-text="filename"></span><span x-text="dimensions"></span></div>
                <flux:text class="riso__local-note">Photos stay in your browser. Export size changes dimensions, not source detail.</flux:text>
                <p class="riso__error" role="alert" x-show="error" x-text="error" x-cloak></p>
                <p class="riso__notice" role="status" x-text="notice" x-cloak></p>
            </section>

            <aside class="riso__controls" aria-label="Treatment settings">
                <div class="riso__section-top"><flux:heading level="2" class="riso__section-title">The treatment</flux:heading><button class="riso__text-button" type="button" x-on:click="applyPreset('reference')">Reset</button></div>
                <div class="riso__presets" role="group" aria-label="Treatment presets">
                    <button type="button" :aria-pressed="preset === 'reference'" x-on:click="applyPreset('reference')">Reference</button>
                    <button type="button" :aria-pressed="preset === 'soft'" x-on:click="applyPreset('soft')">Soft print</button>
                    <button type="button" :aria-pressed="preset === 'red'" x-on:click="applyPreset('red')">Red only</button>
                </div>

                <fieldset class="riso__fieldset"><legend>Inks & paper</legend>
                    <div class="riso__colours">
                        @foreach (['red' => 'Midtones', 'blue' => 'Shadows', 'paper' => 'Paper'] as $key => $label)
                            <label class="riso__colour"><input type="color" x-model="settings.{{ $key }}" aria-label="{{ $label }} colour"><span>{{ $label }}</span><small x-text="settings.{{ $key }}.toUpperCase()"></small></label>
                        @endforeach
                    </div>
                    @foreach (['redOpacity' => 'Midtone ink', 'blueOpacity' => 'Shadow ink'] as $key => $label)
                        <label class="riso__slider-label" for="{{ $key }}">{{ $label }}<output x-text="settings.{{ $key }} + '%'" for="{{ $key }}"></output></label>
                        <input class="riso__range" id="{{ $key }}" type="range" min="0" max="100" step="1" x-model.number="settings.{{ $key }}">
                    @endforeach
                </fieldset>

                <fieldset class="riso__fieldset"><legend>Make your mark</legend>
                    @foreach ([
                        ['contrast', 'Contrast', -40, 80, 1, ''],
                        ['exposure', 'Exposure', -40, 40, 1, ''],
                        ['balance', 'Shadow coverage', 0, 100, 1, '%'],
                        ['grain', 'Ink grain', 0, 100, 1, '%'],
                        ['grainSize', 'Grain size', 0.6, 3, 0.1, ''],
                        ['offset', 'Ink misalignment', 0, 10, 0.5, ' px'],
                        ['texture', 'Paper texture', 0, 100, 1, '%'],
                        ['margin', 'Paper border', 0, 12, 1, '%'],
                    ] as [$key, $label, $min, $max, $step, $unit])
                        <div class="riso__slider"><label class="riso__slider-label" for="{{ $key }}">{{ $label }}<output for="{{ $key }}" x-text="settings.{{ $key }} + '{{ $unit }}'"></output></label><input class="riso__range" id="{{ $key }}" type="range" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" x-model.number="settings.{{ $key }}"></div>
                    @endforeach
                </fieldset>

                <div class="riso__export">
                    <label class="riso__slider-label" for="export-size">Photo long edge</label>
                    <flux:select id="export-size" x-model="exportWidth" class="riso__select"><flux:select.option value="1600">1600 px</flux:select.option><flux:select.option value="2400">2400 px</flux:select.option><flux:select.option value="3200">3200 px</flux:select.option></flux:select>
                    <flux:button class="riso__button riso__button--primary" x-on:click="exportPng()" x-bind:disabled="!ready || busy"><span x-text="busy ? 'Preparing…' : 'Download PNG'"></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M12 3v12m-5-5 5 5 5-5M5 17v4h14v-4"/></svg></flux:button>
                    <div class="riso__settings-actions"><button class="riso__text-button" type="button" x-on:click="saveSettings()">Save treatment</button><button class="riso__text-button" type="button" x-on:click="$refs.settingsInput.click()">Load treatment</button></div>
                </div>
            </aside>
        </div>
        <footer class="riso__footer"><span>Made to leave a mark.</span><span>A digital risoprint approximation. Every imperfection is adjustable.</span></footer>
    </main>
</x-layouts.app>
