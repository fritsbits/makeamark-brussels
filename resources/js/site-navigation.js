function register() {
    window.Alpine.data('siteNavigation', () => {
        const inertState = new Map();
        let mobile;
        let resize;

        return {
            open: false,
            compact: window.scrollY > 24,

            init() {
                mobile = window.matchMedia('(max-width: 760px)');
                this.$watch('open', () => this.syncBackground());
                resize = () => { if (!mobile.matches) this.close(false); };
                mobile.addEventListener('change', resize);
            },

            toggle() {
                if (this.open) this.close();
                else this.open = true;
            },

            close(restoreFocus = true) {
                if (!this.open) return;
                this.open = false;
                this.restoreBackground();
                if (restoreFocus) this.$nextTick(() => this.$refs.toggle.focus({ preventScroll: true }));
            },

            syncBackground() {
                if (!this.open || !mobile.matches) {
                    this.restoreBackground();
                    return;
                }
                document.querySelectorAll('main, .footer, .skip-link').forEach(element => {
                    if (!inertState.has(element)) inertState.set(element, element.inert);
                    element.inert = true;
                });
            },

            restoreBackground() {
                inertState.forEach((wasInert, element) => { element.inert = wasInert; });
                inertState.clear();
            },

            trapFocus(event) {
                if (!this.open || !mobile.matches) return;
                const controls = [this.$refs.toggle, ...this.$refs.links.querySelectorAll('a[href]')];
                const first = controls[0];
                const last = controls.at(-1);
                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            },

            destroy() {
                this.restoreBackground();
                mobile?.removeEventListener('change', resize);
            },
        };
    });
}

if (window.Alpine) register();
else document.addEventListener('alpine:init', register, { once: true });
