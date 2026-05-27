<template>
  <div class="aria-shell" :class="{ 'sidebar-open': sidebarOpen }">

    <div class="aria-overlay" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside class="aria-sidebar">
      <div class="aria-sidebar-header">
        <a href="/" class="aria-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="aria-logo-icon">
            <defs>
              <linearGradient id="alg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#alg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#alg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="aria-sidebar-close" @click="sidebarOpen = false" aria-label="Close menu">✕</button>
      </div>

      <nav class="aria-nav">
        <span class="aria-nav-label">Workspace</span>
        <a href="/dashboard"            class="aria-nav-link"><span class="aria-nav-icon">⬡</span> Home</a>
        <a href="/dashboard/agents"     class="aria-nav-link"><span class="aria-nav-icon">◈</span> My Agents</a>
        <a href="/dashboard/connectors" class="aria-nav-link"><span class="aria-nav-icon">⬡</span> My Connectors</a>
        <a href="/dashboard/catalog"    class="aria-nav-link"><span class="aria-nav-icon">◎</span> Catalog</a>
        <a href="/training"             class="aria-nav-link"><span class="aria-nav-icon">◷</span> Training</a>
        <a href="/dashboard/aria"       class="aria-nav-link active"><span class="aria-nav-icon">◇</span> Aria</a>

        <span class="aria-nav-label">Account</span>
        <a href="/contact"              class="aria-nav-link"><span class="aria-nav-icon">◉</span> Support</a>

        <template v-if="user.is_admin">
          <span class="aria-nav-label">Administration</span>
          <a href="/admin" class="aria-nav-link aria-nav-admin"><span class="aria-nav-icon">⬡</span> Admin Panel</a>
        </template>
      </nav>

      <div class="aria-sidebar-footer">
        <a href="/dashboard/profile" class="aria-user-row">
          <div class="aria-avatar">{{ initial }}</div>
          <div class="aria-user-text">
            <div class="aria-user-name">{{ user.name }}</div>
            <div class="aria-user-email">{{ user.email }}</div>
          </div>
        </a>
        <form method="POST" action="/logout">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="aria-signout">⏻ Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="aria-main">
      <!-- Mobile topbar -->
      <header class="aria-topbar">
        <button class="aria-menu-btn" @click="sidebarOpen = true" aria-label="Open menu">
          <span></span><span></span><span></span>
        </button>
        <div class="aria-topbar-center">
          <a href="/" class="aria-topbar-logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" style="width:20px;height:20px;">
              <defs><linearGradient id="alg2" x1=".5" y1="0" x2=".5" y2="1"><stop offset="0%" stop-color="#FCD34D"/><stop offset="100%" stop-color="#D97706"/></linearGradient></defs>
              <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#alg2)"/>
              <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#alg2)"/>
            </svg>
            <span>ApiSpi</span>
          </a>
        </div>
        <div class="aria-avatar aria-avatar-sm">{{ initial }}</div>
      </header>

      <!-- Chat -->
      <div class="aria-chat">
        <!-- Chat header -->
        <div class="chat-header">
          <div class="chat-avatar">A</div>
          <div class="chat-header-text">
            <div class="chat-name">Aria — ApiSpi AI</div>
            <div class="chat-status"><span class="chat-dot"></span> Online</div>
          </div>
          <div class="chat-badge">AI Assistant</div>
        </div>

        <!-- Messages -->
        <div class="chat-messages" ref="messagesEl">
          <div
            v-for="(msg, i) in messages"
            :key="i"
            :class="['chat-msg', msg.role]"
          >
            <div class="msg-avatar">{{ msg.role === 'bot' ? 'A' : 'You' }}</div>
            <div class="msg-bubble" v-if="msg.role === 'bot'" v-html="formatMarkdown(msg.text)"></div>
            <div class="msg-bubble" v-else>{{ msg.text }}</div>
          </div>

          <div v-if="sending" class="chat-msg bot">
            <div class="msg-avatar">A</div>
            <div class="msg-bubble typing">
              <span></span><span></span><span></span>
            </div>
          </div>
        </div>

        <!-- Suggestions -->
        <div v-if="showSuggestions" class="chat-suggestions">
          <button
            v-for="s in suggestions"
            :key="s"
            class="chat-suggestion"
            @click="send(s)"
          >{{ s }}</button>
        </div>

        <!-- Input -->
        <div class="chat-input-row">
          <textarea
            ref="inputEl"
            v-model="inputText"
            class="chat-input"
            placeholder="Ask Aria anything…"
            rows="1"
            @keydown.enter.exact.prevent="send(inputText)"
            @input="autoResize"
          ></textarea>
          <button class="chat-send" :disabled="sending || !inputText.trim()" @click="send(inputText)">
            <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue';

