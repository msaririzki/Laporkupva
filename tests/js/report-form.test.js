import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { test } from 'node:test';
import vm from 'node:vm';

const source = readFileSync(new URL('../../resources/js/report-form.js', import.meta.url), 'utf8')
    .replace(/^import .*;\r?\n/gm, '')
    .replace('export const initCustomSelect', 'const initCustomSelect');

class Field {
    value = '';
    disabled = false;
    checked = false;
    textContent = '';
    dataset = {};
    style = {};
    selectedOptions = [{ textContent: 'Kejadian' }];
    handlers = new Map();
    classes = new Set();
    attributes = new Map();
    classList = {
        contains: (name) => this.classes.has(name),
        add: (...names) => names.forEach((name) => this.classes.add(name)),
        remove: (...names) => names.forEach((name) => this.classes.delete(name)),
        toggle: (name, enabled) => enabled ? this.classes.add(name) : this.classes.delete(name),
    };
    addEventListener(name, callback) { this.handlers.set(name, [...(this.handlers.get(name) ?? []), callback]); }
    dispatchEvent(event) { this.handlers.get(event.type)?.forEach((callback) => callback(event)); }
    async fire(name, event = {}) { for (const callback of this.handlers.get(name) ?? []) await callback(event); }
    setAttribute(name, value) { this.attributes.set(name, value); }
    removeAttribute(name) { this.attributes.delete(name); }
    checkValidity() { return true; }
    reportValidity() {}
    focus() {}
    querySelector() { return null; }
    querySelectorAll() { return []; }
    getBoundingClientRect() { return { width: 350 }; }
}

const createForm = () => {
    const fields = new Map();
    const field = (selector) => {
        if (!fields.has(selector)) fields.set(selector, new Field());
        return fields.get(selector);
    };
    const identityFields = ['reporter_name', 'reporter_email', 'reporter_phone'].map((name) => {
        const input = field(`#${name}`);
        input.name = name;
        return input;
    });
    field('#reporter_name').value = 'Pelapor Uji';
    field('#reporter_email').value = 'pelapor@example.com';
    const steps = Array.from({ length: 4 }, (_, index) => {
        const step = new Field();
        step.dataset.step = String(index + 1);
        step.querySelectorAll = () => index === 0 ? identityFields : [];
        return step;
    });
    const progress = steps.map((step) => {
        const node = new Field();
        node.dataset.progress = step.dataset.step;
        return node;
    });
    const form = field('#report-form');
    form.dataset.verificationUrl = '/lapor/verifikasi';
    form.querySelector = (selector) => selector === '.is-invalid, .form-error' ? null : field(selector);
    form.querySelectorAll = (selector) => selector === '.form-step' ? steps : identityFields;
    const page = new Field();
    page.scrollTo = () => {};
    page.setTimeout = (callback, delay) => delay < 1000 ? 0 : setTimeout(callback, delay);
    page.clearTimeout = clearTimeout;
    const calls = { render: 0, execute: 0, reset: 0, requests: [] };
    const map = { setView() { return this; }, panTo() {}, on(name, callback) { calls[name] = callback; } };
    const marker = { addTo() { return this; }, on() {}, setLatLng() {} };
    page.TamboraMap = { createBaseLayers() {}, addStyleControl() {}, enableSafeScrollZoom() {} };
    page.turnstile = {
        render: (_, options) => { calls.render += 1; calls.options = options; return 'widget'; },
        execute: () => { calls.execute += 1; },
        reset: () => { calls.reset += 1; },
    };
    const document = {
        querySelector: field,
        querySelectorAll: (selector) => selector === '[data-progress]' ? progress : [],
        addEventListener() {},
    };
    const context = {
        document, window: page, URL, URLSearchParams, AbortController, Event,
        markerIconUrl: '', markerIconRetinaUrl: '', markerShadowUrl: '',
        L: { Icon: { Default: { mergeOptions() {} } }, map: () => map, marker: () => marker },
        initReportSubmission: (options) => { calls.submission = options; return { setPreparing() {} }; },
        fetch: async (url, options) => {
            calls.requests.push({ url, options });
            return { ok: true, json: async () => ({ verification_id: 'approved-form', expires_at: Math.floor(Date.now() / 1000) + 2700 }) };
        },
    };
    vm.runInNewContext(source, context);
    return { field, page, calls, steps, context };
};

