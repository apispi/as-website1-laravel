<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            [
                'slug'        => 'bid-tender',
                'name'        => 'Bid & Tender Response',
                'description' => 'Automates government RFQ/RFT responses, selection criteria, and capability statements for procurement teams.',
                'badge'       => 'Popular',
                'rating'      => 4.90,
                'users_count' => '340+',
                'price'       => '$499/mo',
                'category'    => 'Government & Procurement',
                'is_featured' => false,
                'sort_order'  => 1,
            ],
            [
                'slug'        => 'security-compliance',
                'name'        => 'Security & IRAP Readiness',
                'description' => 'Guides organisations through Essential Eight, ISM, PSPF, IRAP, ISO 27001, and cloud security readiness.',
                'badge'       => 'Premium',
                'rating'      => 4.95,
                'users_count' => '180+',
                'price'       => '$799/mo',
                'category'    => 'Security & Compliance',
                'is_featured' => true,
                'sort_order'  => 2,
            ],
            [
                'slug'        => 'enterprise-architecture',
                'name'        => 'Enterprise Architecture',
                'description' => 'Acts as a virtual enterprise/solution architect — generating options, decision records, and migration roadmaps.',
                'badge'       => 'Popular',
                'rating'      => 4.85,
                'users_count' => '210+',
                'price'       => '$599/mo',
                'category'    => 'Architecture & Strategy',
                'is_featured' => false,
                'sort_order'  => 3,
            ],
            [
                'slug'        => 'digital-avatar',
                'name'        => 'Digital Avatar & Executive Clone',
                'description' => 'Creates AI-powered professional avatars for executives, consultants, trainers, and public-facing staff.',
                'badge'       => 'New',
                'rating'      => 4.90,
                'users_count' => '520+',
                'price'       => '$800',
                'category'    => 'Communications & Avatar',
                'is_featured' => false,
                'sort_order'  => 4,
            ],
            [
                'slug'        => 'knowledge-management',
                'name'        => 'Knowledge Management & SOP',
                'description' => 'Turns scattered organisational knowledge into searchable operational intelligence and auto-generated SOPs.',
                'badge'       => 'Popular',
                'rating'      => 4.80,
                'users_count' => '290+',
                'price'       => '$399/mo',
                'category'    => 'Knowledge Management',
                'is_featured' => false,
                'sort_order'  => 5,
            ],
            [
                'slug'        => 'cyber-incident',
                'name'        => 'Cyber Incident & Threat Intel',
                'description' => 'Acts as a first-line cyber operations assistant for log summarisation, triage, IOC extraction, and playbooks.',
                'badge'       => 'Premium',
                'rating'      => 4.90,
                'users_count' => '150+',
                'price'       => '$699/mo',
                'category'    => 'Cyber Operations',
                'is_featured' => true,
                'sort_order'  => 6,
            ],
            [
                'slug'        => 'content-creator',
                'name'        => 'Content Creator',
                'description' => 'Autonomous content generation for blogs, social media, and marketing campaigns.',
                'badge'       => 'Popular',
                'rating'      => 4.90,
                'users_count' => '1,250+',
                'price'       => '$29/mo',
                'category'    => 'Content & Marketing',
                'is_featured' => false,
                'sort_order'  => 7,
            ],
            [
                'slug'        => 'support-bot',
                'name'        => 'Customer Support Bot',
                'description' => '24/7 intelligent customer support with natural language understanding. Resolve issues faster.',
                'badge'       => 'New',
                'rating'      => 4.80,
                'users_count' => '890+',
                'price'       => '$49/mo',
                'category'    => 'Customer Support',
                'is_featured' => false,
                'sort_order'  => 8,
            ],
            [
                'slug'        => 'data-analyzer',
                'name'        => 'Data Analyzer Pro',
                'description' => 'Advanced data analysis, visualization, and insights generation from any dataset.',
                'badge'       => 'Premium',
                'rating'      => 4.95,
                'users_count' => '2,100+',
                'price'       => '$79/mo',
                'category'    => 'Data & Analytics',
                'is_featured' => true,
                'sort_order'  => 9,
            ],
        ];

        foreach ($agents as $data) {
            Agent::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
