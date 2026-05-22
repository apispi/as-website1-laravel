<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [

            // Document Processing
            ['slug' => 'document-analysis',       'name' => 'Document Analysis',          'category' => 'Document Processing', 'description' => 'Reads and extracts structured information from unstructured documents including PDFs, Word files, and scanned pages.', 'sort_order' => 10],
            ['slug' => 'rfq-rft-parsing',          'name' => 'RFQ / RFT Parsing',          'category' => 'Document Processing', 'description' => 'Parses government Request for Quotation and Request for Tender documents, extracting mandatory criteria, evaluation weightings, and submission requirements.', 'sort_order' => 11],
            ['slug' => 'cv-parsing',               'name' => 'CV Parsing',                  'category' => 'Document Processing', 'description' => 'Extracts structured candidate data from CVs and resumes including skills, experience, qualifications, and certifications.', 'sort_order' => 12],
            ['slug' => 'contract-review',          'name' => 'Contract Review',             'category' => 'Document Processing', 'description' => 'Identifies key clauses, obligations, risk terms, and anomalies in contracts and legal agreements.', 'sort_order' => 13],
            ['slug' => 'ocr-extraction',           'name' => 'OCR & Text Extraction',       'category' => 'Document Processing', 'description' => 'Converts scanned images and non-machine-readable documents into searchable, structured text using optical character recognition.', 'sort_order' => 14],

            // Compliance & Governance
            ['slug' => 'compliance-matrix',        'name' => 'Compliance Matrix Generation', 'category' => 'Compliance & Governance', 'description' => 'Produces structured compliance matrices mapping each mandatory requirement to the relevant section of a response or document.', 'sort_order' => 20],
            ['slug' => 'gap-analysis',             'name' => 'Gap Analysis',                 'category' => 'Compliance & Governance', 'description' => 'Identifies missing coverage between a set of requirements and an existing response, capability statement, or control set.', 'sort_order' => 21],
            ['slug' => 'regulatory-mapping',       'name' => 'Regulatory Mapping',           'category' => 'Compliance & Governance', 'description' => 'Maps organisational controls and practices to regulatory frameworks including Essential Eight, ISO 27001, PSPF, and ISM.', 'sort_order' => 22],
            ['slug' => 'audit-trail-generation',   'name' => 'Audit Trail Generation',       'category' => 'Compliance & Governance', 'description' => 'Generates structured audit logs and evidence packages for compliance reviews and certification audits.', 'sort_order' => 23],
            ['slug' => 'policy-drafting',          'name' => 'Policy Drafting',              'category' => 'Compliance & Governance', 'description' => 'Drafts security policies, procedures, and standards aligned to nominated frameworks and organisational context.', 'sort_order' => 24],

            // Writing & Communication
            ['slug' => 'star-response-generation', 'name' => 'STAR Response Generation',    'category' => 'Writing & Communication', 'description' => 'Generates Situation, Task, Action, Result method responses for selection criteria using provided project references and CV data.', 'sort_order' => 30],
            ['slug' => 'executive-summary',        'name' => 'Executive Summary Writing',   'category' => 'Writing & Communication', 'description' => 'Produces polished executive summaries and cover letters tailored to specific audiences and evaluation criteria.', 'sort_order' => 31],
            ['slug' => 'capability-statements',    'name' => 'Capability Statement Writing', 'category' => 'Writing & Communication', 'description' => 'Creates targeted capability statements mapping organisational strengths to specific procurement or client requirements.', 'sort_order' => 32],
            ['slug' => 'content-generation',       'name' => 'Content Generation',           'category' => 'Writing & Communication', 'description' => 'Produces blog posts, articles, social media copy, and marketing content calibrated to tone, audience, and SEO requirements.', 'sort_order' => 33],
            ['slug' => 'report-writing',           'name' => 'Report Writing',               'category' => 'Writing & Communication', 'description' => 'Synthesises findings from multiple sources into structured, professionally formatted reports suitable for executive and technical audiences.', 'sort_order' => 34],
            ['slug' => 'translation-localisation', 'name' => 'Translation & Localisation',  'category' => 'Writing & Communication', 'description' => 'Translates content across languages and adapts tone, terminology, and cultural references for target regional audiences.', 'sort_order' => 35],

            // Data & Analytics
            ['slug' => 'data-extraction',          'name' => 'Data Extraction',             'category' => 'Data & Analytics', 'description' => 'Extracts structured datasets from unstructured sources including documents, web pages, emails, and APIs.', 'sort_order' => 40],
            ['slug' => 'data-analysis',            'name' => 'Data Analysis',               'category' => 'Data & Analytics', 'description' => 'Performs statistical analysis, trend identification, and pattern recognition on structured and semi-structured datasets.', 'sort_order' => 41],
            ['slug' => 'data-visualisation',       'name' => 'Data Visualisation',          'category' => 'Data & Analytics', 'description' => 'Generates charts, dashboards, and visual summaries that communicate data insights to non-technical stakeholders.', 'sort_order' => 42],
            ['slug' => 'predictive-modelling',     'name' => 'Predictive Modelling',        'category' => 'Data & Analytics', 'description' => 'Builds and applies predictive models to forecast outcomes, classify inputs, and surface anomalies in business data.', 'sort_order' => 43],
            ['slug' => 'sql-query-generation',     'name' => 'SQL Query Generation',        'category' => 'Data & Analytics', 'description' => 'Translates natural language questions into optimised SQL queries against relational database schemas.', 'sort_order' => 44],

            // Security & Cyber
            ['slug' => 'threat-detection',         'name' => 'Threat Detection',            'category' => 'Security & Cyber', 'description' => 'Analyses system logs, network traffic, and telemetry to identify indicators of compromise and active threat activity.', 'sort_order' => 50],
            ['slug' => 'incident-triage',          'name' => 'Incident Triage',             'category' => 'Security & Cyber', 'description' => 'Classifies and prioritises security incidents by severity, blast radius, and required response actions.', 'sort_order' => 51],
            ['slug' => 'vulnerability-assessment', 'name' => 'Vulnerability Assessment',    'category' => 'Security & Cyber', 'description' => 'Assesses system configurations, patch levels, and architecture against known vulnerability databases and hardening benchmarks.', 'sort_order' => 52],
            ['slug' => 'forensic-analysis',        'name' => 'Forensic Analysis',           'category' => 'Security & Cyber', 'description' => 'Preserves and analyses digital evidence from compromised systems, producing chain-of-custody documentation suitable for legal proceedings.', 'sort_order' => 53],
            ['slug' => 'penetration-test-reporting','name' => 'Penetration Test Reporting', 'category' => 'Security & Cyber', 'description' => 'Formats penetration testing findings into structured reports with CVSS scoring, remediation guidance, and executive summaries.', 'sort_order' => 54],

            // Knowledge Management
            ['slug' => 'knowledge-capture',        'name' => 'Knowledge Capture',           'category' => 'Knowledge Management', 'description' => 'Extracts tacit knowledge from conversations, documents, and subject matter experts into structured, reusable knowledge artefacts.', 'sort_order' => 60],
            ['slug' => 'taxonomy-building',        'name' => 'Taxonomy Building',           'category' => 'Knowledge Management', 'description' => 'Designs and populates controlled vocabularies, ontologies, and classification hierarchies for organisational knowledge systems.', 'sort_order' => 61],
            ['slug' => 'semantic-search',          'name' => 'Semantic Search',             'category' => 'Knowledge Management', 'description' => 'Retrieves relevant knowledge assets using meaning-based vector search rather than keyword matching.', 'sort_order' => 62],
            ['slug' => 'faq-generation',           'name' => 'FAQ Generation',              'category' => 'Knowledge Management', 'description' => 'Derives frequently asked questions and structured answers from existing documentation, support tickets, and conversation logs.', 'sort_order' => 63],
            ['slug' => 'knowledge-gap-detection',  'name' => 'Knowledge Gap Detection',     'category' => 'Knowledge Management', 'description' => 'Identifies topics and questions that lack adequate coverage in an existing knowledge base.', 'sort_order' => 64],

            // Customer & Support
            ['slug' => 'intent-classification',    'name' => 'Intent Classification',       'category' => 'Customer & Support', 'description' => 'Classifies incoming messages and requests by intent category to route them to the appropriate handler or response.', 'sort_order' => 70],
            ['slug' => 'sentiment-analysis',       'name' => 'Sentiment Analysis',          'category' => 'Customer & Support', 'description' => 'Detects emotional tone and satisfaction signals in customer communications to inform escalation and response prioritisation.', 'sort_order' => 71],
            ['slug' => 'automated-response',       'name' => 'Automated Response',          'category' => 'Customer & Support', 'description' => 'Generates contextually appropriate responses to common customer queries using knowledge base and conversation history.', 'sort_order' => 72],
            ['slug' => 'ticket-summarisation',     'name' => 'Ticket Summarisation',        'category' => 'Customer & Support', 'description' => 'Condenses long support ticket threads into concise summaries for agent handoff or management reporting.', 'sort_order' => 73],
            ['slug' => 'escalation-detection',     'name' => 'Escalation Detection',        'category' => 'Customer & Support', 'description' => 'Identifies conversations that require human intervention based on complexity, sentiment, and resolution confidence.', 'sort_order' => 74],

            // Architecture & Strategy
            ['slug' => 'architecture-review',      'name' => 'Architecture Review',         'category' => 'Architecture & Strategy', 'description' => 'Evaluates technology architectures against design principles, pattern libraries, and organisational standards.', 'sort_order' => 80],
            ['slug' => 'technology-roadmapping',   'name' => 'Technology Roadmapping',      'category' => 'Architecture & Strategy', 'description' => 'Produces structured technology roadmaps aligning capability investments to business strategy and lifecycle milestones.', 'sort_order' => 81],
            ['slug' => 'decision-record-drafting', 'name' => 'Decision Record Drafting',    'category' => 'Architecture & Strategy', 'description' => 'Drafts Architecture Decision Records (ADRs) capturing context, options considered, decision rationale, and consequences.', 'sort_order' => 82],
            ['slug' => 'pattern-matching',         'name' => 'Architecture Pattern Matching','category' => 'Architecture & Strategy','description' => 'Identifies applicable architecture patterns (microservices, event-driven, CQRS, etc.) for a given problem context and constraints.', 'sort_order' => 83],

            // Avatar & Media
            ['slug' => 'avatar-generation',        'name' => 'Avatar Generation',           'category' => 'Avatar & Media', 'description' => 'Creates photorealistic digital avatars from reference imagery for use in video, presentations, and virtual environments.', 'sort_order' => 90],
            ['slug' => 'voice-cloning',            'name' => 'Voice Cloning',               'category' => 'Avatar & Media', 'description' => 'Produces a licensed synthetic voice model from a short recording sample for use in narration, training, and avatar content.', 'sort_order' => 91],
            ['slug' => 'video-script-generation',  'name' => 'Video Script Generation',     'category' => 'Avatar & Media', 'description' => 'Writes and structures video scripts optimised for avatar delivery, including timing cues, emphasis markers, and slide sync points.', 'sort_order' => 92],
            ['slug' => 'multilingual-narration',   'name' => 'Multilingual Narration',      'category' => 'Avatar & Media', 'description' => 'Delivers avatar-narrated content in multiple languages using translated scripts and localised voice synthesis.', 'sort_order' => 93],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['slug' => $skill['slug']],
                array_merge(['is_active' => true], $skill)
            );
        }
    }
}
