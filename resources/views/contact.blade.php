@extends('layouts.app')

@section('title', 'Contact ApiSpi — Get in Touch')

@push('head')
<style>
/* ── Page layout ───────────────────────────────────────────────── */
.contact-hero { padding: 6rem 0 3rem; text-align: center; }
.contact-hero h1 { font-size: 2.8rem; margin-bottom: 0.75rem; }
.contact-hero p { font-size: 1.1rem; color: var(--gray); max-width: 540px; margin: 0 auto; }
.hero-actions { margin-top: 1.75rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; }
.partner-btn { background: linear-gradient(135deg, #D97706, #F59E0B) !important; color: #0a0805 !important; font-weight: 700; border: none; cursor: pointer; font-family: inherit; font-size: 1rem; padding: 0.875rem 2rem; border-radius: 0.625rem; transition: all 0.2s; }
.partner-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(217,119,6,0.4); }

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2.5rem;
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 0 4rem;
    align-items: start;
}
@media (max-width: 860px) { .contact-grid { grid-template-columns: 1fr; } }

/* ── Chat widget ───────────────────────────────────────────────── */
.chat-widget {
    background: rgba(14, 10, 5, 0.9);
    border: 1px solid rgba(217,119,6,0.25);
    border-radius: 1.25rem;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 620px;
}
.chat-header {
    padding: 1.1rem 1.5rem;
    background: rgba(217,119,6,0.08);
    border-bottom: 1px solid rgba(217,119,6,0.15);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
}
.chat-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, #D97706, #FCD34D);
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1rem; color: #0a0805;
    flex-shrink: 0;
}
.chat-header-text { flex: 1; }
.chat-header-name { font-size: 0.95rem; font-weight: 700; color: #f1f5f9; }
.chat-header-status { font-size: 0.75rem; color: #00d97e; display: flex; align-items: center; gap: 0.35rem; }
.chat-status-dot { width: 7px; height: 7px; border-radius: 50%; background: #00d97e; }

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
    scrollbar-width: thin;
    scrollbar-color: rgba(217,119,6,0.2) transparent;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-thumb { background: rgba(217,119,6,0.2); border-radius: 2px; }

.chat-msg { display: flex; gap: 0.6rem; max-width: 88%; }
.chat-msg.user { align-self: flex-end; flex-direction: row-reverse; }
.chat-msg.bot  { align-self: flex-start; }

.msg-avatar {
    width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0; margin-top: 2px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 700;
}
.chat-msg.bot  .msg-avatar { background: linear-gradient(135deg, #D97706, #FCD34D); color: #0a0805; }
.chat-msg.user .msg-avatar { background: rgba(217,119,6,0.2); color: #FCD34D; font-size: 0.65rem; }

.msg-bubble {
    padding: 0.65rem 0.9rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    line-height: 1.55;
    white-space: pre-wrap;
    word-break: break-word;
}
.chat-msg.bot  .msg-bubble { background: rgba(217,119,6,0.08); border: 1px solid rgba(217,119,6,0.15); color: #e5e7eb; border-bottom-left-radius: 4px; }
.chat-msg.user .msg-bubble { background: rgba(217,119,6,0.18); border: 1px solid rgba(217,119,6,0.3); color: #fef3c7; border-bottom-right-radius: 4px; }

.chat-msg.bot .msg-bubble strong { color: #FCD34D; }
.chat-msg.bot .msg-bubble ul,
.chat-msg.bot .msg-bubble ol { padding-left: 1.2rem; margin: 0.4rem 0; }
.chat-msg.bot .msg-bubble li { margin-bottom: 0.2rem; }

.chat-typing { display: flex; align-items: center; gap: 4px; padding: 0.5rem 0.75rem; }
.typing-dot { width: 6px; height: 6px; border-radius: 50%; background: #D97706; opacity: 0.4; animation: typing-blink 1.2s infinite; }
.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes typing-blink { 0%,100% { opacity: 0.4; transform: scale(1); } 50% { opacity: 1; transform: scale(1.3); } }

.chat-suggestions {
    padding: 0 1.25rem 0.75rem;
    display: flex; gap: 0.5rem; flex-wrap: wrap; flex-shrink: 0;
}
.chat-suggestion {
    padding: 0.35rem 0.75rem; border-radius: 99px; cursor: pointer;
    border: 1px solid rgba(217,119,6,0.25); background: rgba(217,119,6,0.06);
    color: #FCD34D; font-size: 0.75rem; font-family: inherit;
    transition: all 0.18s; white-space: nowrap;
}
.chat-suggestion:hover { background: rgba(217,119,6,0.15); border-color: rgba(217,119,6,0.45); }

.chat-input-row {
    padding: 0.875rem 1rem;
    border-top: 1px solid rgba(217,119,6,0.12);
    display: flex; gap: 0.6rem; align-items: flex-end; flex-shrink: 0;
    background: rgba(10,8,5,0.5);
}
.chat-input {
    flex: 1; padding: 0.65rem 0.875rem; resize: none; max-height: 120px;
    background: rgba(20,12,6,0.9); border: 1px solid rgba(217,119,6,0.2);
    border-radius: 0.75rem; color: #e5e7eb; font-size: 0.9rem; font-family: inherit;
    transition: border-color 0.18s; line-height: 1.4;
    scrollbar-width: thin; overflow-y: auto;
}
.chat-input:focus { outline: none; border-color: rgba(217,119,6,0.5); }
.chat-input::placeholder { color: #4b5563; }
.chat-send {
    width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #D97706, #FCD34D);
    border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: transform 0.15s, opacity 0.15s; font-size: 1rem; color: #0a0805;
}
.chat-send:hover { transform: scale(1.08); }
.chat-send:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
.chat-send svg { width: 16px; height: 16px; fill: currentColor; }

/* ── Contact form ──────────────────────────────────────────────── */
.contact-form-card {
    background: rgba(28,24,16,0.6);
    border: 1px solid rgba(217,119,6,0.15);
    border-radius: 1.25rem;
    padding: 2rem;
}
.contact-form-card h2 { font-size: 1.3rem; font-weight: 700; color: #f1f5f9; margin-bottom: 1.5rem; }

.cf-group { margin-bottom: 1.25rem; }
.cf-label { display: block; margin-bottom: 0.4rem; font-weight: 600; font-size: 0.85rem; color: #FCD34D; }
.cf-input,
.cf-select,
.cf-textarea {
    width: 100%; padding: 0.8rem 1rem;
    background: rgba(217,119,6,0.07); border: 2px solid rgba(217,119,6,0.4);
    border-radius: 0.625rem; color: #f9fafb; font-size: 0.95rem; font-family: inherit;
    transition: border-color 0.18s, background 0.18s, box-shadow 0.18s; box-sizing: border-box;
}
.cf-input:hover, .cf-select:hover, .cf-textarea:hover {
    border-color: rgba(245,158,11,0.65); background: rgba(217,119,6,0.1);
}
.cf-input:focus, .cf-select:focus, .cf-textarea:focus {
    outline: none; border-color: #F59E0B; background: rgba(217,119,6,0.12);
    box-shadow: 0 0 0 3px rgba(245,158,11,0.18);
}
.cf-input::placeholder, .cf-textarea::placeholder { color: rgba(217,119,6,0.45); }
.cf-select option { background: #140a00; }
.cf-textarea { resize: vertical; min-height: 120px; }
.cf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media (max-width: 480px) { .cf-row { grid-template-columns: 1fr; } }
.cf-submit {
    width: 100%; padding: 0.875rem; border: none; border-radius: 0.625rem; cursor: pointer;
    background: linear-gradient(135deg, #D97706, #F59E0B);
    color: #0a0805; font-size: 1rem; font-weight: 700; font-family: inherit;
    transition: all 0.2s; margin-top: 0.5rem;
}
.cf-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(217,119,6,0.35); }

/* ── Info cards ────────────────────────────────────────────────── */
.info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    max-width: 1100px;
    margin: 0 auto;
    padding-bottom: 4rem;
}
.info-card {
    background: rgba(28,24,16,0.6);
    border: 1px solid rgba(217,119,6,0.1);
    border-radius: 1rem;
    padding: 1.5rem;
}
.info-card-icon { font-size: 1.5rem; margin-bottom: 0.75rem; }
.info-card h3 { font-size: 0.9rem; font-weight: 700; color: #FCD34D; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
.info-card p, .info-card a { font-size: 0.875rem; color: #9ca3af; line-height: 1.6; }
.info-card a { color: #FCD34D; text-decoration: none; }
.info-card a:hover { text-decoration: underline; }

/* ── FAQ ───────────────────────────────────────────────────────── */
.faq-section { padding: 4rem 0; border-top: 1px solid rgba(217,119,6,0.1); }
.faq-section h2 { text-align: center; margin-bottom: 2.5rem; }
.faq-list { max-width: 760px; margin: 0 auto; display: flex; flex-direction: column; gap: 0.75rem; }
details.faq-item {
    padding: 1.25rem 1.5rem;
    background: rgba(28,24,16,0.6);
    border: 1px solid rgba(217,119,6,0.1);
    border-radius: 0.875rem; cursor: pointer;
}
details.faq-item summary { font-weight: 600; cursor: pointer; color: #e5e7eb; }
details.faq-item summary:hover,
details.faq-item[open] summary { color: #FCD34D; }
details.faq-item p { margin-top: 0.875rem; color: #6b7280; font-size: 0.9rem; line-height: 1.65; }

.flash-success {
    max-width: 1100px; margin: 0 auto 1.5rem;
    padding: 1rem 1.5rem; background: rgba(0,217,126,0.1);
    border: 1px solid rgba(0,217,126,0.3); border-radius: 0.75rem; color: #00d97e;
}
.flash-error {
    max-width: 1100px; margin: 0 auto 1.5rem;
    padding: 1rem 1.5rem; background: rgba(255,59,48,0.1);
    border: 1px solid rgba(255,59,48,0.3); border-radius: 0.75rem; color: #ff3b30;
}
</style>
@endpush

@section('content')

<section class="contact-hero">
    <div class="container">
        <h1>Get in <span class="gradient-text">Touch</span></h1>
        <p>Chat with our AI or send us a message — we'd love to hear from you.</p>
        <div class="hero-actions">
            <a href="/partners" class="btn btn-primary partner-btn">Partner with Us</a>
        </div>
    </div>
</section>

<section style="padding: 0 0 2rem;">
    <div class="container">

        @if(session('success'))
            <div class="flash-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="flash-error">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <div class="contact-grid">

            <!-- ── AI Chat Widget ── -->
            <div>
                <div id="chatWidget" class="chat-widget">
                    <div class="chat-header">
                        <div class="chat-avatar">A</div>
                        <div class="chat-header-text">
                            <div class="chat-header-name">Aria — ApiSpi AI</div>
                            <div class="chat-header-status">
                                <span class="chat-status-dot"></span> Online
                            </div>
                        </div>
                    </div>

                    <div class="chat-messages" id="chatMessages">
                        <div class="chat-msg bot">
                            <div class="msg-avatar">A</div>
                            <div class="msg-bubble">
                                Hi there! I'm Aria, ApiSpi's AI assistant.<br><br>
                                I can tell you about our AI agents, training courses, pricing, and more. What would you like to know?
                            </div>
                        </div>
                    </div>

                    <div class="chat-suggestions" id="chatSuggestions">
                        <button class="chat-suggestion" onclick="sendSuggestion(this)">What agents do you offer?</button>
                        <button class="chat-suggestion" onclick="sendSuggestion(this)">Tell me about training</button>
                        <button class="chat-suggestion" onclick="sendSuggestion(this)">Pricing info</button>
                        <button class="chat-suggestion" onclick="sendSuggestion(this)">Book a demo</button>
                    </div>

                    <div class="chat-input-row">
                        <textarea
                            id="chatInput"
                            class="chat-input"
                            placeholder="Ask me anything…"
                            rows="1"
                            onkeydown="handleChatKey(event)"
                            oninput="autoResize(this)"
                        ></textarea>
                        <button class="chat-send" id="chatSend" onclick="sendMessage()" title="Send">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ── Contact Form ── -->
            <div>
                <div class="contact-form-card">
                    <h2>Send us a Message</h2>
                    <form id="contactForm" method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="cf-row">
                            <div class="cf-group">
                                <label class="cf-label" for="name">Name *</label>
                                <input class="cf-input" type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="Your name">
                            </div>
                            <div class="cf-group">
                                <label class="cf-label" for="email">Email *</label>
                                <input class="cf-input" type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="you@company.com">
                            </div>
                        </div>
                        <div class="cf-group">
                            <label class="cf-label" for="company">Company</label>
                            <input class="cf-input" type="text" id="company" name="company" value="{{ old('company') }}" placeholder="Company name (optional)">
                        </div>
                        <div class="cf-group">
                            <label class="cf-label" for="subject">Subject *</label>
                            <select class="cf-select" id="subject" name="subject" required>
                                <option value="">Select a subject…</option>
                                <option value="sales"       @selected(old('subject') === 'sales')>Sales Inquiry</option>
                                <option value="support"     @selected(old('subject') === 'support')>Product Support</option>
                                <option value="demo"        @selected(old('subject') === 'demo')>Request a Demo</option>
                                <option value="training"    @selected(old('subject') === 'training')>Training Enquiry</option>
                                <option value="partnership" @selected(old('subject') === 'partnership')>Partnership</option>
                                <option value="feedback"    @selected(old('subject') === 'feedback')>Feedback</option>
                                <option value="other"       @selected(old('subject') === 'other')>Other</option>
                            </select>
                        </div>
                        <div class="cf-group">
                            <label class="cf-label" for="message">Message *</label>
                            <textarea class="cf-textarea" id="message" name="message" required rows="5" placeholder="Tell us how we can help…">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="cf-submit">Send Message →</button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Contact info cards -->
        <div class="info-cards">
            <div class="info-card">
                <div class="info-card-icon">📧</div>
                <h3>Email Us</h3>
                <p>
                    <a href="mailto:sales@apispi.com">sales@apispi.com</a> — Sales & General<br>
                    <a href="mailto:payment@apispi.com">payment@apispi.com</a> — Payments
                </p>
            </div>
            <div class="info-card">
                <div class="info-card-icon">⏰</div>
                <h3>Support Hours</h3>
                <p>Monday–Friday: 9 AM – 6 PM AEST<br>Weekend: Limited support<br>Premium: 24/7 priority support</p>
            </div>
            <div class="info-card">
                <div class="info-card-icon">💬</div>
                <h3>Quick Help</h3>
                <p>Use the Aria chat on this page for instant answers about our agents, training, and pricing.</p>
            </div>
        </div>

    </div>
</section>

<!-- FAQ -->
<section class="faq-section">
    <div class="container">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-list">
            <details class="faq-item">
                <summary>How long does it take to get a response?</summary>
                <p>We typically respond within 24 hours during business days. Use the Aria chat above for instant answers to common questions. Premium customers receive priority support.</p>
            </details>
            <details class="faq-item">
                <summary>Can we schedule a demo?</summary>
                <p>Yes! Select "Request a Demo" in the contact form above, or email <a href="mailto:sales@apispi.com" style="color:#FCD34D;">sales@apispi.com</a>. We're happy to walk you through any of our agents.</p>
            </details>
            <details class="faq-item">
                <summary>Do you offer custom agent solutions?</summary>
                <p>Absolutely. We work with enterprises on custom agent development and API integrations. Contact our sales team to discuss your specific requirements and get a tailored quote.</p>
            </details>
            <details class="faq-item">
                <summary>What's the difference between agents and training?</summary>
                <p>Our <strong>agents</strong> are subscription-based autonomous AI systems that run workflows for your business. Our <strong>training courses</strong> teach your team how to use AI tools effectively — from introductory workshops to advanced technical courses.</p>
            </details>
            <details class="faq-item">
                <summary>What payment methods do you accept?</summary>
                <p>We accept all major credit cards, bank transfer, and can set up custom invoicing for enterprise customers. For payment enquiries email <a href="mailto:payment@apispi.com" style="color:#FCD34D;">payment@apispi.com</a>.</p>
            </details>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2>Ready to Get Started?</h2>
        <p>Explore our agents or chat with Aria to find the right solution</p>
        <div class="cta-buttons">
            <a href="{{ route('agents.index') }}" class="btn btn-outline">Browse Agents</a>
            <a href="{{ route('training') }}" class="btn btn-secondary">View Training</a>
            <a href="/partners" class="btn btn-primary partner-btn">Partner with Us</a>
        </div>
    </div>
</section>

<script>
(function () {
    const CSRF = '{{ csrf_token() }}';
    let history = [];
    let sending = false;

    window.autoResize = function (el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    };

    window.handleChatKey = function (e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    };

    window.sendSuggestion = function (btn) {
        const text = btn.textContent.trim();
        document.getElementById('chatSuggestions').style.display = 'none';
        document.getElementById('chatInput').value = text;
        sendMessage();
    };

    window.sendMessage = async function () {
        if (sending) return;
        const input = document.getElementById('chatInput');
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        input.style.height = 'auto';
        appendMessage('user', text);
        const typing = appendTyping();
        setSending(true);

        try {
            const res = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                },
                body: JSON.stringify({ message: text, history }),
            });

            typing.remove();
            const data = await res.json();

            if (data.error) {
                appendMessage('bot', data.error);
            } else {
                history.push({ role: 'user', content: text });
                history.push({ role: 'assistant', content: data.reply });
                if (history.length > 20) history = history.slice(-20);
                appendMessage('bot', data.reply);
            }
        } catch {
            typing.remove();
            appendMessage('bot', 'Sorry, something went wrong. Please use the contact form below.');
        } finally {
            setSending(false);
        }
    };

    function appendMessage(role, text) {
        const msgs = document.getElementById('chatMessages');
        const isBot = role === 'bot';

        const wrapper = document.createElement('div');
        wrapper.className = `chat-msg ${isBot ? 'bot' : 'user'}`;

        const avatar = document.createElement('div');
        avatar.className = 'msg-avatar';
        avatar.textContent = isBot ? 'A' : 'You';

        const bubble = document.createElement('div');
        bubble.className = 'msg-bubble';

        if (isBot) {
            bubble.innerHTML = formatMarkdown(text);
        } else {
            bubble.textContent = text;
        }

        wrapper.appendChild(avatar);
        wrapper.appendChild(bubble);
        msgs.appendChild(wrapper);
        msgs.scrollTop = msgs.scrollHeight;
        return wrapper;
    }

    function appendTyping() {
        const msgs = document.getElementById('chatMessages');
        const wrapper = document.createElement('div');
        wrapper.className = 'chat-msg bot';
        wrapper.innerHTML = `
            <div class="msg-avatar">A</div>
            <div class="msg-bubble" style="padding:0.4rem 0.75rem;">
                <div class="chat-typing">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>`;
        msgs.appendChild(wrapper);
        msgs.scrollTop = msgs.scrollHeight;
        return wrapper;
    }

    function formatMarkdown(text) {
        return text
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.+?)\*/g, '<em>$1</em>')
            .replace(/^- (.+)$/gm, '<li>$1</li>')
            .replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>')
            .replace(/\n/g, '<br>');
    }

    function setSending(val) {
        sending = val;
        const btn = document.getElementById('chatSend');
        const input = document.getElementById('chatInput');
        btn.disabled = val;
        input.disabled = val;
    }
})();
</script>
@endsection
