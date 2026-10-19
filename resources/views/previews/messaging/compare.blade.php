<x-layouts.app :site="$site" page-title="Homepage comparison · Make a Mark Brussels">
    <main id="main" class="messaging-comparison" tabindex="-1"
        x-data="{ viewport: 1440, scale: 1, height: 900, fit() { this.scale = Math.min(1, this.$refs.stage.clientWidth / this.viewport); this.height = this.$refs.stage.clientHeight / this.scale; } }"
        x-init="$nextTick(() => { new ResizeObserver(() => fit()).observe($refs.stage); fit(); })"
        x-effect="viewport; $nextTick(() => fit())">
        <header class="messaging-comparison__toolbar">
            <flux:heading level="1" class="h-display messaging-comparison__title">Homepage comparison</flux:heading>
            <div class="messaging-comparison__controls">
                <flux:select class="messaging-comparison__select" aria-label="Preview viewport" x-model.number="viewport">
                    <flux:select.option value="1440">Desktop · 1440px</flux:select.option>
                    <flux:select.option value="390">Mobile · 390px</flux:select.option>
                </flux:select>
                <flux:text class="messaging-comparison__hint">Scroll each page independently.</flux:text>
            </div>
        </header>
        <div class="messaging-comparison__panes">
            <section class="messaging-comparison__pane" aria-labelledby="original-label">
                <div class="messaging-comparison__label">
                    <flux:heading level="2" id="original-label">Original</flux:heading>
                    <a href="/tools/messaging/original" target="_blank" rel="noopener">Open full size</a>
                </div>
                <div class="messaging-comparison__stage" x-ref="stage">
                    <iframe title="Original homepage" src="/tools/messaging/original" class="messaging-comparison__frame" :style="{ width: viewport + 'px', height: height + 'px', transform: 'scale(' + scale + ')' }"></iframe>
                </div>
            </section>
            <section class="messaging-comparison__pane" aria-labelledby="distilled-label">
                <div class="messaging-comparison__label">
                    <flux:heading level="2" id="distilled-label">Distilled</flux:heading>
                    <a href="/" target="_blank" rel="noopener">Open full size</a>
                </div>
                <div class="messaging-comparison__stage">
                    <iframe title="Distilled homepage" src="/" class="messaging-comparison__frame" :style="{ width: viewport + 'px', height: height + 'px', transform: 'scale(' + scale + ')' }"></iframe>
                </div>
            </section>
        </div>
        <noscript><p class="container">Open the <a href="/tools/messaging/original">original homepage</a> and <a href="/">distilled homepage</a> in separate windows for a full-size comparison.</p></noscript>
    </main>
</x-layouts.app>
