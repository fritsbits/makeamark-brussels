import { test } from 'node:test';
import assert from 'node:assert/strict';

async function setup() {
    const widgets = [];
    const scripts = [];
    globalThis.window = {};
    globalThis.document = {
        createElement: () => ({ remove() {} }),
        head: { append: script => scripts.push(script) },
    };
    const module = await import(`../../resources/js/application-spam-protection.js?test=${Math.random()}`);
    const api = {
        render: (container, options) => { widgets.push(options); return widgets.length - 1; },
        execute: id => { api.executions.push(id); },
        reset() {}, executions: [],
    };
    const form = () => {
        const token = { value: '' };
        const error = { hidden: true, textContent: '', focus() { this.focused = true; } };
        const host = { dataset: { enabled: 'true', sitekey: 'public', audience: 'makers' }, getBoundingClientRect: () => ({ width: 250 }) };
        return { token, error, values: { name: 'José' }, submits: 0,
            querySelector: selector => selector === '[data-application-verification]' ? host : selector === '[data-verification-error]' ? error : selector === '[data-verification-message]' ? error : selector === '[name="cf-turnstile-response"]' ? token : null,
            ownerDocument: { defaultView: { HTMLFormElement: { prototype: { submit() { this.submits++; } } } } },
        };
    };
    return { module, api, form, scripts, widgets, ready() { window.turnstile = api; window.applicationTurnstileReady(); } };
}
const flush = () => new Promise(resolve => setImmediate(resolve));

test('loads once, waits for API readiness, isolates forms and prevents duplicate native posts', async () => {
    const s = await setup();
    const first = s.form(), second = s.form();
    const a = s.module.submitVerifiedApplication(first);
    const duplicate = s.module.submitVerifiedApplication(first);
    const b = s.module.submitVerifiedApplication(second);
    assert.equal(s.scripts.length, 1);
    assert.equal(s.widgets.length, 0);
    s.ready(); await flush();
    assert.equal(s.widgets.length, 2);
    assert.equal(s.widgets[0].size, 'compact');
    assert.equal(s.widgets[0].execution, 'execute');
    assert.equal(s.widgets[0].action, 'application_makers');
    s.widgets[0].callback('first-token');
    await a; await duplicate;
    assert.equal(first.submits, 1);
    assert.equal(second.submits, 0);
    s.widgets[1].callback('second-token'); await b;
    assert.equal(second.token.value, 'second-token');
    await s.module.submitVerifiedApplication(first);
    assert.equal(first.submits, 1, 'locked while POST is in flight');
});

for (const failure of ['error-callback', 'expired-callback', 'timeout-callback']) {
    test(`${failure} keeps answers and permits a fresh retry`, async () => {
        const s = await setup(); const form = s.form();
        const a = s.module.submitVerifiedApplication(form);
        s.ready(); await flush();
        const rejection = assert.rejects(a);
        s.widgets[0][failure](); await rejection;
        assert.equal(form.submits, 0);
        assert.equal(form.error.hidden, false);
        assert.equal(form.error.focused, true);
        assert.equal(form.values.name, 'José');
        const b = s.module.submitVerifiedApplication(form); await flush();
        s.widgets[0].callback('fresh-token'); await b;
        assert.equal(s.widgets.length, 1);
        assert.equal(form.token.value, 'fresh-token');
        assert.equal(form.submits, 1);
    });
}

test('blocked script times out visibly and can be loaded again', async t => {
    t.mock.timers.enable({ apis: ['setTimeout'] });
    const s = await setup(); const form = s.form();
    const a = s.module.submitVerifiedApplication(form);
    const rejected = assert.rejects(a);
    t.mock.timers.tick(15001); await rejected;
    assert.equal(form.error.hidden, false);
    const b = s.module.submitVerifiedApplication(form);
    s.ready(); await flush(); s.widgets[0].callback('retry'); await b;
    assert.equal(s.scripts.length, 2);
    assert.equal(form.submits, 1);
});

test('pageshow reset permits another attempt without reusing a token', async () => {
    const s = await setup(); const form = s.form();
    const a = s.module.submitVerifiedApplication(form); s.ready(); await flush();
    s.widgets[0].callback('old'); await a;
    s.module.resetApplicationVerification(form);
    const b = s.module.submitVerifiedApplication(form); await flush();
    assert.equal(form.token.value, '');
    s.widgets[0].callback('new'); await b;
    assert.equal(form.submits, 2);
});