const props = defineProps({
  user:      { type: Object, default: () => ({}) },
  csrfToken: { type: String, default: '' },
});

const sidebarOpen = ref(false);
const initial = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());

const messagesEl = ref(null);
const inputEl    = ref(null);
const inputText  = ref('');
const sending    = ref(false);
const history    = ref([]);
const showSuggestions = ref(true);

const messages = ref([{
  role: 'bot',
  text: "Hi, I'm **Aria** — your ApiSpi AI assistant.\n\nI can help you with your agents, connectors, billing, training, and anything else about the platform. What would you like to know?",
}]);

const suggestions = [
  'How do my agents work?',
  "What's included in my plan?",
  'How do connectors work?',
  'Can I upgrade my subscription?',
];

async function send(text) {
  text = (text || '').trim();
  if (!text || sending.value) return;

  showSuggestions.value = false;
  messages.value.push({ role: 'user', text });
  inputText.value = '';
  if (inputEl.value) inputEl.value.style.height = 'auto';
  sending.value = true;

  await nextTick();
  scrollToBottom();

  try {
    const res = await fetch('/chat', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': props.csrfToken },
      body: JSON.stringify({ message: text, history: history.value }),
    });
    const data = await res.json();
    const reply = data.reply || data.error || 'Sorry, something went wrong. Please try again.';
    history.value.push({ role: 'user', content: text });
    history.value.push({ role: 'assistant', content: reply });
    if (history.value.length > 20) history.value = history.value.slice(-20);
    messages.value.push({ role: 'bot', text: reply });
  } catch {
    messages.value.push({ role: 'bot', text: 'Sorry, I ran into an issue. Please try again.' });
  } finally {
    sending.value = false;
    await nextTick();
    scrollToBottom();
  }
}

function scrollToBottom() {
  if (messagesEl.value) messagesEl.value.scrollTop = messagesEl.value.scrollHeight;
}

function autoResize(e) {
  const el = e.target;
  el.style.height = 'auto';
  el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

function formatMarkdown(text) {
  return text
    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
    .replace(/\*(.+?)\*/g, '<em>$1</em>')
    .replace(/^- (.+)$/gm, '<li>$1</li>')
    .replace(/(<li>.*<\/li>\n?)+/s, m => `<ul>${m}</ul>`)
    .replace(/\n/g, '<br>');
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.aria-shell {
  display: flex;
  height: 100vh;
  overflow: hidden;
  background: #0a0805;
  color: #e5e7eb;
  font-family: 'Instrument Sans', system-ui, sans-serif;
}

.aria-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,0.65); z-index: 90;
}
.sidebar-open .aria-overlay { display: block; }

/* Sidebar */
.aria-sidebar {
  width: 240px; background: rgba(12,8,4,0.99);
  border-right: 1px solid rgba(217,119,6,0.12);
  position: fixed; top: 0; left: 0; height: 100vh;
  display: flex; flex-direction: column;
  z-index: 100; overflow-y: auto;
  transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
  flex-shrink: 0;
}
.aria-sidebar-header {
  display: flex; align-items: center; gap: 0.5rem;
  padding: 1.25rem 1rem 1rem;
  border-bottom: 1px solid rgba(217,119,6,0.1);
  flex-shrink: 0;
}
.aria-logo {
  display: flex; align-items: center; gap: 0.5rem;
  text-decoration: none; color: #e5e7eb;
  font-size: 1.1rem; font-weight: 700; flex: 1;
}
.aria-logo-icon { width: 24px; height: 24px; }
.aria-sidebar-close {
  display: none; background: none; border: none; cursor: pointer;
  color: #6b7280; font-size: 1rem; padding: 0.25rem; flex-shrink: 0;
}

