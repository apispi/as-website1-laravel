<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class AgentSkillSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'bid-tender' => [
                'rfq-rft-parsing',
                'cv-parsing',
                'document-analysis',
                'star-response-generation',
                'executive-summary',
                'capability-statements',
                'compliance-matrix',
                'gap-analysis',
            ],
            'security-compliance' => [
                'compliance-matrix',
                'gap-analysis',
                'regulatory-mapping',
                'audit-trail-generation',
                'policy-drafting',
                'vulnerability-assessment',
                'penetration-test-reporting',
                'report-writing',
            ],
            'enterprise-architecture' => [
                'architecture-review',
                'technology-roadmapping',
                'decision-record-drafting',
                'pattern-matching',
                'document-analysis',
                'report-writing',
            ],
            'digital-avatar' => [
                'avatar-generation',
                'voice-cloning',
                'video-script-generation',
                'multilingual-narration',
                'content-generation',
            ],
            'knowledge-management' => [
                'knowledge-capture',
                'taxonomy-building',
                'semantic-search',
                'faq-generation',
                'knowledge-gap-detection',
                'document-analysis',
                'ocr-extraction',
                'report-writing',
            ],
            'cyber-incident' => [
                'threat-detection',
                'incident-triage',
                'vulnerability-assessment',
                'forensic-analysis',
                'penetration-test-reporting',
                'audit-trail-generation',
                'report-writing',
            ],
            'content-creator' => [
                'content-generation',
                'report-writing',
                'translation-localisation',
                'video-script-generation',
                'data-analysis',
            ],
            'support-bot' => [
                'intent-classification',
                'sentiment-analysis',
                'automated-response',
                'ticket-summarisation',
                'escalation-detection',
                'faq-generation',
                'semantic-search',
            ],
            'data-analyzer' => [
                'data-extraction',
                'data-analysis',
                'data-visualisation',
                'predictive-modelling',
                'sql-query-generation',
                'document-analysis',
                'ocr-extraction',
                'report-writing',
            ],
        ];

        $skillIds = Skill::pluck('id', 'slug');

        foreach ($map as $agentSlug => $skillSlugs) {
            $agent = Agent::where('slug', $agentSlug)->first();
            if (! $agent) continue;

            $ids = collect($skillSlugs)
                ->map(fn($s) => $skillIds[$s] ?? null)
                ->filter()
                ->values()
                ->all();

            $agent->skills()->sync($ids);
        }
    }
}
