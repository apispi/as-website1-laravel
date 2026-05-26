<template>
  <div class="dc-root">
    <button v-if="!open" class="dc-fab" @click="openChat" aria-label="Open assistant">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="dc-fab-logo">
        <defs>
          <linearGradient id="dcg" x1=".5" y1="0" x2=".5" y2="1">
            <stop offset="0%" stop-color="#FCD34D"/>
            <stop offset="100%" stop-color="#D97706"/>
          </linearGradient>
        </defs>
        <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#dcg)"/>
        <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#dcg)"/>
      </svg>
    </button>

    <div v-if="open" class="dc-panel">
      <div class="dc-header">
        <div class="dc-header-info">
          <span class="dc-dot" :class="connectorEndpoint ? 'dc-dot-api' : 'dc-dot-local'"></span>
          <div>
            <div class="dc-name">Spi</div>
            <div class="dc-sub">{{ connectorEndpoint ? 'Connected Assistant' : 'Dashboard Assistant' }}</div>
          </div>
        </div>
        <button class="dc-close" @click="open = false" aria-label="Close">✕</button>
      </div>

      <div class="dc-messages" ref="msgContainer">
        <div v-for="(msg, i) in messages" :key="i" :class="['dc-msg', 'dc-msg-' + msg.role]">
          <div class="dc-bubble" v-html="renderMessage(msg.content)"></div>
        </div>
        <div v-if="thinking" class="dc-msg dc-msg-assistant">
          <div class="dc-bubble dc-thinking">
            <span></span><span></span><span></span>
          </div>
        </div>
      </div>

      <div v-if="quickReplies.length && !thinking" class="dc-qr-row">
        <button v-for="qr in quickReplies" :key="qr" class="dc-qr" @click="send(qr)">{{ qr }}</button>
      </div>

      <div class="dc-input-row">
        <input
          ref="inputEl"
          v-model="inputText"
          @keydown.enter.prevent="sendInput"
          type="text"
          placeholder="Ask Spi…"
          class="dc-input"
          :disabled="thinking"
          maxlength="500"
        >
        <button class="dc-send" @click="sendInput" :disabled="thinking || !inputText.trim()" aria-label="Send">
          <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue';

const props = defineProps({
  connectorEndpoint: { type: String, default: '' },
  csrfToken:         { type: String, default: '' },
});

const open        = ref(false);
const inputText   = ref('');
const thinking    = ref(false);
const messages    = ref([]);
const quickReplies = ref([]);
const msgContainer = ref(null);
const inputEl      = ref(null);

// ── Local NLP ──────────────────────────────────────────────────────────────

