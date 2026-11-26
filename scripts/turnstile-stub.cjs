// Deterministic browser-only provider boundary. Real server verification remains enabled.
module.exports = async function stubTurnstile(page) {
    await page.route('https://challenges.cloudflare.com/turnstile/v0/api.js*', route => route.fulfill({
        contentType: 'application/javascript', body: `(() => {
            const widgets = [];
            window.turnstile = {
                render(host, options) { widgets.push(options); return widgets.length - 1; },
                reset() {},
                execute(id) {
                    const options = widgets[id];
                    window.verificationExecutions = (window.verificationExecutions || 0) + 1;
                    setTimeout(() => window.verificationFailure ? options['error-callback']() : options.callback('fixture-' + options.action.slice(12)), 20);
                }
            };
            window.applicationTurnstileReady();
        })();`,
    }));
};
