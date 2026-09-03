import { z } from 'astro/zod';
import siteJson from '../content/data/site.json';

const link = z.object({ label: z.string(), href: z.string() });

const SiteSchema = z.object({
  name: z.string(),
  shortName: z.string(),
  domain: z.url(),
  instagramUrl: z.url(),
  bruzzUrl: z.url(),
  email: z.email(),
  globalSiteUrl: z.url(),
  firstEditionYear: z.number().int(),
  meta: z.object({ title: z.string(), description: z.string() }),
  nav: z.array(link),
  hero: z.object({
    headline: z.string(),
    lines: z.array(z.string()),
    intro: z.string(),
    instagramLabel: z.string(),
    bruzzLabel: z.string(),
  }),
  makers: z.object({
    heading: z.string(),
    steps: z.array(z.object({ title: z.string(), text: z.string() })),
    closing: z.string(),
    note: z.string(),
  }),
  organisations: z.object({
    heading: z.string(),
    headingLines: z.array(z.string()),
    note: z.string(),
  }),
  edition: z.object({
    statusLabels: z.object({
      open: z.string(),
      closed: z.string(),
      selected: z.string(),
      done: z.string(),
    }),
  }),
  about: z.object({
    heading: z.string(),
    intro: z.string(),
    organisers: z.array(z.object({ name: z.string(), role: z.string(), url: z.url() })),
    contactLabel: z.string(),
  }),
  footer: z.object({
    globalLabel: z.string(),
    cityLine: z.string(),
    cityLabel: z.string(),
    instagramLabel: z.string(),
    emailLabel: z.string(),
  }),
});

export type Site = z.infer<typeof SiteSchema>;

export const site: Site = SiteSchema.parse(siteJson);