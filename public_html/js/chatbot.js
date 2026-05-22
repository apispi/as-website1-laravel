(function () {

  // ── CSS ───────────────────────────────────────────────────────────────────
  const style = document.createElement('style');
  style.textContent = `
    .spi-fab {
      position: fixed; bottom: 24px; right: 24px;
      width: 56px; height: 56px; border-radius: 50%;
      background: linear-gradient(135deg, #FCD34D, #D97706);
      border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 20px rgba(217,119,6,0.45);
      z-index: 9998;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .spi-fab:hover { transform: scale(1.1); box-shadow: 0 6px 30px rgba(217,119,6,0.65); }
    .spi-fab svg { width: 26px; height: 26px; }
    .spi-window {
      position: fixed; bottom: 92px; right: 24px;
      width: 360px; background: #1C1810;
      border: 1px solid rgba(217,119,6,0.25); border-radius: 16px;
      display: flex; flex-direction: column;
      z-index: 9999;
      box-shadow: 0 8px 48px rgba(0,0,0,0.65);
      overflow: hidden;
      transform-origin: bottom right;
      transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), opacity 0.2s;
    }
    .spi-window.spi-hidden { transform: scale(0.85); opacity: 0; pointer-events: none; }
    .spi-header {
      padding: 14px 16px;
      background: linear-gradient(90deg, rgba(217,119,6,0.18), rgba(252,211,77,0.07));
      border-bottom: 1px solid rgba(217,119,6,0.2);
      display: flex; align-items: center; gap: 10px;
    }
    .spi-avatar {
      width: 36px; height: 36px; border-radius: 50%;
      background: linear-gradient(135deg, #FCD34D, #D97706);
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .spi-avatar svg { width: 20px; height: 22px; }
    .spi-header-info h4 { margin: 0; font-size: 0.9rem; font-weight: 700; color: #fff; font-family: 'Outfit', sans-serif; }
    .spi-header-info p  { margin: 0; font-size: 0.75rem; color: rgba(252,211,77,0.8); font-family: 'Outfit', sans-serif; }
    .spi-close {
      margin-left: auto; background: none; border: none;
      color: rgba(255,255,255,0.4); cursor: pointer;
      font-size: 1.3rem; padding: 2px 6px; line-height: 1;
      transition: color 0.2s;
    }
    .spi-close:hover { color: #fff; }
    .spi-messages {
      flex: 1; overflow-y: auto; padding: 16px;
      display: flex; flex-direction: column; gap: 10px;
      min-height: 280px; max-height: 320px;
      scrollbar-width: thin; scrollbar-color: rgba(217,119,6,0.3) transparent;
    }
    .spi-msg {
      max-width: 86%; padding: 10px 14px; border-radius: 14px;
      font-size: 0.85rem; line-height: 1.55;
      font-family: 'Outfit', sans-serif; white-space: pre-line;
    }
    .spi-msg.bot {
      background: rgba(217,119,6,0.09); border: 1px solid rgba(217,119,6,0.15);
      color: #e5e7eb; align-self: flex-start;
      border-radius: 4px 14px 14px 14px;
    }
    .spi-msg.user {
      background: linear-gradient(135deg, rgba(217,119,6,0.28), rgba(252,211,77,0.12));
      border: 1px solid rgba(252,211,77,0.18);
      color: #fff; align-self: flex-end;
      border-radius: 14px 14px 4px 14px;
    }
    .spi-typing {
      display: flex; gap: 5px; align-items: center;
      padding: 10px 14px;
      background: rgba(217,119,6,0.08); border: 1px solid rgba(217,119,6,0.15);
      border-radius: 4px 14px 14px 14px;
      align-self: flex-start;
    }
    .spi-typing span {
      width: 7px; height: 7px; border-radius: 50%; background: #D97706;
      animation: spi-dot 1.2s ease-in-out infinite;
    }
    .spi-typing span:nth-child(2) { animation-delay: 0.2s; }
    .spi-typing span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes spi-dot {
      0%, 60%, 100% { transform: translateY(0); opacity: 0.6; }
      30% { transform: translateY(-6px); opacity: 1; }
    }
    .spi-quick-replies { display: flex; flex-wrap: wrap; gap: 7px; padding: 4px 16px 10px; }
    .spi-qr {
      background: none; border: 1px solid rgba(217,119,6,0.4);
      color: #FCD34D; padding: 5px 12px; border-radius: 20px;
      font-size: 0.78rem; cursor: pointer;
      font-family: 'Outfit', sans-serif;
      transition: all 0.2s;
    }
    .spi-qr:hover { background: rgba(217,119,6,0.15); border-color: #D97706; }
    .spi-input-row {
      display: flex; gap: 8px; padding: 12px 16px;
      border-top: 1px solid rgba(217,119,6,0.15);
    }
    .spi-input {
      flex: 1; background: rgba(13,11,8,0.7);
      border: 1px solid rgba(217,119,6,0.2); border-radius: 8px;
      color: #fff; padding: 8px 12px;
      font-size: 0.85rem; font-family: 'Outfit', sans-serif;
      outline: none; transition: border-color 0.2s;
    }
    .spi-input:focus { border-color: #D97706; }
    .spi-input::placeholder { color: rgba(217,119,6,0.4); }
    .spi-send {
      width: 38px; height: 38px; border-radius: 8px;
      background: linear-gradient(135deg, #FCD34D, #D97706);
      border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0; transition: opacity 0.2s;
    }
    .spi-send:disabled { opacity: 0.4; cursor: default; }
    .spi-send svg { width: 16px; height: 16px; }
    @media (max-width: 420px) {
      .spi-window { width: calc(100vw - 32px); right: 16px; bottom: 84px; }
      .spi-fab    { right: 16px; bottom: 16px; }
    }
  `;
  document.head.appendChild(style);

  // ── LINK MAP ──────────────────────────────────────────────────────────────
  const LINKS = {
    'Our Agents':              '/agents',
    'Browse Agents':           '/agents',
    'All Agents':              '/agents',
    'Pricing':                 '/agents',
    'Training':                '/training',
    'View Training':           '/training',
    'All Courses':             '/training',
    'Contact Us':              '/contact',
    'Get in Touch':            '/contact',
    'Go to Contact':           '/contact',
    'Get Started':             '/contact',
    'Book a Demo':             '/contact',
    'Blog':                    '/blog',
    'Bid & Tender':            '/agents/bid-tender',
    'View Bid & Tender':       '/agents/bid-tender',
    'Security & IRAP':         '/agents/security-compliance',
    'View Security Agent':     '/agents/security-compliance',
    'Enterprise Architecture': '/agents/enterprise-architecture',
    'View Architecture Agent': '/agents/enterprise-architecture',
    'Digital Avatar':          '/agents/digital-avatar',
    'View Digital Avatar':     '/agents/digital-avatar',
    'Knowledge Management':    '/agents/knowledge-management',
    'View Knowledge Agent':    '/agents/knowledge-management',
    'Cyber Incident':          '/agents/cyber-incident',
    'View Cyber Agent':        '/agents/cyber-incident',
    'Content Creator':         '/agents/content-creator',
    'Customer Support Bot':    '/agents/support-bot',
    'Data Analyzer':           '/agents/data-analyzer',
  };

  // ── INTENT RESPONSES ──────────────────────────────────────────────────────
  const RESPONSES = {
    greet: {
      text: "Hey there! I'm Spi — your ApiSpi guide.\n\nAsk me about our AI agents, training courses, or anything else.",
      qr: ['Our Agents', 'Training', 'Pricing', 'Contact Us']
    },
    'agents.all': {
      text: "We have 9 AI agents ready to deploy:\n\n• Bid & Tender Response — $499/mo\n• Security & IRAP Readiness — $799/mo\n• Enterprise Architecture — $599/mo\n• Digital Avatar & Executive Clone — from $149/mo\n• Knowledge Management & SOP — $399/mo\n• Cyber Incident & Threat Intel — $699/mo\n• Content Creator — $29/mo\n• Customer Support Bot — $49/mo\n• Data Analyzer Pro — $79/mo\n\nAsk me about any of them!",
      qr: ['Bid & Tender', 'Security & IRAP', 'Digital Avatar', 'Cyber Incident']
    },
    'agents.bid': {
      text: "The Bid & Tender Response agent automates government RFQ/RFT responses, CV matching, selection criteria, and compliance matrices — saving procurement teams hours per submission.\n\nRating: ⭐ 4.9/5 · 340+ users\nFrom $299/mo (Starter) · $499/mo (Professional)",
      qr: ['View Bid & Tender', 'Pricing', 'Get Started']
    },
    'agents.security': {
      text: "The Security & IRAP Readiness agent guides organisations through Essential Eight, ISM, PSPF, IRAP, ISO 27001, and cloud security readiness assessments.\n\nRating: ⭐ 4.95/5 · 180+ users\nFrom $499/mo (SME) · $799/mo (Professional)",
      qr: ['View Security Agent', 'Get Started', 'Contact Us']
    },
    'agents.architecture': {
      text: "The Enterprise Architecture agent acts as a virtual enterprise/solution architect — generating ADRs, architecture options, target-state models, and migration roadmaps on demand.\n\nRating: ⭐ 4.85/5 · 210+ users\nFrom $349/mo (Starter) · $599/mo (Professional)",
      qr: ['View Architecture Agent', 'Pricing', 'Get Started']
    },
    'agents.avatar': {
      text: "The Digital Avatar & Executive Clone agent creates AI-powered professional avatars for executives, consultants, and trainers — complete with voice cloning and multilingual support.\n\nRating: ⭐ 4.9/5 · 520+ users\nFrom $149/mo (Starter) · $800 (Professional)",
      qr: ['View Digital Avatar', 'Get Started', 'Pricing']
    },
    'agents.knowledge': {
      text: "The Knowledge Management & SOP agent turns scattered organisational knowledge into searchable operational intelligence and auto-generated standard operating procedures.\n\nRating: ⭐ 4.8/5 · 290+ users\nFrom $199/mo (Starter) · $399/mo (Professional)",
      qr: ['View Knowledge Agent', 'Get Started', 'Pricing']
    },
    'agents.cyber': {
      text: "The Cyber Incident & Threat Intel agent is a first-line cyber operations assistant for log summarisation, alert triage, IOC extraction, and runbook/playbook generation.\n\nRating: ⭐ 4.9/5 · 150+ users\nFrom $399/mo (SME) · $699/mo (Professional)",
      qr: ['View Cyber Agent', 'Security & IRAP', 'Get Started']
    },
    'agents.content': {
      text: "The Content Creator agent handles autonomous content generation for blogs, social media, and marketing campaigns — powered by advanced language models.\n\nPrice: $29/mo",
      qr: ['Content Creator', 'All Agents', 'Contact Us']
    },
    'agents.support': {
      text: "The Customer Support Bot provides 24/7 intelligent customer support with natural language understanding — resolve issues faster and improve customer satisfaction.\n\nPrice: $49/mo",
      qr: ['Customer Support Bot', 'All Agents', 'Contact Us']
    },
    'agents.data': {
      text: "The Data Analyzer Pro agent handles advanced data analysis, visualisation, and insights generation from any dataset — uncover patterns and make data-driven decisions.\n\nPrice: $79/mo",
      qr: ['Data Analyzer', 'All Agents', 'Contact Us']
    },
    pricing: {
      text: "All agents are billed monthly in AUD with no lock-in contracts:\n\n• Content Creator — $29/mo\n• Customer Support Bot — $49/mo\n• Data Analyzer Pro — $79/mo\n• Digital Avatar — from $149/mo\n• Knowledge Management — from $199/mo\n• Bid & Tender — from $299/mo\n• Enterprise Architecture — from $349/mo\n• Cyber Incident — from $399/mo\n• Security & IRAP — from $499/mo\n\nAll plans include onboarding support.",
      qr: ['Browse Agents', 'Training', 'Contact Us']
    },
    training: {
      text: "We run hands-on AI training for individuals and teams:\n\n• Introduction to AI — Full Day Workshop, $1,500/person ⭐ Popular\n• Digital Avatar — Online, $250/avatar 🆕\n• Prompt Engineering Masterclass — Workshop, $750/person\n• AI for Business Leaders — Half Day Workshop, $995/person\n• Building AI Agents with APIs — Online 3-day, $1,200/person\n• Enterprise AI Strategy — 2-day Workshop, $2,500/person 🎓",
      qr: ['View Training', 'Contact Us']
    },
    about: {
      text: "ApiSpi is an agentic AI platform focused on deploying production-ready AI agents for government, enterprise, and professional services teams across Australia.\n\nWe combine deep AI expertise with real-world implementation experience.",
      qr: ['Our Agents', 'Training', 'Contact Us']
    },
    contact: {
      text: "You can reach the ApiSpi team via our contact page — we typically respond within one business day.\n\nFor sales enquiries, email sales@apispi.com",
      qr: ['Contact Us', 'Our Agents', 'Pricing']
    },
    getstarted: {
      text: "Getting started is easy:\n\n1. Browse the Agents catalogue\n2. Click into any agent to see full details\n3. Choose your plan and subscribe via secure checkout\n\nNeed help choosing? Happy to recommend one based on your needs.",
      qr: ['Browse Agents', 'Pricing', 'Contact Us']
    },
  };

  const FALLBACK = {
    text: "I'm not sure about that one — but our team can help!\n\nDrop us a message on the contact page or email sales@apispi.com and we'll get back to you quickly.",
    qr: ['Contact Us', 'Our Agents', 'Training']
  };

  // ── NLP TRAINING DATA ─────────────────────────────────────────────────────
  const TRAINING = [
    { intent: 'greet', phrases: [
      'hello', 'hi', 'hey', "g'day", 'howdy', 'sup', 'yo', 'greetings',
      'hey there', 'hi there', 'good morning', 'good afternoon', 'good day',
      'hiya', 'what\'s up', 'wassup',
    ]},
    { intent: 'agents.all', phrases: [
      'what agents do you have', 'show me your agents', 'what can you do',
      'what do you offer', 'list your agents', 'show me all agents',
      'what services do you have', 'browse agents', 'agent catalogue',
      'agent catalog', 'all agents', 'view agents', 'your products',
      'what AI agents are available', 'tell me about your agents',
    ]},
    { intent: 'agents.bid', phrases: [
      'bid and tender', 'tender response', 'rfq', 'rft',
      'government tender', 'procurement', 'capability statement',
      'selection criteria', 'government bid', 'tender writing',
      'bid writing', 'government contract', 'government procurement',
      'tender automation', 'bid response', 'proposal writing',
      'tender agent', 'rfq response', 'government rfq',
    ]},
    { intent: 'agents.security', phrases: [
      'security compliance', 'irap', 'essential eight', 'essential 8',
      'ism', 'pspf', 'iso 27001', 'cyber security compliance',
      'cloud security', 'security assessment', 'compliance reporting',
      'gap analysis', 'security framework', 'security agent',
      'information security', 'security readiness', 'audit',
    ]},
    { intent: 'agents.architecture', phrases: [
      'enterprise architecture', 'solution architect', 'architecture review',
      'adr', 'architecture decision record', 'technology roadmap',
      'migration roadmap', 'togaf', 'target state architecture',
      'technical roadmap', 'ea agent', 'solution design',
      'architecture agent', 'system architecture', 'it architecture',
    ]},
    { intent: 'agents.avatar', phrases: [
      'digital avatar', 'executive clone', 'voice clone', 'ai avatar',
      'ai presenter', 'personal brand video', 'heygen', 'video avatar',
      'ai video', 'voice cloning', 'executive video', 'avatar agent',
      'digital clone', 'video clone', 'ai spokesperson',
    ]},
    { intent: 'agents.knowledge', phrases: [
      'knowledge management', 'sop agent', 'standard operating procedures',
      'knowledge base', 'document management', 'sop generation',
      'confluence', 'sharepoint', 'organisational knowledge',
      'knowledge capture', 'knowledge agent', 'operational procedures',
      'process documentation', 'internal knowledge',
    ]},
    { intent: 'agents.cyber', phrases: [
      'cyber incident', 'threat intel', 'ioc', 'incident triage',
      'soc agent', 'threat intelligence', 'malware analysis',
      'cyber playbook', 'runbook generation', 'log analysis',
      'alert triage', 'cyber operations', 'security operations',
      'threat hunting', 'incident response',
    ]},
    { intent: 'agents.content', phrases: [
      'content creator', 'blog writing', 'social media content',
      'marketing content', 'content generation', 'write articles',
      'ai copywriting', 'blog posts', 'social posts', 'content marketing',
      'copywriting agent', 'article writing',
    ]},
    { intent: 'agents.support', phrases: [
      'customer support bot', 'helpdesk', 'support ticket',
      'live chat bot', 'customer service ai', 'automated support',
      'ticket handling', 'support automation', 'chatbot agent',
      'help desk', 'customer service bot',
    ]},
    { intent: 'agents.data', phrases: [
      'data analyzer', 'data analysis', 'data visualisation',
      'data visualization', 'data insights', 'analytics',
      'dataset analysis', 'data patterns', 'predictive modelling',
      'data dashboard', 'data agent', 'business intelligence',
    ]},
    { intent: 'pricing', phrases: [
      'pricing', 'how much', 'cost', 'price', 'subscription',
      'monthly fee', 'plans', 'what does it cost', 'aud',
      'how much does it cost', 'price list', 'agent pricing',
      'how much are the agents', 'fees', 'rates', 'charges',
    ]},
    { intent: 'training', phrases: [
      'training', 'course', 'workshop', 'learn ai', 'upskill',
      'ai training', 'training courses', 'what courses', 'learning',
      'certification', 'ai course', 'how to learn ai', 'training program',
      'education', 'learn about ai', 'ai workshop', 'introduction to ai',
      'prompt engineering', 'building agents', 'enterprise ai strategy',
    ]},
    { intent: 'about', phrases: [
      'about apispi', 'who are you', 'what is apispi', 'about the company',
      'who made this', 'tell me about apispi', 'company information',
      'what does apispi do', 'about you', 'your company', 'who is apispi',
    ]},
    { intent: 'contact', phrases: [
      'contact', 'email', 'phone', 'speak to someone', 'talk to a human',
      'reach you', 'how do i contact', 'get in touch', 'speak to sales',
      'contact details', 'support', 'speak to a person', 'human agent',
      'contact form', 'sales team',
    ]},
    { intent: 'getstarted', phrases: [
      'get started', 'how do i start', 'sign up', 'subscribe',
      'how to begin', 'try it', 'demo', 'trial', 'how do i buy',
      'start using', 'how to use', 'onboard', 'register',
      'begin', 'want to start', 'ready to start', 'buy an agent',
    ]},
  ];

  // ── NLP STATE ─────────────────────────────────────────────────────────────
  let manager  = null;
  let nlpReady = false;
  let nlpLoading = false;
  const CONFIDENCE_THRESHOLD = 0.5;

  function loadNLP() {
    if (nlpLoading) return;
    nlpLoading = true;
    const s = document.createElement('script');
    s.src = '/js/nlp.min.js';
    s.onload = async () => {
      const { NlpManager } = window.NLPJS;
      manager = new NlpManager({ languages: ['en'], nlu: { log: false } });
      TRAINING.forEach(({ intent, phrases }) => {
        phrases.forEach(phrase => manager.addDocument('en', phrase, intent));
      });
      await manager.train();
      nlpReady = true;
    };
    document.head.appendChild(s);
  }

  async function classify(text) {
    if (!nlpReady) {
      // wait for training to complete (up to 10s)
      await new Promise((resolve, reject) => {
        const start = Date.now();
        const check = setInterval(() => {
          if (nlpReady) { clearInterval(check); resolve(); }
          if (Date.now() - start > 10000) { clearInterval(check); reject(); }
        }, 100);
      });
    }
    const result = await manager.process('en', text);
    return result.score >= CONFIDENCE_THRESHOLD ? result.intent : null;
  }

  // ── DOM ───────────────────────────────────────────────────────────────────
  const logoSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27">
    <defs><linearGradient id="spi-g" x1=".5" y1="0" x2=".5" y2="1">
      <stop offset="0%" stop-color="#0D0B08"/><stop offset="100%" stop-color="#1C1810"/>
    </linearGradient></defs>
    <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="#0D0B08"/>
    <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="#0D0B08"/>
  </svg>`;

  const fabSVG = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27">
    <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="#0D0B08"/>
    <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="#0D0B08"/>
  </svg>`;

  const sendSVG = `<svg viewBox="0 0 20 20" fill="#0D0B08" xmlns="http://www.w3.org/2000/svg">
    <path d="M2 10l16-8-6 8 6 8-16-8z"/>
  </svg>`;

  const fab = document.createElement('button');
  fab.className = 'spi-fab';
  fab.setAttribute('aria-label', 'Open chat');
  fab.innerHTML = fabSVG;

  const win = document.createElement('div');
  win.className = 'spi-window spi-hidden';
  win.innerHTML = `
    <div class="spi-header">
      <div class="spi-avatar">${logoSVG}</div>
      <div class="spi-header-info">
        <h4>Spi</h4>
        <p>ApiSpi Assistant · Online</p>
      </div>
      <button class="spi-close" aria-label="Close chat">&#x2715;</button>
    </div>
    <div class="spi-messages" id="spi-msgs"></div>
    <div class="spi-quick-replies" id="spi-qrs"></div>
    <div class="spi-input-row">
      <input class="spi-input" id="spi-input" type="text" placeholder="Ask me anything…" autocomplete="off" maxlength="300">
      <button class="spi-send" id="spi-send" aria-label="Send">${sendSVG}</button>
    </div>
  `;

  document.body.appendChild(fab);
  document.body.appendChild(win);

  const msgs  = document.getElementById('spi-msgs');
  const qrs   = document.getElementById('spi-qrs');
  const input = document.getElementById('spi-input');
  const send  = document.getElementById('spi-send');

  // ── LOGIC ─────────────────────────────────────────────────────────────────
  let opened  = false;
  let busy    = false;

  function toggle() {
    opened = !opened;
    win.classList.toggle('spi-hidden', !opened);
    if (opened && msgs.childElementCount === 0) greet();
  }

  fab.addEventListener('click', () => {
    loadNLP(); // begin loading NLP as soon as user shows interest
    toggle();
  });
  win.querySelector('.spi-close').addEventListener('click', toggle);

  function greet() {
    addBotMsg("Hey there! I'm Spi — your ApiSpi guide.\n\nAsk me about our AI agents, training courses, or anything else.", ['Our Agents', 'Training', 'Pricing', 'Contact Us']);
  }

  function addMsg(text, cls) {
    const el = document.createElement('div');
    el.className = 'spi-msg ' + cls;
    el.textContent = text;
    msgs.appendChild(el);
    msgs.scrollTop = msgs.scrollHeight;
    return el;
  }

  function addTyping() {
    const el = document.createElement('div');
    el.className = 'spi-typing';
    el.innerHTML = '<span></span><span></span><span></span>';
    msgs.appendChild(el);
    msgs.scrollTop = msgs.scrollHeight;
    return el;
  }

  function addBotMsg(text, quickReplies) {
    addMsg(text, 'bot');
    if (quickReplies && quickReplies.length) showQR(quickReplies);
  }

  function showQR(replies) {
    qrs.innerHTML = '';
    replies.forEach(label => {
      const btn = document.createElement('button');
      btn.className = 'spi-qr';
      btn.textContent = label;
      btn.addEventListener('click', () => {
        const href = LINKS[label];
        if (href) {
          window.location.href = href;
        } else {
          handleInput(label);
        }
      });
      qrs.appendChild(btn);
    });
  }

  function setSending(val) {
    busy = val;
    input.disabled = val;
    send.disabled  = val;
  }

  async function handleInput(text) {
    const raw = text.trim();
    if (!raw || busy) return;

    setSending(true);
    addMsg(raw, 'user');
    qrs.innerHTML = '';
    input.value = '';

    const typing = addTyping();

    let intent = null;
    try {
      intent = await classify(raw);
    } catch (e) {
      // NLP failed to load/train — fall through to fallback
    }

    typing.remove();

    const resp = (intent && RESPONSES[intent]) ? RESPONSES[intent] : FALLBACK;
    addBotMsg(resp.text, resp.qr);

    setSending(false);
    input.focus();
  }

  send.addEventListener('click', () => handleInput(input.value));
  input.addEventListener('keydown', e => { if (e.key === 'Enter') handleInput(input.value); });
})();