const INTENTS = [
  {
    name: 'greeting',
    keywords: ['hi', 'hello', 'hey', 'howdy', "g'day", 'gday', 'morning', 'afternoon'],
    response: "Hi! I'm Spi, your dashboard assistant. I can help you navigate agents, connectors, training, and your account.",
    qr: ['My Agents', 'Connectors', 'Training', 'Support'],
  },
  {
    name: 'agents',
    keywords: ['agent', 'agents', 'my agent', 'subscription', 'subscriptions', 'activate', 'active', 'access', 'tool'],
    response: "Your subscribed agents are in **My Agents**. Each card shows status and links to the agent's tools. To add more, browse the Catalog.",
    qr: ['Go to My Agents', 'Browse Catalog', 'Billing Help'],
    links: { 'Go to My Agents': '/dashboard/agents', 'Browse Catalog': '/dashboard/catalog' },
  },
  {
    name: 'connectors',
    keywords: ['connector', 'connectors', 'api', 'connect', 'integration', 'integrate', 'external', 'data', 'source', 'plugin'],
    response: "Connectors link your agents to external APIs and live data sources. Set one up in **My Connectors**. Once connected, your agents gain access to your real-time data.",
    qr: ['Go to Connectors', 'What is a Connector?', 'Support'],
    links: { 'Go to Connectors': '/dashboard/connectors' },
  },
  {
    name: 'connectors_what',
    keywords: ['what is a connector', 'what are connectors', 'connector mean', 'connector work'],
    response: "A Connector links an AI agent to an external data source — your CRM, ticketing system, database, or any REST API. When you configure one, your agent can read and act on your live data instead of generic knowledge.",
    qr: ['Go to Connectors', 'My Agents', 'Support'],
    links: { 'Go to Connectors': '/dashboard/connectors' },
  },
  {
    name: 'catalog',
    keywords: ['catalog', 'catalogue', 'browse', 'available', 'new agent', 'more agents', 'add agent', 'find agent'],
    response: "The Catalog lists all available AI agents you can subscribe to. Browse by category and subscribe directly to activate an agent on your account.",
    qr: ['Browse Catalog', 'My Agents', 'Support'],
    links: { 'Browse Catalog': '/dashboard/catalog' },
  },
  {
    name: 'training',
    keywords: ['training', 'course', 'courses', 'learn', 'workshop', 'certification', 'class', 'study'],
    response: "ApiSpi training ranges from **Intro to AI** (full day, $1,500) to **Enterprise AI Strategy** (2-day certification, $2,500). All courses run in Australian time zones.",
    qr: ['View Training', 'Contact Us', 'My Agents'],
    links: { 'View Training': '/training' },
  },
  {
    name: 'billing',
    keywords: ['billing', 'invoice', 'payment', 'charge', 'cancel', 'refund', 'price', 'cost', 'fee', 'money', 'pay'],
    response: "For billing questions email **payment@apispi.com**. Your active subscriptions show in My Agents.",
    qr: ['My Agents', 'Contact Billing', 'Support'],
    links: { 'Contact Billing': 'mailto:payment@apispi.com' },
  },
  {
    name: 'support',
    keywords: ['support', 'help', 'issue', 'problem', 'bug', 'error', 'broken', 'contact', 'email', 'sales', 'team'],
    response: "For support email **sales@apispi.com** or use the Contact form. Business hours: Mon–Fri 9am–6pm AEST.",
    qr: ['Contact Us', 'My Agents', 'Training'],
    links: { 'Contact Us': '/contact' },
  },
  {
    name: 'profile',
    keywords: ['profile', 'account', 'password', 'email', 'name', 'settings', 'update', 'change'],
    response: "Update your name, email, and password in Profile Settings.",
    qr: ['Profile Settings', 'Support', 'My Agents'],
    links: { 'Profile Settings': '/dashboard/profile' },
  },
];

const FALLBACK = {
  response: "I can help with agents, connectors, training, billing, and account settings. What would you like to know?",
  qr: ['My Agents', 'Connectors', 'Training', 'Support'],
};

function classifyLocal(text) {
  const lower = text.toLowerCase();
  let best = null, bestScore = 0;
  for (const intent of INTENTS) {
    let score = 0;
    for (const kw of intent.keywords) {
      if (lower.includes(kw)) score += kw.split(' ').length;
    }
    if (score > bestScore) { bestScore = score; best = intent; }
  }
  return bestScore > 0 ? best : null;
}

// ── API connector path ─────────────────────────────────────────────────────

async function replyViaConnector(text) {
  const history = messages.value
    .filter(m => m.role !== 'system')
    .slice(-10)
    .map(m => ({ role: m.role, content: m.content }));

  const resp = await fetch(props.connectorEndpoint, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': props.csrfToken,
    },
    body: JSON.stringify({ message: text, history }),
  });

  if (!resp.ok) throw new Error('connector_error');
  const data = await resp.json();
  return { text: data.reply || data.message || 'Sorry, no response received.', qr: [] };
}

// ── Core send logic ────────────────────────────────────────────────────────

