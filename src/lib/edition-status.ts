export type EditionStatus = 'open' | 'closed' | 'selected' | 'done';

export interface StatusLabels {
  open: string;
  closed: string;
  selected: string;
  done: string;
}

export interface PillModel {
  label: string;
  href?: string;
}

export function statusPill(input: {
  status: EditionStatus;
  year: number;
  applyUrl?: string;
  instagramUrl: string;
  labels: StatusLabels;
}): PillModel {
  const { status, year, applyUrl, instagramUrl, labels } = input;
  const withYear = (label: string) => label.replaceAll('{year}', String(year));
  if (status === 'open' && applyUrl) return { label: withYear(labels.open), href: applyUrl };
  if (status === 'selected') return { label: withYear(labels.selected) };
  if (status === 'done') return { label: withYear(labels.done), href: instagramUrl };
  return { label: withYear(labels.closed) };
}
