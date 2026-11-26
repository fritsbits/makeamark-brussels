let loading;
const states = new WeakMap();
const posting = new WeakSet();
const message = 'Verification is temporarily unavailable. Please try again or contact us.';

function loadTurnstile() {
    if (window.turnstile?.render) return Promise.resolve(window.turnstile);
    if (loading) return loading;
    loading = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        const timer = setTimeout(fail, 15000);
        function fail() {
            clearTimeout(timer);
            script.remove();
            loading = undefined;
            reject(new Error(message));
        }
        window.applicationTurnstileReady = () => {
            if (!window.turnstile?.render) return;
            clearTimeout(timer);
            resolve(window.turnstile);
        };
        script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit&onload=applicationTurnstileReady';
        script.async = true;
        script.defer = true;
        script.onerror = fail;
        document.head.append(script);
    });
    return loading;
}

function showError(form) {
    const region = form.querySelector('[data-verification-error]');
    form.querySelector('[data-verification-message]').textContent = message;
    region.hidden = false;
    region.focus();
}

export function verifyApplicationForm(form) {
    const host = form.querySelector('[data-application-verification]');
    if (host.dataset.enabled !== 'true') return Promise.resolve();
    let state = states.get(form);
    if (!state) {
        state = {};
        states.set(form, state);
    }
    if (state.pending) return state.pending;
    const token = form.querySelector('[name="cf-turnstile-response"]');
    token.value = '';
    form.querySelector('[data-verification-error]').hidden = true;
    state.pending = (async () => {
        const api = await loadTurnstile();
        if (!host.dataset.sitekey) throw new Error(message);
        await new Promise((resolve, reject) => {
            const finish = (value) => {
                if (!state.finish) return;
                clearTimeout(state.timer);
                state.finish = null;
                if (typeof value === 'string' && value.length > 0) {
                    token.value = value;
                    resolve();
                } else {
                    token.value = '';
                    reject(new Error(message));
                }
            };
            state.finish = finish;
            state.timer = setTimeout(() => finish(), 120000);
            const fail = () => { state.finish?.(); return true; };
            if (state.widget === undefined) {
                state.widget = api.render(host, {
                    sitekey: host.dataset.sitekey,
                    action: `application_${host.dataset.audience}`,
                    execution: 'execute', appearance: 'interaction-only',
                    size: host.getBoundingClientRect().width < 300 ? 'compact' : 'flexible',
                    'response-field': false, 'refresh-expired': 'manual', 'refresh-timeout': 'manual', retry: 'never',
                    callback: value => state.finish?.(value),
                    'error-callback': fail, 'expired-callback': fail, 'timeout-callback': fail,
                });
            } else {
                api.reset(state.widget);
            }
            api.execute(state.widget);
        });
    })().catch(error => {
        token.value = '';
        showError(form);
        throw error;
    }).finally(() => { state.pending = null; });
    return state.pending;
}

export async function submitVerifiedApplication(form, submitter) {
    if (posting.has(form)) return;
    posting.add(form);
    try {
        await verifyApplicationForm(form);
        // Native submit avoids recursively dispatching Alpine's validation handler.
        if (submitter?.name) {
            const input = form.ownerDocument.createElement('input');
            input.type = 'hidden'; input.name = submitter.name; input.value = submitter.value;
            form.append(input);
        }
        form.ownerDocument.defaultView.HTMLFormElement.prototype.submit.call(form);
    } catch (error) {
        posting.delete(form);
        throw error;
    }
}

export function resetApplicationVerification(form) {
    posting.delete(form);
    const state = states.get(form);
    state?.finish?.();
    const token = form.querySelector('[name="cf-turnstile-response"]');
    if (token) token.value = '';
}

if (typeof window !== 'undefined') {
    window.submitVerifiedApplication = submitVerifiedApplication;
    window.resetApplicationVerification = resetApplicationVerification;
}
