<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AgentController extends Controller
{
    private array $agents = [
        [
            'slug'        => 'bid-tender',
            'name'        => 'Bid & Tender Response',
            'description' => 'Automates government RFQ/RFT responses, selection criteria, and capability statements for procurement teams.',
            'badge'       => 'Popular',
            'rating'      => '4.9',
            'users'       => '340+',
            'price'       => '$499/mo',
        ],
        [
            'slug'        => 'security-compliance',
            'name'        => 'Security & IRAP Readiness',
            'description' => 'Guides organisations through Essential Eight, ISM, PSPF, IRAP, ISO 27001, and cloud security readiness.',
            'badge'       => 'Premium',
            'rating'      => '4.95',
            'users'       => '180+',
            'price'       => '$799/mo',
        ],
        [
            'slug'        => 'enterprise-architecture',
            'name'        => 'Enterprise Architecture',
            'description' => 'Acts as a virtual enterprise/solution architect — generating options, decision records, and migration roadmaps.',
            'badge'       => 'Popular',
            'rating'      => '4.85',
            'users'       => '210+',
            'price'       => '$599/mo',
        ],
        [
            'slug'        => 'digital-avatar',
            'name'        => 'Digital Avatar & Executive Clone',
            'description' => 'Creates AI-powered professional avatars for executives, consultants, trainers, and public-facing staff.',
            'badge'       => 'New',
            'rating'      => '4.9',
            'users'       => '520+',
            'price'       => '$250/mo',
        ],
        [
            'slug'        => 'knowledge-management',
            'name'        => 'Knowledge Management & SOP',
            'description' => 'Turns scattered organisational knowledge into searchable operational intelligence and auto-generated SOPs.',
            'badge'       => 'Popular',
            'rating'      => '4.8',
            'users'       => '290+',
            'price'       => '$399/mo',
        ],
        [
            'slug'        => 'cyber-incident',
            'name'        => 'Cyber Incident & Threat Intel',
            'description' => 'Acts as a first-line cyber operations assistant for log summarisation, triage, IOC extraction, and playbooks.',
            'badge'       => 'Premium',
            'rating'      => '4.9',
            'users'       => '150+',
            'price'       => '$699/mo',
        ],
        [
            'slug'        => 'content-creator',
            'name'        => 'Content Creator',
            'description' => 'Autonomous content generation for blogs, social media, and marketing campaigns.',
            'badge'       => 'Popular',
            'rating'      => '4.9',
            'users'       => '1,250+',
            'price'       => '$29/mo',
        ],
        [
            'slug'        => 'support-bot',
            'name'        => 'Customer Support Bot',
            'description' => '24/7 intelligent customer support with natural language understanding. Resolve issues faster.',
            'badge'       => 'New',
            'rating'      => '4.8',
            'users'       => '890+',
            'price'       => '$49/mo',
        ],
        [
            'slug'        => 'data-analyzer',
            'name'        => 'Data Analyzer Pro',
            'description' => 'Advanced data analysis, visualization, and insights generation from any dataset.',
            'badge'       => 'Premium',
            'rating'      => '4.95',
            'users'       => '2,100+',
            'price'       => '$79/mo',
        ],
    ];

    public function index()
    {
        return view('admin.agents.index', ['agents' => $this->agents]);
    }
}
