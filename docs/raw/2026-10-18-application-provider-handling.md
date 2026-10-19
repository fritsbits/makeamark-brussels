# Application provider handling — official-source review

- Review date: 2026-10-18.
- Purpose: identify provider roles, locations, retention, and transfer terms for the application privacy notice.
- Limits: provider contract and subprocessor notes below come from public documentation. The owner later supplied DigitalOcean/Forge screenshots confirming the droplet is in Amsterdam 3 (AMS3) and managed with Forge. The screenshots show an option to set up automated backups, not an enabled backup configuration. They do not identify the destination mailbox or confirm Resend setup.

## DigitalOcean

- Official DPA: https://www.digitalocean.com/legal/data-processing-agreement
- DPA FAQ: https://www.digitalocean.com/legal/gdpr-faq
- Region list: https://docs.digitalocean.com/platform/regional-availability/
- Subprocessors: https://www.digitalocean.com/trust/subprocessors
- The DigitalOcean droplet overview supplied by the owner shows AMS3; the Forge app-server dashboard shows the shared-projects server and Forge-managed sites/databases. The public region list identifies Amsterdam as AMS3.
- DigitalOcean's DPA governs processor processing. Its current DPA describes EU-US Data Privacy Framework transfers, with EU Standard Contractual Clauses as fallback. The public subprocessor list includes AWS in the US for backups/infrastructure services and Cloudflare in the US for platform security services. This does not prove that this app has a DigitalOcean backup configured.
- DigitalOcean says the customer is responsible for content backups and regional configuration. The supplied screenshot offers “Setup Automated Backups,” so do not state that managed backups are enabled.

## Laravel Forge

- Forge terms: https://laravel.com/legal/forge-terms
- Forge Trust Center: https://trust.laravel.com/?product=forge&itemUid=c4223a81-5840-4e11-ac9f-2b812794a67e
- Terms say Laravel Holdings Inc. operates Forge as SaaS; Your Data remains the customer's, while Laravel and its representatives may access/process data as needed to provide the service. Terms refer to Laravel's Data Processing Agreement. Forge connects to and manages the customer's server; permissions and support configuration determine practical access.
- The DPA is listed in Laravel's Trust Center, which requires an account/access request for documentation. Public material did not confirm the exact Forge data locations, current subprocessor list, or accepted DPA for this account. Confirm these in the Forge account/trust-center documents before launch.

## one.com

- GDPR support and data location: https://help.one.com/hc/en-us/articles/360000253649-How-does-one-com-comply-with-the-GDPR
- DPA: https://www.one.com/en-gb/legal/dpa/
- Privacy policy: https://www.one.com/en-us/legal/privacy/
- one.com states EU customer data is hosted in Denmark. Its DPA lists Denmark data-centre processing and, depending on service, a one.com India affiliate for system monitoring; it uses Standard Contractual Clauses for relevant out-of-EEA subprocessor transfers. Account/product type remains unknown. one.com may be only the domain/email provider; it is not the application's stated database host.
- Confirm whether one.com hosts the `hello@` mailbox or only the domain. If email hosting is active, its mailbox may hold incoming applicant correspondence and its configured retention/backups apply.

## Cloudflare Email Routing

- Product page: https://www.cloudflare.com/en-in/developer-platform/products/email-routing/
- Email Service docs: https://developers.cloudflare.com/email-service/
- Customer DPA: https://www.cloudflare.com/cloudflare-customer-dpa/
- Cloudflare says Email Routing handles inbound mail at the SMTP-envelope/transport layer, does not store or access email body content, uses phishing detection, and provides delivery/count analytics. It forwards mail to a separate mailbox provider; it does not itself provide the destination mailbox. Routing/metadata and analytics still identify communications. Email Sending is a separate outbound product and requires a separate setup.
- Cloudflare's DPA includes SCC terms for applicable international transfers. The actual destination mailbox and routing configuration are not yet known.

## Resend

- GDPR overview: https://resend.com/security/gdpr
- DPA: https://resend.com/legal/dpa
- Subprocessors: https://resend.com/legal/subprocessors
- Resend states that customer data (including message content, delivery logs, webhook payloads, and account records) is stored in the United States. Choosing an EU sending region changes where email is routed/sent from, not where stored data is held. The DPA uses EU SCCs and Resend also states it participates in the EU-US DPF.
- On Free, Pro, and Scale plans, email and log data is retained for 30 days while the account is active and backups persist 7 days. The app's current receipt message includes applicant name, email address as recipient, audience type, and edition year; it does not include the application answers. Resend is not configured in this workspace yet.

## Google Gmail / Workspace

- Workspace DPA: https://workspace.google.com/terms/dpa_terms.html
- Workspace subprocessors: https://workspace.google.com/terms/subprocessors/
- Google Workspace's DPA says Google may store/process Customer Data in locations where Google or its subprocessors operate, subject to applicable transfer safeguards; DPA provides transfer mechanisms including SCCs/alternatives. Consumer Gmail and Google Workspace do not have identical contractual terms. The owner's account type is unknown and Gmail alerts are not implemented.
- Use a Google Workspace account covered by its business DPA for internal alerts. Keep alerts to a notification and authenticated admin link, with no application answers. Confirm the Workspace account, mailbox access, and retention before enabling alerts.

## Result for the notice

For the current notice, keep provider details proportional: identify DigitalOcean/Forge for hosting and server management, Resend for confirmation email (US processing with contractual safeguards), and Cloudflare/one.com for domain and email contact. Do not enumerate unrelated subprocessors or future Google Workspace alerts. The screenshots confirm AMS3 and Forge. Practical setup still needed before mail is live: configure Resend and a verified sender, and confirm the destination mailbox for `hello@`.
