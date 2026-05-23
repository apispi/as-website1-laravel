<?php

namespace Database\Seeders;

use App\Models\Connector;
use Illuminate\Database\Seeder;

class ConnectorSeeder extends Seeder
{
    public function run(): void
    {
        $connectors = [
            // Communication
            ['slug' => 'slack',           'name' => 'Slack',            'category' => 'Communication',  'icon' => '💬', 'description' => 'Send messages, post to channels, and manage notifications in Slack workspaces.',               'website_url' => 'https://slack.com',             'sort_order' => 10],
            ['slug' => 'microsoft-teams', 'name' => 'Microsoft Teams',  'category' => 'Communication',  'icon' => '💼', 'description' => 'Post messages and manage conversations in Microsoft Teams channels.',                          'website_url' => 'https://teams.microsoft.com',   'sort_order' => 11],
            ['slug' => 'gmail',           'name' => 'Gmail',            'category' => 'Communication',  'icon' => '📧', 'description' => 'Read, send, and manage emails via Gmail. Supports drafts, labels, and search.',                'website_url' => 'https://gmail.com',             'sort_order' => 12],
            [
                'slug'               => 'outlook',
                'name'               => 'Outlook',
                'category'           => 'Communication',
                'icon'               => '📨',
                'description'        => 'Manage emails and calendar events through Microsoft Outlook.',
                'website_url'        => 'https://outlook.com',
                'sort_order'         => 13,
                'is_oauth'           => true,
                'oauth_auth_url'     => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
                'oauth_token_url'    => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
                'oauth_scopes'       => 'offline_access Mail.ReadWrite Mail.Send Calendars.ReadWrite',
                'oauth_extra_params' => ['response_mode' => 'query'],
            ],

            // CRM
            ['slug' => 'salesforce',      'name' => 'Salesforce',       'category' => 'CRM',            'icon' => '☁️', 'description' => 'Create and update leads, contacts, opportunities, and cases in Salesforce.',                  'website_url' => 'https://salesforce.com',        'sort_order' => 20],
            ['slug' => 'hubspot',         'name' => 'HubSpot',          'category' => 'CRM',            'icon' => '🔶', 'description' => 'Manage contacts, deals, and marketing campaigns in HubSpot.',                               'website_url' => 'https://hubspot.com',           'sort_order' => 21],
            ['slug' => 'zoho-crm',        'name' => 'Zoho CRM',         'category' => 'CRM',            'icon' => '📊', 'description' => 'Sync leads, contacts, and sales pipeline data with Zoho CRM.',                              'website_url' => 'https://zoho.com/crm',          'sort_order' => 22],

            // Productivity
            ['slug' => 'google-calendar', 'name' => 'Google Calendar',  'category' => 'Productivity',   'icon' => '📅', 'description' => 'Create, read, and update events and reminders on Google Calendar.',                          'website_url' => 'https://calendar.google.com',  'sort_order' => 30],
            ['slug' => 'notion',          'name' => 'Notion',           'category' => 'Productivity',   'icon' => '📝', 'description' => 'Read and write Notion pages, databases, and blocks for knowledge management.',               'website_url' => 'https://notion.so',             'sort_order' => 31],
            ['slug' => 'google-drive',    'name' => 'Google Drive',     'category' => 'Productivity',   'icon' => '📁', 'description' => 'Upload, download, and manage files and folders in Google Drive.',                            'website_url' => 'https://drive.google.com',     'sort_order' => 32],
            ['slug' => 'sharepoint',      'name' => 'SharePoint',       'category' => 'Productivity',   'icon' => '🗂️', 'description' => 'Access and manage documents and lists in Microsoft SharePoint.',                             'website_url' => 'https://sharepoint.com',        'sort_order' => 33],
            ['slug' => 'jira',            'name' => 'Jira',             'category' => 'Productivity',   'icon' => '🎯', 'description' => 'Create and update issues, sprints, and project boards in Jira.',                             'website_url' => 'https://atlassian.com/jira',   'sort_order' => 34],

            // Data & Analytics
            ['slug' => 'google-sheets',   'name' => 'Google Sheets',    'category' => 'Data',           'icon' => '📊', 'description' => 'Read from and write to Google Sheets spreadsheets.',                                         'website_url' => 'https://sheets.google.com',    'sort_order' => 40],
            ['slug' => 'airtable',        'name' => 'Airtable',         'category' => 'Data',           'icon' => '🗃️', 'description' => 'Query and update records in Airtable bases and tables.',                                     'website_url' => 'https://airtable.com',         'sort_order' => 41],
            ['slug' => 'snowflake',       'name' => 'Snowflake',        'category' => 'Data',           'icon' => '❄️', 'description' => 'Run queries and retrieve data from Snowflake data warehouses.',                               'website_url' => 'https://snowflake.com',         'sort_order' => 42],

            // Development
            ['slug' => 'github',          'name' => 'GitHub',           'category' => 'Development',    'icon' => '🐙', 'description' => 'Manage repos, issues, pull requests, and actions on GitHub.',                                'website_url' => 'https://github.com',           'sort_order' => 50],
            ['slug' => 'gitlab',          'name' => 'GitLab',           'category' => 'Development',    'icon' => '🦊', 'description' => 'Interact with GitLab projects, merge requests, and CI/CD pipelines.',                       'website_url' => 'https://gitlab.com',           'sort_order' => 51],
            ['slug' => 'jenkins',         'name' => 'Jenkins',          'category' => 'Development',    'icon' => '🔧', 'description' => 'Trigger builds and query job status from Jenkins CI servers.',                               'website_url' => 'https://jenkins.io',           'sort_order' => 52],

            // E-commerce
            ['slug' => 'shopify',         'name' => 'Shopify',          'category' => 'E-commerce',     'icon' => '🛍️', 'description' => 'Manage products, orders, and customers in Shopify stores.',                                  'website_url' => 'https://shopify.com',          'sort_order' => 60],
            ['slug' => 'stripe',          'name' => 'Stripe',           'category' => 'Finance',        'icon' => '💳', 'description' => 'Retrieve payment, subscription, and customer data from Stripe.',                            'website_url' => 'https://stripe.com',           'sort_order' => 70],
            ['slug' => 'quickbooks',      'name' => 'QuickBooks',       'category' => 'Finance',        'icon' => '🧾', 'description' => 'Sync invoices, expenses, and accounting data with QuickBooks Online.',                       'website_url' => 'https://quickbooks.intuit.com','sort_order' => 71],

            // Government / Procurement
            ['slug' => 'sam-gov',         'name' => 'SAM.gov',          'category' => 'Government',     'icon' => '🏛️', 'description' => 'Search federal contracts, awards, and entity registrations on SAM.gov.',                    'website_url' => 'https://sam.gov',              'sort_order' => 80],
            ['slug' => 'usaspending',     'name' => 'USASpending.gov',  'category' => 'Government',     'icon' => '🇺🇸', 'description' => 'Query federal spending, contracts, and grants from USASpending.gov.',                       'website_url' => 'https://usaspending.gov',      'sort_order' => 81],
        ];

        foreach ($connectors as $data) {
            Connector::updateOrCreate(['slug' => $data['slug']], array_merge($data, ['is_active' => true]));
        }
    }
}