.aria-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.aria-nav-label {
  font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: 0.1em; color: #4b5563;
  padding: 0.75rem 0.5rem 0.25rem;
}
.aria-nav-link {
  display: flex; align-items: center; gap: 0.75rem;
  padding: 0.625rem 0.75rem; border-radius: 0.5rem;
  text-decoration: none; color: #9ca3af; font-size: 0.875rem;
  transition: all 0.18s; min-height: 44px;
}
.aria-nav-link:hover { background: rgba(217,119,6,0.08); color: #FCD34D; }
.aria-nav-link.active { background: rgba(217,119,6,0.12); color: #FCD34D; font-weight: 600; }
.aria-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
.aria-nav-admin { color: #6b7280 !important; font-size: 0.82rem; }
.aria-nav-admin:hover { color: #FCD34D !important; }

.aria-sidebar-footer {
  padding: 1rem 0.75rem;
  border-top: 1px solid rgba(217,119,6,0.1);
  flex-shrink: 0;
}
.aria-user-row {
  display: flex; align-items: center; gap: 0.75rem;
  margin-bottom: 0.75rem; text-decoration: none;
  border-radius: 0.5rem; padding: 0.25rem 0.25rem;
  transition: background 0.18s;
}
.aria-user-row:hover { background: rgba(217,119,6,0.06); }
.aria-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #D97706, #FCD34D);
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 0.85rem; color: #0a0805; flex-shrink: 0;
}
.aria-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.aria-user-text { min-width: 0; }
.aria-user-name  { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.aria-user-email { font-size: 0.72rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.aria-signout {
  display: flex; align-items: center; gap: 0.5rem;
  width: 100%; padding: 0.5rem 0.75rem;
  background: none; border: none; cursor: pointer;
  color: #6b7280; font-size: 0.85rem; border-radius: 0.4rem;
  transition: all 0.18s; font-family: inherit; text-align: left; min-height: 44px;
}
.aria-signout:hover { background: rgba(217,119,6,0.08); color: #FCD34D; }

/* Main */
.aria-main {
  margin-left: 240px; flex: 1;
  display: flex; flex-direction: column;
  height: 100vh; overflow: hidden;
  min-width: 0;
}

/* Mobile topbar */
.aria-topbar {
  display: none; flex-shrink: 0;
  height: 56px; padding: 0 1rem;
  background: rgba(12,8,4,0.98);
  border-bottom: 1px solid rgba(217,119,6,0.1);
  align-items: center; justify-content: space-between;
}
.aria-topbar-center { display: flex; align-items: center; gap: 0.5rem; }
.aria-topbar-logo {
  display: flex; align-items: center; gap: 0.4rem;
  text-decoration: none; color: #e5e7eb;
  font-size: 1rem; font-weight: 700;
}
.aria-menu-btn {
  display: flex; flex-direction: column; justify-content: space-between;
  width: 24px; height: 18px; background: none; border: none;
  cursor: pointer; padding: 0;
}
.aria-menu-btn span { display: block; width: 100%; height: 2px; background: #FCD34D; border-radius: 2px; }

/* Chat container */
.aria-chat {
  flex: 1; display: flex; flex-direction: column; overflow: hidden;
}

.chat-header {
  flex-shrink: 0;
  display: flex; align-items: center; gap: 0.875rem;
  padding: 1rem 1.5rem;
  background: rgba(217,119,6,0.05);
  border-bottom: 1px solid rgba(217,119,6,0.12);
}
.chat-avatar {
  width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, #D97706, #FCD34D);
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 1.1rem; color: #0a0805;
}
.chat-header-text { flex: 1; }
.chat-name   { font-size: 1rem; font-weight: 700; color: #f1f5f9; }
.chat-status { font-size: 0.75rem; color: #00d97e; display: flex; align-items: center; gap: 0.35rem; margin-top: 0.1rem; }
.chat-dot    { width: 7px; height: 7px; border-radius: 50%; background: #00d97e; flex-shrink: 0; }
.chat-badge  {
  font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;
  color: #FCD34D; background: rgba(217,119,6,0.1);
  border: 1px solid rgba(217,119,6,0.25); padding: 0.2rem 0.6rem; border-radius: 99px;
}

.chat-messages {
  flex: 1; overflow-y: auto; padding: 1.5rem;
  display: flex; flex-direction: column; gap: 1rem;
  scrollbar-width: thin; scrollbar-color: rgba(217,119,6,0.2) transparent;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-thumb { background: rgba(217,119,6,0.2); border-radius: 2px; }

.chat-msg { display: flex; gap: 0.75rem; max-width: 820px; }
.chat-msg.user { align-self: flex-end; flex-direction: row-reverse; }
.chat-msg.bot  { align-self: flex-start; }

.msg-avatar {
  width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0; margin-top: 2px;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.7rem; font-weight: 700;
}
.chat-msg.bot  .msg-avatar { background: linear-gradient(135deg, #D97706, #FCD34D); color: #0a0805; }
.chat-msg.user .msg-avatar { background: rgba(217,119,6,0.18); color: #FCD34D; font-size: 0.65rem; }

.msg-bubble {
  padding: 0.75rem 1rem; border-radius: 1.1rem;
  font-size: 0.9rem; line-height: 1.6; word-break: break-word;
}
.chat-msg.bot  .msg-bubble { background: rgba(217,119,6,0.07); border: 1px solid rgba(217,119,6,0.14); color: #e5e7eb; border-bottom-left-radius: 4px; }
.chat-msg.user .msg-bubble { background: rgba(217,119,6,0.18); border: 1px solid rgba(217,119,6,0.3); color: #fef3c7; border-bottom-right-radius: 4px; }
.chat-msg.bot .msg-bubble :deep(strong) { color: #FCD34D; }
.chat-msg.bot .msg-bubble :deep(ul) { padding-left: 1.2rem; margin: 0.4rem 0; }
.chat-msg.bot .msg-bubble :deep(li) { margin-bottom: 0.2rem; }

/* Typing dots */
.msg-bubble.typing {
  display: flex; align-items: center; gap: 5px;
  padding: 0.75rem 1rem; min-width: 60px;
}
.msg-bubble.typing span {
  width: 7px; height: 7px; border-radius: 50%; background: #D97706;
  opacity: 0.5; animation: blink 1.2s infinite;
}
.msg-bubble.typing span:nth-child(2) { animation-delay: 0.2s; }
.msg-bubble.typing span:nth-child(3) { animation-delay: 0.4s; }
@keyframes blink { 0%,100%{opacity:0.4;transform:scale(1)} 50%{opacity:1;transform:scale(1.3)} }

/* Suggestions */
.chat-suggestions {
  flex-shrink: 0; padding: 0 1.5rem 0.875rem;
  display: flex; flex-wrap: wrap; gap: 0.5rem;
}
.chat-suggestion {
  padding: 0.4rem 0.875rem; border-radius: 99px; cursor: pointer;
  border: 1px solid rgba(217,119,6,0.25); background: rgba(217,119,6,0.06);
  color: #FCD34D; font-size: 0.78rem; font-family: inherit;
  transition: all 0.18s; white-space: nowrap;
}
.chat-suggestion:hover { background: rgba(217,119,6,0.15); border-color: rgba(217,119,6,0.5); }

/* Input */
.chat-input-row {
  flex-shrink: 0;
  padding: 0.875rem 1.25rem;
  border-top: 1px solid rgba(217,119,6,0.1);
  display: flex; gap: 0.625rem; align-items: flex-end;
  background: rgba(10,8,5,0.5);
}
.chat-input {
  flex: 1; padding: 0.7rem 1rem; resize: none; max-height: 120px;
  background: rgba(20,12,6,0.9); border: 1px solid rgba(217,119,6,0.2);
  border-radius: 0.875rem; color: #e5e7eb; font-size: 0.9rem; font-family: inherit;
  transition: border-color 0.18s; line-height: 1.45; overflow-y: auto;
  scrollbar-width: thin;
}
.chat-input:focus { outline: none; border-color: rgba(217,119,6,0.5); }
.chat-input::placeholder { color: #4b5563; }
.chat-send {
  width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, #D97706, #FCD34D);
  border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: transform 0.15s, opacity 0.15s;
}
.chat-send:hover { transform: scale(1.08); }
.chat-send:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }
.chat-send svg { width: 17px; height: 17px; fill: #0a0805; }

/* Responsive */
@media (max-width: 768px) {
  .aria-sidebar { transform: translateX(-100%); }
  .sidebar-open .aria-sidebar { transform: translateX(0); }
  .sidebar-open .aria-overlay { display: block; }
  .aria-sidebar-close { display: block; }
  .aria-main { margin-left: 0; }
  .aria-topbar { display: flex; }
  .chat-header { padding: 0.875rem 1rem; }
  .chat-messages { padding: 1rem; }
  .chat-input-row { padding: 0.75rem 1rem; }
  .chat-suggestions { padding: 0 1rem 0.75rem; }
}
</style>
