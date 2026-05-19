@extends('layouts.app')

@section('title', 'API Integration Guide: Connect Your Systems - ApiSpi')

@section('content')
    <section class="blog-post">
        <div class="post-header">
            <h1>API Integration Guide: Connect Your Systems</h1>
            <div class="post-meta">
                <span>April 22, 2026</span>
                <span>•</span>
                <span>By ApiSpi Team</span>
                <span>•</span>
                <span>13 min read</span>
            </div>
        </div>

        <div class="post-content">
            <p>An AI agent is only as powerful as the systems it can reach. This guide walks through every integration pattern the ApiSpi platform supports—from simple REST webhooks to complex bidirectional data pipelines—so you can connect your agents to the tools your organization already uses.</p>

            <h2>Integration Architecture Overview</h2>
            <p>ApiSpi exposes three primary integration surfaces:</p>
            <ul style="margin-left: 2rem; margin-bottom: 1.5rem;">
                <li style="margin-bottom: 0.5rem;"><strong>Inbound API:</strong> Send requests to an agent from your application</li>
                <li style="margin-bottom: 0.5rem;"><strong>Outbound tools:</strong> Give your agent the ability to call your APIs and services</li>
                <li style="margin-bottom: 0.5rem;"><strong>Webhooks and events:</strong> Receive real-time notifications when agent activity occurs</li>
            </ul>
            <p>Most production integrations use all three in combination. A user action in your application triggers an inbound request; the agent calls several outbound tools to fulfil the request; your application receives a webhook when the response is ready.</p>

            <h2>1. Authentication and Authorization</h2>

            <h3>API Keys</h3>
            <p>All ApiSpi API calls require a bearer token in the Authorization header. Generate API keys from the dashboard under Settings → API Keys. Create separate keys for each environment (development, staging, production) and rotate them on a regular schedule—at least annually, or immediately after any suspected compromise.</p>

            <h3>Scoped Permissions</h3>
            <p>API keys support scoped permissions. A key used only by your data pipeline to read conversation logs does not need write access to agent configuration. Apply the principle of least privilege: grant each key only the permissions it genuinely needs.</p>

            <h3>Webhook Signature Verification</h3>
            <p>ApiSpi signs all outbound webhook payloads with HMAC-SHA256 using your webhook secret. Always verify the signature before processing a webhook payload. Do not process payloads from unknown sources, even if the content looks valid.</p>

            <h2>2. The Inbound API</h2>

            <h3>Synchronous Requests</h3>
            <p>Use the synchronous endpoint for interactions that must complete before your application can proceed. The endpoint accepts a JSON payload containing the agent ID, the user message, optional session context, and any metadata you want attached to the interaction.</p>
            <p>Synchronous calls block until the agent produces a response. Set your HTTP client timeout to at least 60 seconds—complex agent tasks involving multiple tool calls can take longer than typical web requests.</p>

            <h3>Asynchronous Requests</h3>
            <p>For long-running tasks or workloads where you do not need an immediate response, use the asynchronous endpoint. You receive a job ID immediately; the agent processes the request in the background and delivers the result via webhook when complete. This pattern is essential for batch processing and any interaction triggered by an automated workflow rather than a waiting user.</p>

            <h3>Streaming Responses</h3>
            <p>When building chat interfaces where perceived speed matters, use the streaming endpoint. The response is delivered as a Server-Sent Events stream, allowing your application to render tokens progressively as they are generated rather than waiting for the complete response.</p>

            <h2>3. Outbound Tools — Extending Agent Capabilities</h2>

            <h3>Defining Custom Tools</h3>
            <p>Custom tools let your agent call your internal APIs. Define each tool with a name, description, and JSON Schema for its parameters. The description is critical—it is the text the model uses to decide when to call your tool. Write it precisely: describe what the tool does, when to use it, and what it returns.</p>

            <h3>Tool Endpoint Requirements</h3>
            <p>Tool endpoints must be publicly accessible HTTPS endpoints (or reachable via our VPC peering option for private network deployments). They must respond within 30 seconds. For operations that take longer, implement an async pattern: return a pending status immediately and provide a polling or webhook mechanism for the result.</p>

            <h3>Error Response Format</h3>
            <p>Return errors as JSON with a consistent structure: an error code, a human-readable message, and optional detail fields. The agent can interpret and respond to structured errors; it cannot do anything useful with an HTML error page or an empty 500 response.</p>

            <h3>Pre-Built Connectors</h3>
            <p>ApiSpi ships pre-built connectors for common enterprise tools. No custom code is required to connect your agent to Salesforce, Jira, Confluence, Slack, Microsoft Teams, Google Workspace, ServiceNow, or Zendesk. Enable a connector from the dashboard, authorize it with OAuth, and it is available to your agent as a set of pre-defined tools.</p>

            <h2>4. Webhooks and Event Streams</h2>

            <h3>Configuring Webhooks</h3>
            <p>Register webhook endpoints in the dashboard under Settings → Webhooks. You can subscribe to specific event types—conversation completed, tool call failed, quality score below threshold, human escalation triggered—rather than receiving all events. Filter early to avoid processing overhead on your receiving end.</p>

            <h3>Event Payload Structure</h3>
            <p>Every webhook payload includes the event type, a timestamp, the agent ID, a conversation ID you can use for correlation, and event-specific data. Store conversation IDs in your own system from the moment a conversation starts—they are the key to reconstructing what happened from your logs and ours.</p>

            <h3>Retry and Idempotency</h3>
            <p>ApiSpi retries failed webhook deliveries with exponential backoff for up to 24 hours. Your receiving endpoint must be idempotent—processing the same event twice should produce the same outcome as processing it once. Use the event ID as an idempotency key: if you have already processed an event with that ID, skip it.</p>

            <h2>5. Session and Context Management</h2>

            <h3>Session IDs</h3>
            <p>Pass a stable session ID with each request to maintain conversation continuity. The agent uses the session ID to retrieve prior turns and relevant context. Use your own user or conversation identifiers as session IDs rather than generating random values—this makes correlation between your systems and ApiSpi logs straightforward.</p>

            <h3>Context Injection</h3>
            <p>The context field in the request payload lets you inject structured data that the agent should have available but that is not part of the user's message—the current user's account tier, the page they are on, the state of an open support ticket. Keep injected context concise and relevant; extraneous data dilutes the agent's attention.</p>

            <h3>Session Expiry</h3>
            <p>Sessions expire after a configurable period of inactivity (default 30 minutes). When a session expires, the next request starts a fresh conversation. Design your application to handle this gracefully—if continuity matters, re-inject the necessary context on the new session rather than assuming the agent remembers.</p>

            <h2>6. Testing Your Integration</h2>

            <h3>Sandbox Environment</h3>
            <p>Use the sandbox environment for all development and testing. The sandbox is isolated from production, uses test API keys, and will not trigger real outbound tool calls unless you explicitly configure sandbox tool endpoints. Changes to agent configuration in the sandbox do not affect production.</p>

            <h3>Replay Testing</h3>
            <p>The dashboard's replay feature lets you re-run any past conversation through the current agent configuration. Use this to verify that a prompt change does not break existing interactions before deploying to production.</p>

            <h3>Load Testing</h3>
            <p>Before a major launch, coordinate with our team to run a load test against your integration. We can help size your rate limits appropriately and identify any bottlenecks in your tool endpoints before real user traffic arrives.</p>

            <h2>Conclusion</h2>
            <p>A well-integrated agent disappears into your product—users interact with your application and the agent works behind the scenes, pulling data from your systems and taking actions on behalf of users. Getting the integration right takes upfront investment, but it is what transforms an interesting demo into a genuinely useful product.</p>

            <p>Need help with your integration? <a href="{{ route('contact') }}" style="color: #FCD34D; text-decoration: none;">Our solutions team</a> offers integration workshops for enterprise customers.</p>

            <div style="margin-top: 3rem; padding: 2rem; background: rgba(217, 119, 6, 0.05); border-left: 4px solid #EA580C; border-radius: 0.5rem;">
                <h3 style="margin-top: 0;">Security First</h3>
                <p>Integrations that expose internal systems deserve careful security review. Read our <a href="{{ route('blog.show', 'security-and-compliance') }}" style="color: #FCD34D; text-decoration: none;">Security and Compliance guide</a> before moving to production.</p>
            </div>
        </div>
    </section>

    <section class="latest-posts" style="padding: 4rem 0; margin-top: 4rem; border-top: 1px solid rgba(217, 119, 6, 0.1);">
        <div class="container">
            <h2>Related Articles</h2>
            <div class="blog-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); margin-top: 2rem;">
                <article class="blog-card">
                    <div class="blog-date">May 5, 2026</div>
                    <h3><a href="{{ route('blog.show', 'building-custom-agents') }}">Building Custom Agents: Advanced Techniques</a></h3>
                    <p>Deep dive into advanced techniques for building specialized agents tailored to your needs.</p>
                    <div class="blog-meta"><span class="category">Advanced</span><span class="read-time">15 min read</span></div>
                </article>
                <article class="blog-card">
                    <div class="blog-date">April 19, 2026</div>
                    <h3><a href="{{ route('blog.show', 'security-and-compliance') }}">Security and Compliance: Protecting Your Data</a></h3>
                    <p>Security features, compliance certifications, and best practices for data protection.</p>
                    <div class="blog-meta"><span class="category">Security</span><span class="read-time">10 min read</span></div>
                </article>
                <article class="blog-card">
                    <div class="blog-date">May 12, 2026</div>
                    <h3><a href="{{ route('blog.show', 'agent-best-practices') }}">Best Practices for Production AI Agents</a></h3>
                    <p>Proven strategies for deploying reliable, scalable agents at enterprise scale.</p>
                    <div class="blog-meta"><span class="category">Guide</span><span class="read-time">12 min read</span></div>
                </article>
            </div>
        </div>
    </section>
@endsection
