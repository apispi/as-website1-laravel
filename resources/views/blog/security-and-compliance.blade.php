@extends('layouts.app')

@section('title', 'Security and Compliance: Protecting Your Data - ApiSpi')

@section('content')
    <section class="blog-post">
        <div class="post-header">
            <h1>Security and Compliance: Protecting Your Data</h1>
            <div class="post-meta">
                <span>April 19, 2026</span>
                <span>•</span>
                <span>By ApiSpi Team</span>
                <span>•</span>
                <span>10 min read</span>
            </div>
        </div>

        <div class="post-content">
            <p>When you deploy an AI agent, you are placing a capable autonomous system at the boundary between your organization and the outside world. That boundary demands serious security engineering. This guide covers how ApiSpi protects your data at the platform level, and what you need to implement on your side to run a secure deployment.</p>

            <h2>Platform Security Foundations</h2>

            <h3>Data Encryption</h3>
            <p>All data transmitted to and from the ApiSpi platform is encrypted in transit using TLS 1.3. Data stored at rest—conversation logs, agent configurations, vector embeddings—is encrypted using AES-256. Encryption keys are managed through a dedicated key management service with automatic rotation and full audit logging of every key access event.</p>

            <h3>Data Residency</h3>
            <p>Enterprise customers can specify the geographic region where their data is stored and processed. This is essential for organizations subject to data residency regulations such as GDPR (EU), the Australian Privacy Act, or similar frameworks. Data never leaves the selected region without explicit customer consent.</p>

            <h3>Tenant Isolation</h3>
            <p>Customer data is logically isolated at every layer of the stack. Separate encryption keys, separate database namespaces, and strict API authorization ensure that one customer's data is never accessible to another, even in the event of an application-layer bug.</p>

            <h2>1. Compliance Certifications</h2>

            <h3>SOC 2 Type II</h3>
            <p>ApiSpi maintains a SOC 2 Type II attestation covering the security, availability, and confidentiality trust service criteria. The report is updated annually and available to customers under NDA. A SOC 2 Type II report is the baseline documentation most enterprise security teams require before approving a SaaS vendor.</p>

            <h3>ISO 27001</h3>
            <p>Our information security management system is certified to ISO 27001. This certification demonstrates that security is managed systematically—with documented policies, regular risk assessments, and continuous improvement cycles—rather than addressed ad hoc.</p>

            <h3>GDPR and Privacy Regulations</h3>
            <p>ApiSpi serves as a data processor under GDPR. We provide a Data Processing Agreement (DPA) that governs how we handle personal data on your behalf, specifying our obligations, sub-processors, and data subject rights support. Our privacy engineering team reviews every new feature for privacy impact before release.</p>

            <h3>HIPAA Availability</h3>
            <p>For healthcare customers, we offer a HIPAA-eligible configuration with a Business Associate Agreement (BAA). HIPAA-eligible deployments include additional controls: enhanced audit logging, restricted data retention, and dedicated infrastructure isolated from non-HIPAA workloads. Contact our team to discuss requirements.</p>

            <h2>2. Access Control</h2>

            <h3>Role-Based Access Control (RBAC)</h3>
            <p>The ApiSpi dashboard supports granular RBAC. Define custom roles with precise permissions—read-only access to analytics, write access to agent configuration, admin access to billing—and assign them to team members. Avoid using admin accounts for routine work; create task-specific roles and apply the principle of least privilege consistently.</p>

            <h3>Single Sign-On (SSO)</h3>
            <p>Enterprise plans support SAML 2.0 and OIDC-based SSO with your identity provider. SSO integration means team members use your organization's authentication (with MFA enforced at your IdP) rather than separate ApiSpi passwords, and deprovisioning is instant when someone leaves the organization.</p>

            <h3>API Key Management</h3>
            <p>Rotate API keys regularly. Use separate keys for each environment and application. Never embed API keys in client-side code or version control systems. Use a secrets management solution (such as AWS Secrets Manager, HashiCorp Vault, or Azure Key Vault) to inject keys at runtime rather than hard-coding them in configuration files.</p>

            <h2>3. Prompt Injection and Agent Safety</h2>

            <h3>Understanding Prompt Injection</h3>
            <p>Prompt injection is the most significant security risk specific to AI agents. An attacker embeds instructions in content the agent will process—a document, a web page, a user input—attempting to hijack the agent's behavior. For example, a malicious document might contain hidden text instructing the agent to exfiltrate data or take unauthorized actions.</p>

            <h3>Input Sanitization</h3>
            <p>Sanitize all user inputs before passing them to the agent. Strip HTML and markdown from inputs that should be plain text. Wrap external content (documents, web pages, emails) in clear delimiters so the agent understands that content is data to be processed, not instructions to be followed.</p>

            <h3>Tool Permission Scoping</h3>
            <p>Give each agent only the tools it genuinely needs. An agent that handles customer enquiries should not have access to tools that can modify billing data or delete records. Narrow tool permissions dramatically reduce the blast radius if an agent is successfully manipulated.</p>

            <h3>Output Validation</h3>
            <p>Validate agent outputs before acting on them. If an agent's output drives a downstream action—sending an email, writing to a database, calling an external API—inspect the output for anomalies before executing the action. Anomaly detection is especially important for high-stakes operations.</p>

            <h2>4. Data Minimization and Retention</h2>

            <h3>Minimize Data in Prompts</h3>
            <p>Include only the data the agent genuinely needs to fulfil a request in the prompt. Do not pass full user profiles, complete account histories, or entire database records when a few relevant fields will do. Less data in prompts means less data at risk if a session is somehow compromised.</p>

            <h3>Retention Policies</h3>
            <p>Configure data retention policies in the dashboard to match your compliance requirements. Conversation logs can be retained for days, months, or years, or purged automatically after a specified period. For sensitive use cases, consider short retention windows and export the data you need for analytics to your own secure storage.</p>

            <h3>Right to Erasure</h3>
            <p>If your agents handle personal data of EU residents or other individuals with erasure rights, map your user identifiers to ApiSpi session IDs so you can fulfil deletion requests. Our API supports deletion of all data associated with a given session ID.</p>

            <h2>5. Incident Response</h2>

            <h3>Monitoring for Anomalies</h3>
            <p>Enable anomaly detection alerts in the dashboard. Unusual patterns—a spike in failed tool calls, a sudden increase in long responses, unexpected geographic origins of API requests—can indicate a security event. Investigate anomalies promptly; many incidents are caught and contained because someone noticed something unusual before it escalated.</p>

            <h3>Our Breach Notification Commitment</h3>
            <p>In the event of a confirmed security incident affecting customer data, we commit to notifying affected customers within 72 hours of confirmation, in accordance with GDPR Article 33 and equivalent regulations. Notifications include the nature of the incident, the data involved, and our remediation actions.</p>

            <h3>Your Incident Response Plan</h3>
            <p>Have a plan for what you will do if your ApiSpi integration is compromised. Know how to rotate API keys immediately, how to disable an agent, how to pull conversation logs for forensics, and who in your organization needs to be notified. Practice the plan—a tabletop exercise takes two hours and is worth the investment.</p>

            <h2>Conclusion</h2>
            <p>Security is not a feature you add after launch—it is a property of the entire system, built in from the first design decision. The platform handles a significant portion of the security burden, but your application-level choices—how you manage keys, how you scope permissions, how you sanitize inputs—determine whether the full system is genuinely secure.</p>

            <p>Have questions about security requirements for your deployment? <a href="{{ route('contact') }}" style="color: #FCD34D; text-decoration: none;">Contact our security team</a> for a detailed review.</p>

            <div style="margin-top: 3rem; padding: 2rem; background: rgba(217, 119, 6, 0.05); border-left: 4px solid #EA580C; border-radius: 0.5rem;">
                <h3 style="margin-top: 0;">Secure Your Integration</h3>
                <p>Read the <a href="{{ route('blog.show', 'api-integration-guide') }}" style="color: #FCD34D; text-decoration: none;">API Integration Guide</a> alongside this article to ensure your integration is built securely from day one.</p>
            </div>
        </div>
    </section>

    <section class="latest-posts" style="padding: 4rem 0; margin-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
        <div class="container">
            <h2>Related Articles</h2>
            <div class="blog-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); margin-top: 2rem;">
                <article class="blog-card">
                    <div class="blog-date">April 22, 2026</div>
                    <h3><a href="{{ route('blog.show', 'api-integration-guide') }}">API Integration Guide: Connect Your Systems</a></h3>
                    <p>Complete guide on integrating ApiSpi agents with your existing systems via our API.</p>
                    <div class="blog-meta"><span class="category">Integration</span><span class="read-time">13 min read</span></div>
                </article>
                <article class="blog-card">
                    <div class="blog-date">May 12, 2026</div>
                    <h3><a href="{{ route('blog.show', 'agent-best-practices') }}">Best Practices for Production AI Agents</a></h3>
                    <p>Proven strategies for deploying reliable, scalable agents at enterprise scale.</p>
                    <div class="blog-meta"><span class="category">Guide</span><span class="read-time">12 min read</span></div>
                </article>
                <article class="blog-card">
                    <div class="blog-date">April 28, 2026</div>
                    <h3><a href="{{ route('blog.show', 'monitoring-optimization') }}">Monitoring and Optimization: Keeping Your Agents Healthy</a></h3>
                    <p>Essential monitoring practices and optimization techniques for peak agent performance.</p>
                    <div class="blog-meta"><span class="category">Operations</span><span class="read-time">9 min read</span></div>
                </article>
            </div>
        </div>
    </section>
@endsection
