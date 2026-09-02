import { test } from 'node:test';
import assert from 'node:assert/strict';
import { statusPill } from './edition-status.ts';

const instagramUrl = 'https://www.instagram.com/lets_makeamarkbxl/';
const labels = {
  open: 'Apply as a Maker',
  closed: 'Applications are closed for {year}',
  selected: 'Makers have been selected, see you in March',
  done: 'Read about the {year} edition on Instagram',
};

test('open links to the application form', () => {
  const pill = statusPill({ status: 'open', year: 2026, applyUrl: 'https://forms.example/apply', instagramUrl, labels });
  assert.deepEqual(pill, { label: 'Apply as a Maker', href: 'https://forms.example/apply' });
});

test('open without an applyUrl falls back to closed wording (defensive; the schema rejects this at build time)', () => {
  const pill = statusPill({ status: 'open', year: 2026, instagramUrl, labels });
  assert.deepEqual(pill, { label: 'Applications are closed for 2026' });
});

test('closed is a label with the year', () => {
  assert.deepEqual(statusPill({ status: 'closed', year: 2026, instagramUrl, labels }), { label: 'Applications are closed for 2026' });
});

test('selected is a label', () => {
  assert.deepEqual(statusPill({ status: 'selected', year: 2026, instagramUrl, labels }), { label: 'Makers have been selected, see you in March' });
});

test('done links to instagram', () => {
  assert.deepEqual(statusPill({ status: 'done', year: 2026, instagramUrl, labels }), { label: 'Read about the 2026 edition on Instagram', href: instagramUrl });
});
