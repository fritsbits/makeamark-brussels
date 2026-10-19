const { chromium } = require('playwright');
const assert = require('node:assert/strict');

const base = process.env.BASE_URL || 'https://makeamark-southern-ocean.test';
const commitments = ['brussels_eligibility', 'assets_ready', 'planning_meeting', 'feedback_session', 'evening_presentation'];
const fields = {
    organisation_name: ['input', 'text', true, 255],
    mission: ['textarea', 'textarea', true, 5000],
    website: ['input', 'url', false, 2000],
    social_media: ['textarea', 'textarea', false, 2000],
    challenge: ['textarea', 'textarea', true, 5000],
    contact_name: ['input', 'text', true, 255],
    email: ['input', 'email', true, 255],
    phone: ['input', 'tel', true, 80],
};
const answers = {
    organisation_name: 'Équipe علي 山田 – browser fixture',
    mission: 'We support people in Brussels.',
    website: 'https://example.com',
    social_media: 'https://example.com/social',
    challenge: 'A clear identity and practical communication materials.',
    contact_name: 'Browser fixture',
    email: 'fixture@example.com',
    phone: '+32 123 45 67 89',
};
const button = page => page.getByRole('button', { name: 'Send organisation application' });
const ready = async page => {
    await page.goto(base + '/organisations');
    await page.waitForLoadState('networkidle');
    await page.evaluate(() => document.fonts.ready);
};
async function fill(page) {
    for (const [name, value] of Object.entries(answers)) await page.locator(`[name="${name}"]`).fill(value);
}
async function assertContract(page) {
    assert.equal(await page.locator('ui-checkbox[data-flux-checkbox]').count(), 8);
    assert.equal(await page.locator('input[data-flux-control]').count(), 5);
    assert.equal(await page.locator('textarea[data-flux-textarea]').count(), 3);
    assert.equal(await page.locator('button[type=submit][data-flux-button]').count(), 1);
    for (const [name, [tag, type, required, maxLength]] of Object.entries(fields)) {
        const actual = await page.locator(`${tag}[name="${name}"]`).evaluate(el => ({ type: el.type, required: el.required, maxLength: el.maxLength }));
        assert.deepEqual(actual, { type, required, maxLength }, name + ' contract retained');
    }
    for (const name of commitments) {
        const control = page.locator(`ui-checkbox[name="${name}"]`);
        assert.equal(await control.getAttribute('value'), '1');
        assert.notEqual(await control.getAttribute('required'), null, name + ' remains required');
    }
    assert.deepEqual(await page.locator('ui-checkbox[name="languages[]"]').evaluateAll(controls => controls.map(el => ({ value: el.getAttribute('value'), required: el.hasAttribute('required') }))), [
        { value: 'English', required: false }, { value: 'Dutch', required: false }, { value: 'French', required: false },
    ]);
    assert.equal(await page.locator('input[name=_submission_token]').inputValue().then(value => value.length), 97, 'issued organisation replay token');
}