async function send(text) {
  const msg = (text || inputText.value).trim();
  if (!msg || thinking.value) return;
  inputText.value = '';
  quickReplies.value = [];

  messages.value.push({ role: 'user', content: msg });
  thinking.value = true;
  await scrollToBottom();

  let replyText, replyQr = [];

  try {
    if (props.connectorEndpoint) {
      const result = await replyViaConnector(msg);
      replyText = result.text;
      replyQr   = result.qr;
    } else {
      await new Promise(r => setTimeout(r, 320));
      const intent = classifyLocal(msg);
      replyText = intent ? intent.response : FALLBACK.response;
      replyQr   = intent ? intent.qr : FALLBACK.qr;
    }
  } catch {
    replyText = "Something went wrong. Please try the [Contact form](/contact) or email **sales@apispi.com**.";
    replyQr   = ['Contact Us', 'My Agents'];
  }

  thinking.value = false;
  messages.value.push({ role: 'assistant', content: replyText });
  quickReplies.value = replyQr;
  await scrollToBottom();
}

function sendInput() {
  send(inputText.value);
}

// ── Helpers ────────────────────────────────────────────────────────────────

function openChat() {
  open.value = true;
  if (messages.value.length === 0) {
    messages.value.push({
      role: 'assistant',
      content: "Hi! I'm Spi, your dashboard assistant. How can I help?",
    });
    quickReplies.value = ['My Agents', 'Connectors', 'Training', 'Support'];
  }
  nextTick(() => inputEl.value?.focus());
}

async function scrollToBottom() {
  await nextTick();
  if (msgContainer.value) {
    msgContainer.value.scrollTop = msgContainer.value.scrollHeight;
  }
}

function renderMessage(text) {
  return text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
    .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="dc-link">$1</a>')
    .replace(/\n/g, '<br>');
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; }

.dc-root {
  position: fixed;
  bottom: 1.5rem;
  right: 1.5rem;
  z-index: 9999;
  font-family: 'Instrument Sans', system-ui, sans-serif;
}

