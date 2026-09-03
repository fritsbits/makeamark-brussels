import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';

const html = readFileSync(new URL('../dist/index.html', import.meta.url), 'utf8');

test('one h1', () => {
  assert.equal((html.match(/<h1[\s>]/g) ?? []).length, 1);
});

test('every section anchor exists once', () => {
  for (const id of ['top', 'edition', 'makers', 'organisations', 'about']) {
    assert.equal((html.match(new RegExp(`id="${id}"`, 'g')) ?? []).length, 1, id);
  }
});

test('nav links point at sections', () => {
  for (const href of ['#edition', '#makers', '#organisations']) {
    assert.ok(html.includes(`href="${href}"`), href);
  }
});

test('external links from the doc are present', () => {
  for (const href of [
    'https://klimaatzaak.eu/en/',
    'https://www.instagram.com/lets_makeamarkbxl/',
    'https://www.bruzz.be/samenleving/creatieve-marathon-make-mark-maakt-brussel-opnieuw-een-beetje-beter-2023-03-19',
    'https://www.letsmakeamark.org/',
    'https://www.gwentibold.com/',
    'https://www.brusseleir.eu/neus/piet-lambrechts-brussel-komen-kijken-en-het-willen-zien/',
    'mailto:makeamarkbxl@gmail.com',
  ]) {
    assert.ok(html.includes(`href="${href}"`), href);
  }
});

test('no undefined or [object Object] leaked into the html', () => {
  assert.ok(!html.includes('undefined'));
  assert.ok(!html.includes('[object Object]'));
});

test('55 organisations are listed', () => {
  assert.equal((html.match(/class="org"/g) ?? []).length, 55);
});

test('the status pill reads closed for 2026', () => {
  assert.ok(html.includes('Applications are closed for 2026'));
});

test('the answers folded out of the faq are still on the page', () => {
  for (const phrase of [
    'drinks, delicious snacks and good vibes',
    'Organisations pay nothing to take part',
    'organise Make a Mark in your own city',
  ]) {
    assert.ok(html.includes(phrase), phrase);
  }
});
