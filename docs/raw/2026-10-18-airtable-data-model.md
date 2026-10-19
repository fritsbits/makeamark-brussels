# Airtable data model — source capture

- Source: [Make a Mark Airtable base](https://airtable.com/appmlzjWqich2Whq4/tbl3BFudyJnttv469/viwrjIvfRm9NAbts9?blocks=hide), inspected read-only on 2026-10-18.
- Capture method: visible table names, field names, view names, and record counts from the Airtable interface. No records were edited or exported. Personal record values are intentionally omitted.
- Scope: the base's visible tables and their relationship fields; not a full schema/API export.

## Tables and relationships

The base contains five tables: **Organisations**, **Makers**, **Teams**, **Screenshots**, and **Editions**.

**Editions** is a separate table with three visible records: 2023 (Done), 2024 (Done), and 2025 (In progress). It has Name, Status, Notes, and a linked Organisations field. The 2025 record is linked to organisations that are also present in the Organisations table. This makes an edition a first-class record in the data model, rather than only a date in copy.

**Organisations** has 37 records in the inspected view. Its fields cover organisation identity and contact details, mission, challenge and project details/goals, language-specific summaries, status, selection feedback, logistics/availability checks, a linked Team, and a linked Edition. The visible status groups include Applied, Contacted, Meer info nodig, and Confirmed, as well as records with no status.

**Makers** has fields covering application date, skills, portfolio/work, contact details, languages, preferences, dietary needs, planning-meeting availability, date confirmation, status, themes, work experience, selection feedback, and a linked Team. The table's saved views include “Makers '25”, “Accepted with team”, “Rejected”, “Teamlozen”, “Gallery”, and “Stay in the loop as a maker”. The inspected view showed six rows; that is a view count, not a total base count.

**Teams** has a saved 2025 view with 11 teams. Fields include a maker count, linked Organisation and Makers records, notes, status, and lookups for maker skills, organisation challenge, and organisation skills needed, plus a Year field. This appears to model the edition-specific matching of makers to an organisation through a team; this is an interpretation of the links and fields.

**Screenshots** has 51 records and fields for URL, screenshot attachment, and created time. It appears to hold portfolio links and screenshot assets, based on the visible fields and values.

## Limits

The interface inspection establishes visible names, fields, views, statuses, and relationships only. It does not establish requiredness, validation, automations, form-to-table behavior, consent/legal basis, retention, or whether all records/fields are represented by the views inspected. The visible edition list ends at 2025; this does not establish whether a 2027 record exists elsewhere or is planned. Personal data values were not captured.