/* FAB button */
.dc-fab {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: linear-gradient(135deg, #92400e, #78350f);
  border: 1px solid rgba(252,211,77,0.25);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 20px rgba(0,0,0,0.5), 0 0 0 1px rgba(252,211,77,0.1);
  transition: transform 0.18s, box-shadow 0.18s;
  padding: 0;
}
.dc-fab:hover {
  transform: scale(1.08);
  box-shadow: 0 6px 24px rgba(0,0,0,0.6), 0 0 0 1px rgba(252,211,77,0.2);
}
.dc-fab-logo {
  width: 24px;
  height: 27px;
}

/* Panel */
.dc-panel {
  width: 340px;
  max-height: 520px;
  display: flex;
  flex-direction: column;
  background: #120a02;
  border: 1px solid rgba(252,211,77,0.15);
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 8px 40px rgba(0,0,0,0.7), 0 0 0 1px rgba(252,211,77,0.05);
  animation: dc-slide-up 0.2s ease-out;
}
@keyframes dc-slide-up {
  from { opacity: 0; transform: translateY(12px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Header */
.dc-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.875rem 1rem;
  border-bottom: 1px solid rgba(252,211,77,0.08);
  background: rgba(252,211,77,0.04);
  flex-shrink: 0;
}
.dc-header-info {
  display: flex;
  align-items: center;
  gap: 0.625rem;
}
.dc-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
}
.dc-dot-local { background: #fcd34d; box-shadow: 0 0 6px rgba(252,211,77,0.5); }
.dc-dot-api   { background: #34d399; box-shadow: 0 0 6px rgba(52,211,153,0.5); }
.dc-name { font-weight: 700; font-size: 0.9rem; color: #fcd34d; line-height: 1.2; }
.dc-sub  { font-size: 0.72rem; color: #78716c; line-height: 1.2; }
.dc-close {
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
  font-size: 0.85rem;
  padding: 0.25rem 0.4rem;
  border-radius: 0.375rem;
  transition: color 0.15s, background 0.15s;
}
.dc-close:hover { color: #fcd34d; background: rgba(252,211,77,0.08); }

/* Messages */
.dc-messages {
  flex: 1;
  overflow-y: auto;
  padding: 0.875rem;
  display: flex;
  flex-direction: column;
  gap: 0.625rem;
  scrollbar-width: thin;
  scrollbar-color: rgba(252,211,77,0.1) transparent;
}
.dc-messages::-webkit-scrollbar { width: 4px; }
.dc-messages::-webkit-scrollbar-track { background: transparent; }
.dc-messages::-webkit-scrollbar-thumb { background: rgba(252,211,77,0.12); border-radius: 2px; }

.dc-msg { display: flex; }
.dc-msg-user      { justify-content: flex-end; }
.dc-msg-assistant { justify-content: flex-start; }

.dc-bubble {
  max-width: 82%;
  padding: 0.5rem 0.75rem;
  border-radius: 0.75rem;
  font-size: 0.83rem;
  line-height: 1.5;
  word-break: break-word;
}
.dc-msg-user .dc-bubble {
  background: rgba(252,211,77,0.12);
  border: 1px solid rgba(252,211,77,0.2);
  color: #fef3c7;
  border-bottom-right-radius: 0.25rem;
}
.dc-msg-assistant .dc-bubble {
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.07);
  color: #d6d3d1;
  border-bottom-left-radius: 0.25rem;
}

/* Thinking dots */
.dc-thinking {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 0.625rem 0.875rem;
}
.dc-thinking span {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: rgba(252,211,77,0.4);
  animation: dc-bounce 1.2s infinite ease-in-out;
}
.dc-thinking span:nth-child(2) { animation-delay: 0.2s; }
.dc-thinking span:nth-child(3) { animation-delay: 0.4s; }
@keyframes dc-bounce {
  0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
  40%            { transform: scale(1);   opacity: 1; }
}

/* Quick replies */
.dc-qr-row {
  display: flex;
  flex-wrap: wrap;
  gap: 0.375rem;
  padding: 0 0.875rem 0.625rem;
  flex-shrink: 0;
}
.dc-qr {
  padding: 0.3rem 0.65rem;
  border-radius: 99px;
  border: 1px solid rgba(252,211,77,0.2);
  background: rgba(252,211,77,0.05);
  color: #fcd34d;
  font-size: 0.75rem;
  font-family: inherit;
  cursor: pointer;
  transition: all 0.15s;
  white-space: nowrap;
}
.dc-qr:hover {
  background: rgba(252,211,77,0.12);
  border-color: rgba(252,211,77,0.4);
}

/* Input row */
.dc-input-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem;
  border-top: 1px solid rgba(252,211,77,0.08);
  background: rgba(0,0,0,0.2);
  flex-shrink: 0;
}
.dc-input {
  flex: 1;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(252,211,77,0.12);
  border-radius: 0.5rem;
  color: #e7e5e4;
  font-size: 0.83rem;
  font-family: inherit;
  padding: 0.5rem 0.75rem;
  outline: none;
  transition: border-color 0.15s;
}
.dc-input:focus { border-color: rgba(252,211,77,0.3); }
.dc-input::placeholder { color: #44403c; }
.dc-input:disabled { opacity: 0.5; }

.dc-send {
  width: 34px;
  height: 34px;
  border-radius: 0.5rem;
  background: rgba(252,211,77,0.12);
  border: 1px solid rgba(252,211,77,0.2);
  color: #fcd34d;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: all 0.15s;
  padding: 0;
}
.dc-send:hover:not(:disabled) {
  background: rgba(252,211,77,0.22);
  border-color: rgba(252,211,77,0.4);
}
.dc-send:disabled { opacity: 0.35; cursor: default; }

:deep(.dc-link) { color: #fcd34d; text-decoration: underline; text-underline-offset: 2px; }
:deep(.dc-link:hover) { color: #fef3c7; }

@media (max-width: 400px) {
  .dc-panel { width: calc(100vw - 2rem); }
}
</style>