(async () => {
    const browser = await chromium.launch();
    const measurements = [];
    try {
        for (const width of [1440, 900, 720, 390, 320]) {
            const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width, height: 900 } });
            const page = await context.newPage();
            page.setDefaultTimeout(15000);
            const errors = [];
            page.on('pageerror', error => errors.push(error.message));
            let posts = 0;
            let serverErrorsAllowed = false;
            // Every potentially valid POST is intercepted. Only explicitly invalid email payloads reach Laravel.
            await page.route('**/organisations/apply', async route => {
                if (route.request().method() !== 'POST') return route.continue();
                posts++;
                const data = new URLSearchParams(route.request().postData());
                if (serverErrorsAllowed && data.get('email') === 'invalid') return route.continue();
                await route.fulfill({ status: 200, contentType: 'text/html', body: '<p>Browser check intercepted submission. No application saved.</p>' });
            });

            await ready(page);
            await assertContract(page);
            assert.equal(await page.locator('#languages').getAttribute('aria-describedby'), 'languages-error', 'hidden client error is excluded before validation');
            await fill(page);

            const english = page.locator('#language-0-control');
            await english.focus();
            await page.keyboard.press('Space');
            assert.equal(await english.getAttribute('aria-checked'), 'true');
            assert.deepEqual(await page.locator('form').evaluate(form => new FormData(form).getAll('languages[]')), ['English']);
            await page.keyboard.press('Space');
            assert.equal(await english.getAttribute('aria-checked'), 'false');

            await button(page).click();
            await page.waitForFunction(() => document.querySelectorAll('.application-form__client-error').length === 6 && [...document.querySelectorAll('.application-form__client-error')].every(el => getComputedStyle(el).display !== 'none'));
            assert.equal(posts, 0, 'required commitments and language group block POST');
            assert.equal(await page.locator('.application-form__client-error:visible').count(), 6);
            assert.equal(await page.locator('#brussels_eligibility-control').getAttribute('aria-invalid'), 'true');
            for (const name of commitments) await page.locator(`#${name}-control`).click();
            await button(page).click();
            assert.equal(posts, 0, 'language group still blocks POST when commitments are confirmed');
            assert.equal(await page.locator('#languages-client-error').isVisible(), true);
            assert.equal(await english.evaluate(el => document.activeElement === el), true);
            assert.ok((await english.getAttribute('aria-describedby')).split(/\s+/).includes('languages-client-error'), 'language error is associated with the focused choice');
            await page.keyboard.press('Space');
            await page.waitForFunction(() => [...document.querySelectorAll('.application-form__client-error')].every(el => getComputedStyle(el).display === 'none'));
            assert.equal(await english.getAttribute('aria-invalid'), 'false');
            const formData = await page.locator('form').evaluate(form => [...new FormData(form)]);
            for (const [name, value] of Object.entries(answers)) assert.deepEqual(formData.filter(([key]) => key === name), [[name, value]]);
            for (const name of commitments) assert.deepEqual(formData.filter(([key]) => key === name), [[name, '1']]);
            assert.deepEqual(formData.filter(([key]) => key === 'languages[]'), [['languages[]', 'English']]);
            assert.equal(await page.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0, 'no horizontal overflow');
            const brokenReferences = await page.evaluate(() => [...document.querySelectorAll('[aria-labelledby],[aria-describedby]')].flatMap(el => ['aria-labelledby', 'aria-describedby'].flatMap(attr => (el.getAttribute(attr) || '').split(/\s+/).filter(Boolean).filter(id => !document.getElementById(id)))));
            assert.deepEqual(brokenReferences, []);

            // Keep the original document available to inspect Alpine's busy state.
            await page.locator('form').evaluate(form => {
                form.addEventListener('submit', event => event.preventDefault(), { once: true });
                form.requestSubmit();
            });
            assert.equal(await page.locator('.application-form__submit').isDisabled(), true);
            assert.equal(await page.locator('.application-form__submit').getAttribute('aria-busy'), 'true');
            assert.equal(await page.getByRole('button', { name: 'Sending application…' }).count(), 1);
            assert.equal(await page.locator('form').evaluate(form => {
                let prevented = false;
                form.addEventListener('submit', event => { prevented = event.defaultPrevented; }, { once: true });
                form.requestSubmit();
                return prevented;
            }), true, 'duplicate submit prevented while busy');
            assert.equal(posts, 0);
            await page.evaluate(() => window.dispatchEvent(new PageTransitionEvent('pageshow', { persisted: true })));
            assert.equal(await button(page).isEnabled(), true);

            if (width === 390) {
                await ready(page);
                await page.locator('[name=organisation_name]').fill('Retained invalid fixture');
                await page.locator('[name=email]').fill('invalid');
                serverErrorsAllowed = true;
                await Promise.all([
                    page.waitForURL('**/organisations#application'),
                    page.locator('form').evaluate(form => { form.noValidate = true; form.submit(); }),
                ]);
                await page.waitForLoadState('networkidle');
                assert.equal(await page.locator('.application-form__errors').count(), 1);
                assert.equal(await page.locator('.application-form__errors').evaluate(el => document.activeElement === el), true);
                assert.equal(await page.locator('[name=organisation_name]').inputValue(), 'Retained invalid fixture');
                assert.equal(await page.locator('[name=email]').inputValue(), 'invalid');
                assert.equal(await page.locator('[name=email]').getAttribute('aria-invalid'), 'true');
                for (const href of await page.locator('.application-form__errors a').evaluateAll(links => links.map(link => link.getAttribute('href')))) assert.equal(await page.locator(href).count(), 1, 'summary target exists');
                await page.locator('.application-form__errors a[href="#email"]').click();
                assert.equal(await page.locator('#email').evaluate(el => document.activeElement === el), true);
            }
            assert.deepEqual(errors, [], 'no page JavaScript errors');
            measurements.push({ width, overflow: 0, controls: 16, languages: ['English', 'Dutch', 'French'] });
            await context.close();
        }

        const nojs = await browser.newContext({ ignoreHTTPSErrors: true, javaScriptEnabled: false, viewport: { width: 390, height: 900 } });
        const staticPage = await nojs.newPage();
        await ready(staticPage);
        assert.equal(await staticPage.locator('#languages').getAttribute('aria-describedby'), 'languages-error', 'no-JS group excludes unused client error');
        assert.equal(await staticPage.locator('input[type=checkbox]').count(), 8);
        for (const name of commitments) {
            const control = staticPage.locator(`input[type=checkbox][name="${name}"]`);
            assert.equal(await control.isVisible(), true);
            assert.notEqual(await control.getAttribute('required'), null);
            await control.check();
        }
        const nativeLanguages = staticPage.locator('input[type=checkbox][name="languages[]"]');
        assert.equal(await nativeLanguages.count(), 3);
        assert.deepEqual(await nativeLanguages.evaluateAll(controls => controls.map(el => el.required)), [false, false, false]);
        await nativeLanguages.first().check();
        const staticData = await staticPage.locator('form').evaluate(form => [...new FormData(form)]);
        for (const name of commitments) assert.deepEqual(staticData.filter(([key]) => key === name), [[name, '1']]);
        assert.deepEqual(staticData.filter(([key]) => key === 'languages[]'), [['languages[]', 'English']]);
        assert.equal(await button(staticPage).isEnabled(), true);
        assert.equal(await staticPage.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0);
        await nojs.close();

        const contrast = await browser.newContext({ ignoreHTTPSErrors: true, forcedColors: 'active', viewport: { width: 720, height: 900 } });
        const contrastPage = await contrast.newPage();
        await ready(contrastPage);
        await contrastPage.locator('#language-0-control').focus();
        await contrastPage.keyboard.press('Space');
        assert.equal(await contrastPage.locator('#language-0-control').getAttribute('aria-checked'), 'true');
        assert.equal(await contrastPage.evaluate(() => document.documentElement.scrollWidth - innerWidth), 0);
        assert.ok(await contrastPage.locator('#language-0').evaluate(el => parseFloat(getComputedStyle(el.querySelector('label')).borderTopWidth) >= 1));
        await contrast.close();
        console.log(JSON.stringify({ passed: true, widths: measurements, checks: ['Flux controls and field contract', 'keyboard and FormData', 'required commitments and one-or-more languages', 'linked inline server errors and retained draft', 'busy/duplicate/pageshow', 'no-JS native fallback', 'forced colors'], screenshots: 0, validApplicationsCreated: 0 }, null, 2));
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exit(1); });
