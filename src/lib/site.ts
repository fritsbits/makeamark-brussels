import { z } from 'astro/zod';
import siteJson from '../content/data/site.json';
import storiesJson from '../content/data/stories.json';

const link = z.object({ label: z.string(), href: z.string() });

const SiteSchema = z.object({
  name: z.string(),
  shortName: z.string(),
  domain: z.url(),
  instagramUrl: z.url(),
  email: z.email(),
  globalSiteUrl: z.url(),
  firstEditionYear: z.number().int(),
  meta: z.object({ title: z.string(), description: z.string() }),
  nav: z.array(link),
  hero: z.object({ headline: z.string(), intro: z.string(), instagramLabel: z.string() }),
  makers: z.object({
    heading: z.string(),
    steps: z.array(z.object({ title: z.string(), text: z.string() })),
    closing: z.string(),
  }),
  organisations: z.object({ heading: z.string(), intro: z.string() }),
  faq: z.object({ heading: z.string() }),
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
  footer: z.object({ globalLabel: z.string(), instagramLabel: z.string(), emailLabel: z.string() }),
});

const StorySchema = z.object({ file: z.string(), alt: z.string(), credit: z.string() });

export type Site = z.infer<typeof SiteSchema>;
export type Story = z.infer<typeof StorySchema>;

export const site: Site = SiteSchema.parse(siteJson);
export const stories: Story[] = z.array(StorySchema).parse(storiesJson);
