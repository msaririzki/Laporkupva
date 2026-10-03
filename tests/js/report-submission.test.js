import assert from 'node:assert/strict';
import { test } from 'node:test';
import { initReportSubmission } from '../../resources/js/report-submission.js';

const createForm = (canSubmit = () => true) => {
    const form = new EventTarget();
    const page = new EventTarget();
    const attributes = new Map();
    const label = { textContent: 'Kirim laporan' };
    const spinner = {
        hidden: true,
        classList: { toggle: (_, hidden) => { spinner.hidden = hidden; } },
    };
    const button = {
        disabled: false,
        attributes: new Map(),
        querySelector: (selector) => selector === '[data-submit-label]' ? label : spinner,
        setAttribute(name, value) { this.attributes.set(name, value); },
    };
    const controls = [{ disabled: false }, { disabled: true }];
    form.setAttribute = (name, value) => attributes.set(name, value);
    form.removeAttribute = (name) => attributes.delete(name);
    const submission = initReportSubmission({ form, button, controls, canSubmit, page });
    const submit = () => form.dispatchEvent(new Event('submit', { cancelable: true }));

    return { form, page, attributes, label, spinner, button, controls, submission, submit };
};

test('sending shows a spinner, locks navigation, and rejects a second submission', () => {
    const state = createForm();

    assert.equal(state.submit(), true);
    assert.equal(state.label.textContent, 'Mengirim…');
    assert.equal(state.spinner.hidden, false);
    assert.equal(state.button.disabled, true);
    assert.equal(state.button.attributes.get('aria-busy'), 'true');
    assert.equal(state.attributes.get('aria-busy'), 'true');
    assert.deepEqual(state.controls.map((control) => control.disabled), [true, true]);
    assert.equal(state.submit(), false);
});

test('invalid input does not lock the form or show the sending animation', () => {
    const state = createForm(() => false);

    assert.equal(state.submit(), false);
    assert.equal(state.button.disabled, false);
    assert.equal(state.spinner.hidden, true);
    assert.equal(state.label.textContent, 'Kirim laporan');
    assert.equal(state.attributes.has('aria-busy'), false);
});

test('preparing evidence blocks sending until the files are ready', () => {
    const state = createForm();

    state.submission.setPreparing(true);
    assert.equal(state.label.textContent, 'Menyiapkan…');
    assert.equal(state.spinner.hidden, false);
    assert.equal(state.submit(), false);
    state.submission.setPreparing(false);
    assert.equal(state.button.disabled, false);
    assert.equal(state.spinner.hidden, true);
    assert.equal(state.submit(), true);
});

test('returning to the page restores the original navigation states and allows sending', () => {
    const state = createForm();
    state.submit();

    state.page.dispatchEvent(new Event('pageshow'));

    assert.equal(state.button.disabled, false);
    assert.equal(state.label.textContent, 'Kirim laporan');
    assert.equal(state.spinner.hidden, true);
    assert.equal(state.attributes.has('aria-busy'), false);
    assert.deepEqual(state.controls.map((control) => control.disabled), [false, true]);
    assert.equal(state.submit(), true);
});

test('a submission canceled by another handler does not enter the sending state', () => {
    const state = createForm();
    const event = new Event('submit', { cancelable: true });
    event.preventDefault();

    state.form.dispatchEvent(event);

    assert.equal(state.button.disabled, false);
    assert.equal(state.spinner.hidden, true);
});
