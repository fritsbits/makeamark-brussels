<x-layouts.app :site="$site" :page-title="$pageTitle" :page-description="$pageDescription" :public-typography="true">
    @include('sections.nav')
    <main id="main" tabindex="-1">
        <article class="privacy container">
            <a class="back-link" href="{{ route('home') }}" aria-label="Back to the edition"><flux:icon.arrow-left variant="micro" class="back-link__icon" aria-hidden="true" /><span>Edition</span></a>
            <p class="kicker">Application information</p>
            <flux:heading level="1" class="h-display privacy__title">Privacy notice</flux:heading>
            <div class="privacy__body">
                <p class="privacy__updated">Last updated 20 October 2026</p>

                <section aria-labelledby="privacy-controller">
                    <h2 id="privacy-controller">Who is responsible</h2>
                    <p>Impact Studio BV (BE 0652.723.886), Kasteeldreef 47, 1083 Ganshoren, is responsible for personal information submitted through the Make a Mark Brussels maker and organisation applications and processed for public website analytics.</p>
                    <p>For privacy questions or requests, contact Frederik Vincx at <a href="mailto:hi@frederikvincx.com">hi@frederikvincx.com</a>.</p>
                </section>

                <section aria-labelledby="privacy-purpose">
                    <h2 id="privacy-purpose">Why we use application information</h2>
                    <p>We use contact details, application answers, and basic technical request logs to assess applications, select participants, secure the application site, organise the edition, and keep prior answers available so you can refer to them if you apply again within three years. If you apply again, the team will ask you to review and update your information. We will not submit an old application for you or use your answers for marketing.</p>
                    <p>Our legal basis is our legitimate interest in running Make a Mark and managing applications across editions. We have weighed this against your privacy: access is limited to the internal Make a Mark team listed on this site, applications are reviewed manually, and we review retained applications yearly and delete or anonymise them three years after your latest application.</p>
                    <p>You can object to processing based on legitimate interests. You do not have to apply, but required answers are necessary for the team to assess your application.</p>
                </section>

                <section aria-labelledby="privacy-analytics">
                    <h2 id="privacy-analytics">Website analytics</h2>
                    <p>We use Fathom Analytics to understand how visitors use the public website and improve it. Fathom does not use tracking cookies or build advertising profiles across websites. To produce site statistics, it briefly processes request information including your IP address and browser user-agent to create a site-specific visitor signature. Fathom says this personal data is processed and anonymised on EU servers before it reaches its US-controlled infrastructure.</p>
                    <p>Our purpose is to understand and improve the public website. You can object to processing based on our legitimate interests by contacting us using the details below. Read more in <a href="https://usefathom.com/docs/start/policy">Fathom's privacy information</a> and its <a href="https://usefathom.com/legal/dpa">data processing agreement</a>.</p>
                </section>

                <section aria-labelledby="privacy-data">
                    <h2 id="privacy-data">What we collect</h2>
                    <p>We collect contact information and the answers you provide in the maker or organisation application. Our site and hosting providers may also log basic technical details, such as IP address and request time, to maintain and secure the service. We do not ask about dietary requirements. Please do not include health details, identity documents, or other sensitive information in free-text answers.</p>
                    <p>We do not use automated decisions to select applicants.</p>
                </section>

                <section aria-labelledby="privacy-sharing">
                    <h2 id="privacy-sharing">Who handles the information</h2>
                    <p>The internal Make a Mark team listed on this site reviews applications. We do not share applications with other organisations for their own use. A few providers support the site and email:</p>
                    <ul>
                        <li><strong>DigitalOcean</strong> hosts the application on a server in Amsterdam. <strong>Laravel Forge</strong> is used to manage that server.</li>
                        <li><strong>Resend</strong> sends confirmation emails when email is enabled. It receives your name, email address, application type, and edition year, but not your application answers. Resend processes email data in the United States under contractual transfer safeguards.</li>
                        <li><strong>Fathom Analytics</strong> provides statistics about use of the public website. It briefly processes IP address and browser user-agent data for site-specific analytics; see the <a href="https://usefathom.com/legal/dpa">Fathom data processing agreement</a>.</li>
                        <li><strong>Cloudflare and one.com</strong> support our domain and email. If you email us, they may handle your message and delivery details.</li>
                    </ul>
                </section>

                <section aria-labelledby="privacy-retention">
                    <h2 id="privacy-retention">How long we keep it</h2>
                    <p>We review application records once a year. We delete or anonymise an application no later than three years after your most recent application, unless you apply again or we need limited information for a legal claim. You can ask us to delete your information sooner.</p>
                    <p>Future account features are not available now. If we introduce accounts or another use of prior application information, we will explain that use before it begins.</p>
                </section>

                <section aria-labelledby="privacy-rights">
                    <h2 id="privacy-rights">Your rights</h2>
                    <p>You may ask us to access or correct your information, restrict its use, or delete it. You may object to processing based on legitimate interests. Contact <a href="mailto:hi@frederikvincx.com">hi@frederikvincx.com</a> to make a request.</p>
                    <p>You can also complain to the <a href="https://www.autoriteprotectiondonnees.be/citoyen/agir/introduire-une-plainte">Belgian Data Protection Authority</a>.</p>
                </section>
            </div>
        </article>
    </main>
    @include('sections.footer')
</x-layouts.app>