test('captcha never runs on page load and requires explicit user action', async () => {
    const { calls, field } = createForm();
    assert.equal(calls.render, 0);
    assert.equal(calls.execute, 0);

    await field('#start-verification').fire('click');

    assert.equal(calls.execute, 1);
    assert.equal(calls.options.execution, 'execute');
    assert.equal(calls.options['refresh-expired'], 'manual');
    assert.equal(calls.options.retry, 'never');
});

test('next cannot skip verification and sends no evidence in the initial check', async () => {
    const { calls, field, steps } = createForm();
    await field('#next-step').fire('click');
    assert.equal(calls.requests.length, 0);
    assert.equal(steps[1].classes.has('hidden'), true);

    await field('#start-verification').fire('click');
    calls.options.callback('fresh-token');
    await field('#next-step').fire('click');

    assert.equal(calls.requests.length, 1);
    assert.equal(calls.requests[0].url, '/lapor/verifikasi');
    assert.equal(calls.requests[0].options.body.get('reporter_email'), 'pelapor@example.com');
    assert.equal(calls.requests[0].options.body.get('cf-turnstile-response'), 'fresh-token');
    assert.equal(calls.requests[0].options.body.has('evidence'), false);
    assert.equal(field('#verification_id').value, 'approved-form');
    assert.equal(steps[1].classes.has('hidden'), false);
});

test('editing identity or returning from browser cache requires fresh verification', async () => {
    const { calls, field, page, steps } = createForm();
    await field('#start-verification').fire('click');
    calls.options.callback('fresh-token');
    await field('#next-step').fire('click');

    await field('#reporter_email').fire('input');
    assert.equal(field('#verification_id').value, '');
    assert.equal(calls.submission.canSubmit(), false);
    assert.equal(steps[0].classes.has('hidden'), false);

    await page.fire('pageshow', { persisted: true });
    assert.equal(field('#verification_id').value, '');
    assert.equal(field('#next-step').disabled, false);
});

test('double clicks while checking cannot duplicate the verification request', async () => {
    const { calls, field, context } = createForm();
    let resolveRequest;
    context.fetch = async () => {
        calls.requests.push({});
        return new Promise((resolve) => { resolveRequest = resolve; });
    };
    await field('#start-verification').fire('click');
    calls.options.callback('fresh-token');
    const pending = field('#next-step').fire('click');
    await field('#next-step').fire('click');
    assert.equal(calls.requests.length, 1);
    resolveRequest({ ok: true, json: async () => ({ verification_id: 'approved-form', expires_at: Math.floor(Date.now() / 1000) + 2700 }) });
    await pending;
    assert.equal(field('#next-step').disabled, false);
});

test('location cannot use a default map center and changing the pin resets confirmation', async () => {
    const { calls, field, steps } = createForm();
    await field('#start-verification').fire('click');
    calls.options.callback('fresh-token');
    await field('#next-step').fire('click');
    await field('#next-step').fire('click');
    assert.equal(steps[2].classes.has('hidden'), false);
    await field('#next-step').fire('click');
    assert.equal(steps[3].classes.has('hidden'), true);
    assert.match(field('#map-message').textContent, /Pilih titik/);

    calls.click({ latlng: { lat: -8.58, lng: 116.12 } });
    assert.equal(field('#latitude').value, '-8.5800000');
    assert.equal(field('#longitude').value, '116.1200000');
    field('#location_confirmed').checked = true;
    calls.click({ latlng: { lat: -8.59, lng: 116.13 } });
    assert.equal(field('#location_confirmed').checked, false);
});
