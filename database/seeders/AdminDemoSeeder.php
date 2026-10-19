<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Team;
use App\Models\User;
use App\Services\Matchmaking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class AdminDemoSeeder extends Seeder
{
    private const DOMAIN = 'admin-demo.example.invalid';

    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('Admin demo data cannot be seeded outside local/testing environments.');
        }

        DB::transaction(function (): void {
            $reviewers = collect(['Gwen', 'Piet', 'Bernard'])->map(fn (string $name): User => User::firstOrCreate(
                ['email' => strtolower($name).'-reviewer@'.self::DOMAIN],
                ['name' => $name.' (demo reviewer)', 'password' => Str::password(40), 'is_admin' => false, 'is_super_admin' => false, 'is_active' => false],
            ));

            $organisations = [];
            foreach ($this->organisations() as $index => $data) {
                $email = 'organisation-'.($index + 1).'@'.self::DOMAIN;
                $organisations[] = $organisation = Application::firstOrCreate(
                    ['audience' => 'organisations', 'edition_year' => 2027, 'email' => $email],
                    [
                        'contact_name' => $data['contact'].' (demo)', 'status' => $data['status'],
                        'answers' => [
                            'organisation_name' => 'Demo · '.$data['name'], 'contact_name' => $data['contact'].' (demo)',
                            'email' => $email, 'mission' => $data['mission'], 'challenge' => $data['challenge'],
                            'website' => 'https://example.org/', 'social_media' => 'No real account (demo)',
                            'phone' => 'No real phone number (demo)', 'languages' => $data['languages'],
                            'brussels_eligibility' => '1', 'assets_ready' => $data['status'] === 'needs_information' ? '0' : '1',
                            'planning_meeting' => '1', 'feedback_session' => '1', 'evening_presentation' => '1',
                            'demo_record' => 'Fictional application for local testing; not imported from Airtable.',
                        ],
                        'refined_brief' => $data['brief'], 'skills_needed' => $data['skills'],
                        'internal_notes' => $data['notes'], 'created_at' => now()->subDays(14 - $index),
                    ],
                );
                if (isset($data['ratings'])) {
                    foreach ($reviewers as $reviewerIndex => $reviewer) {
                        $organisation->reviews()->firstOrCreate(['user_id' => $reviewer->id], [
                            'rating' => $data['ratings'][$reviewerIndex],
                            'comment' => $data['comments'][$reviewerIndex].' (Demo assessment.)',
                        ]);
                    }
                }
            }

            $makers = [];
            foreach ($this->makers() as $index => [$name, $status, $main, $backup, $languages, $motivation]) {
                $email = 'maker-'.($index + 1).'@'.self::DOMAIN;
                $makers[] = Application::firstOrCreate(
                    ['audience' => 'makers', 'edition_year' => 2027, 'email' => $email],
                    [
                        'contact_name' => $name.' (demo)', 'status' => $status,
                        'main_skills' => $main, 'backup_skills' => $backup,
                        'answers' => [
                            'name' => $name.' (demo)', 'email' => $email, 'motivation' => $motivation,
                            'portfolio' => 'https://example.org/', 'social_media' => 'No real account (demo)',
                            'main_skills' => $main, 'backup_skills' => $backup, 'other_skills' => '',
                            'english' => (string) $languages[0], 'dutch' => (string) $languages[1], 'french' => (string) $languages[2],
                            'other_languages' => $index % 3 === 0 ? 'Arabic (demo)' : '',
                            'meeting_availability' => $index % 2 === 0 ? '18:30–19:30' : '19:30–20:30',
                            'team_preferences' => $index % 2 === 0 ? 'Happy to work in a mixed-discipline team.' : 'Interested in a youth-led project with a clear scope.',
                            'themes' => ['Education and youth development', 'Culture and community building'],
                            'referral' => 'Fictional demo application', 'date_confirmed' => '1', 'full_commitment' => '1',
                            'demo_record' => 'Fictional application for local testing; not imported from Airtable.',
                        ],
                        'internal_notes' => match ($status) {
                            'selected', 'confirmed' => 'Demo: ready for matching; check language, skills and preferences.',
                            'waitlisted' => 'Demo: keep available if another maker withdraws.',
                            'needs_information' => 'Demo: ask for clarification of portfolio and availability.',
                            'rejected' => 'Demo: not selected for this edition.',
                            'withdrawn' => 'Demo: applicant can no longer attend.',
                            default => null,
                        },
                        'created_at' => now()->subDays(21 - $index),
                    ],
                );
            }

            $this->starterTeam($organisations[0], 'Demo · Atelier youth campaign', [$makers[0], $makers[1]], 'draft',
                'Demo draft: art direction and graphic design are covered. Add a copywriter, such as Lina, to fill the remaining skill need.');
            $this->starterTeam($organisations[1], 'Demo · Quartier Vert campaign', [$makers[3], $makers[4], $makers[5]], 'confirmed',
                'Demo confirmed team: campaign strategy, graphic design and photography are covered. Try adjusting membership or adding notes.');
        });
    }

    private function starterTeam(Application $organisation, string $name, array $makers, string $status, string $notes): void
    {
        if ($organisation->organisationTeam()->exists() || ! in_array($organisation->status, ['selected', 'confirmed'], true)) {
            return;
        }

        $availableIds = Application::whereKey(array_map(fn (Application $maker): int => $maker->id, $makers))
            ->whereIn('status', ['selected', 'confirmed'])->doesntHave('teams')->pluck('id')->all();
        app(Matchmaking::class)->save(new Team, [
            'name' => $name, 'edition_year' => 2027, 'organisation_application_id' => $organisation->id,
            'status' => $status, 'notes' => $notes,
        ], $availableIds);
    }

    private function organisations(): array
    {
        return [
            [
                'name' => 'Atelier des Jeunes', 'contact' => 'Sarah Laurent', 'status' => 'selected', 'languages' => ['French', 'English'],
                'mission' => 'A fictional Brussels youth workshop where young people explore making, art and practical skills.',
                'challenge' => 'Help more young people discover our workshops. Our current communication feels written for parents.',
                'brief' => 'Create a small campaign for young people aged 16–22: one visual direction, an A3 poster and three adaptable social posts. The team will provide workshop photos and a short description. Focus on an invitation young people can share with friends.',
                'skills' => ['art-direction', 'graphic-design', 'content-development-copywriting'],
                'notes' => 'Demo: briefing meeting completed. Keep the deliverables small; the organisation can update the resulting templates.',
                'ratings' => [5, 4, 4], 'comments' => ['Clear audience and a manageable brief.', 'Strong fit; check the copywriting capacity.', 'A useful small campaign with material ready.'],
            ],
            [
                'name' => 'Quartier Vert', 'contact' => 'Lucas De Smet', 'status' => 'confirmed', 'languages' => ['Dutch', 'French', 'English'],
                'mission' => 'A fictional neighbourhood group helping young residents take part in local environmental projects.',
                'challenge' => 'We need a recognisable invitation for a neighbourhood clean-up and repair day.',
                'brief' => 'Develop a bilingual invitation for a repair and clean-up day: a simple campaign idea, a poster template and a set of reusable photographs. The audience is local residents aged 15–25. Use a welcoming tone and avoid guilt-based messaging.',
                'skills' => ['campaign-strategy', 'graphic-design', 'photography'],
                'notes' => 'Demo: organisation confirmed, contact and feedback sessions arranged. A complete starter team is assigned.',
                'ratings' => [4, 5, 4], 'comments' => ['Good local relevance and a clear invitation.', 'The bilingual format is feasible.', 'Photography will make the invitation more specific.'],
            ],
            [
                'name' => 'Passerelle Numérique', 'contact' => 'Meryem Ait', 'status' => 'selected', 'languages' => ['French', 'English'],
                'mission' => 'A fictional mentoring initiative helping young people access digital learning and guidance.',
                'challenge' => 'Young people struggle to understand our offer and how to register for mentoring.',
                'brief' => 'Design a clear mentoring landing page and a small clickable prototype of the registration journey. Prioritise information structure, plain language and accessibility. A production website is outside the one-day scope; record technical recommendations for the organisation.',
                'skills' => ['service-design', 'ux-design', 'web-design', 'front-end-development'],
                'notes' => 'Demo: ready for a new team. Samir, Mila and Alex are available examples to explore in the picker.',
                'ratings' => [4, 4, 5], 'comments' => ['Useful problem, provided we keep to a prototype.', 'The registration journey needs attention.', 'Good match for service and UX skills.'],
            ],
            [
                'name' => 'Voix du Canal', 'contact' => 'Aya Benomar', 'status' => 'applied', 'languages' => ['Dutch', 'French'],
                'mission' => 'A fictional collective offering young people space to share neighbourhood stories through sound and illustration.',
                'challenge' => 'Our programme is difficult to explain. We would like a clearer story and examples we can share online.',
                'brief' => null, 'skills' => [], 'notes' => null,
            ],
            [
                'name' => 'Vélo Solidaire', 'contact' => 'Niels Jacobs', 'status' => 'needs_information', 'languages' => ['Dutch', 'English'],
                'mission' => 'A fictional volunteer bicycle workshop helping young people learn repair skills.',
                'challenge' => 'We want to recruit participants and volunteers, but have not decided which audience needs priority.',
                'brief' => null, 'skills' => ['brand-strategy', 'content-development-copywriting'],
                'notes' => 'Demo: clarify the priority audience and request usable photos before refining the briefing.',
            ],
            [
                'name' => 'Mémoire en Couleurs', 'contact' => 'Camille Simon', 'status' => 'rejected', 'languages' => ['French'],
                'mission' => 'A fictional association preserving local stories through exhibitions and community activities.',
                'challenge' => 'We would like a complete rebrand, new website, exhibition design and a year-long communication plan.',
                'brief' => null, 'skills' => ['brand-identity', 'web-design', 'print-production'],
                'notes' => 'Demo decision: defer until the scope is smaller. Use this record to test rejected-status filters.',
                'ratings' => [2, 1, 2], 'comments' => ['Scope is too broad for one day.', 'Needs a smaller first project.', 'Potential for a later, focused brief.'],
            ],
        ];
    }

    private function makers(): array
    {
        return [
            ['Noor Benali', 'selected', ['brand-identity', 'art-direction'], ['graphic-design'], [5, 4, 3], 'I enjoy helping youth organisations find a visual direction that they can keep using.'],
            ['Jules Martin', 'selected', ['graphic-design', 'illustrations'], ['print-production'], [4, 2, 5], 'I would like to make practical posters and illustrations with a team.'],
            ['Lina Vermeer', 'selected', ['content-development-copywriting', 'brand-strategy'], ['social-media-design'], [5, 5, 3], 'I like turning a broad idea into a short, clear invitation for young people.'],
            ['Amadou Diallo', 'confirmed', ['campaign-strategy', 'photography'], ['digital-strategy'], [5, 2, 5], 'I can help a team choose a campaign idea and make a focused set of images.'],
            ['Eva Peeters', 'confirmed', ['graphic-design', 'social-media-design'], ['video-production'], [4, 5, 3], 'I enjoy designing reusable templates that small organisations can maintain.'],
            ['Chloé Laurent', 'selected', ['photography', 'video-production'], ['illustrations'], [4, 1, 5], 'I would love to document local initiatives and work alongside designers.'],
            ['Samir El Haddad', 'selected', ['service-design', 'ux-design'], ['brand-strategy'], [5, 3, 5], 'I can help map a registration journey and prioritise the most useful improvements.'],
            ['Mila Janssen', 'selected', ['web-design', 'front-end-development'], ['ux-design'], [5, 5, 2], 'I like accessible web design and can help make a small interactive prototype.'],
            ['Alex Moreau', 'selected', ['back-end-development', 'web-design'], ['front-end-development'], [5, 2, 5], 'I can explain technical trade-offs and help keep a digital brief realistic.'],
            ['Robin De Smet', 'selected', ['pr', 'digital-strategy'], ['content-development-copywriting'], [5, 5, 4], 'I can help teams clarify their audience and choose useful communication channels.'],
            ['Yara Haddad', 'applied', ['animation'], ['illustrations'], [5, 2, 4], 'I would like to make a short animated invitation with a multidisciplinary team.'],
            ['Thomas Dubois', 'applied', ['print-production', 'graphic-design'], ['photography'], [4, 3, 5], 'I enjoy preparing simple printed materials that can be produced on a small budget.'],
            ['Inès De Winter', 'applied', ['illustrations'], ['content-development-copywriting'], [4, 4, 5], 'I want to use illustration to make a programme easier to understand.'],
            ['Oscar Nguyen', 'applied', ['content-development-copywriting', 'social-media-design'], ['animation'], [5, 4, 3], 'I enjoy writing short messages and developing practical social content.'],
            ['Louise Bakker', 'waitlisted', ['brand-strategy'], ['art-direction'], [5, 5, 2], 'I can help organisations choose a focused message and brief a creative team.'],
            ['Hugo Petit', 'rejected', ['brand-identity'], ['graphic-design'], [3, 1, 5], 'I would like to gain experience working on an identity project with others.'],
            ['Fatima El Amrani', 'needs_information', ['digital-strategy', 'pr'], ['photography'], [5, 3, 5], 'I am interested in communication strategy; I can share more examples of my work.'],
            ['Max Willems', 'withdrawn', ['service-design', 'ux-design'], ['web-design'], [5, 5, 3], 'I enjoy working on information and service journeys with community organisations.'],
        ];
    }
}
