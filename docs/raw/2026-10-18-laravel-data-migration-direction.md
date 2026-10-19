# Laravel data migration direction — source capture

- Source: owner message in the UX planning conversation on 2026-10-18.
- Original wording: “I dont want to use the Airtable anymore. Even for the first version I want to import all the content of the Airtable into the Laravel site.”
- Product decision: Airtable is not the ongoing system for the new version. Import its content into Laravel and use Laravel for new signup data.
- Interpretation for planning: “all content” means all records and their data/relationships across the five captured Airtable tables. Verify whether attached media, historical field values, and saved-view behavior are included during migration inventory; the source capture is not a complete Airtable export.
