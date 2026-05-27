# Services & Integration Specification

## OAuthService

**File**: `app/Services/OAuthService.php`

### Purpose
Centralized OAuth 2.0 token exchange, refresh, and revocation for connector integrations.

### Public Methods

```php
public static function authorize(Connector $connector, User $user): string
    // Initiates OAuth flow
    // Returns: full redirect URL to OAuth provider
    // Generates and stores state token in session for CSRF protection
    // Example: "https://slack.com/oauth/authorize?client_id=...&state=abc123..."

public static function exchangeCodeForToken(Connector $connector, string $code): array
    // Exchanges authorization code for access token
    // Parameters:
    //   - $connector: Connector instance with OAuth config
    //   - $code: authorization code from OAuth callback
    // Returns: 
    //   ['access_token' => '...', 'refresh_token' => '...', 'expires_at' => Carbon|null]
    // Uses Laravel Http::post() to call $connector->oauth_token_url
    // Throws exception on API error

public static function refreshAccessToken(ConnectorToken $token): void
    // Refreshes expired access token
    // Updates token record with new access_token and expires_at
    // Called periodically or on 401 response from connector API

public static function revokeToken(ConnectorToken $token): void
    // Revokes user's token at OAuth provider
    // Calls provider's revocation endpoint (if supported)
    // Best-effort: continues even if revocation fails
    // Called before deleting token record
```

### Integration Points

**Authorize Flow** (ConnectorOAuthController):
```php
public function authorize(string $slug)
{
    $connector = Connector::where('slug', $slug)->firstOrFail();
    $authUrl = OAuthService::authorize($connector, auth()->user());
    return redirect($authUrl);
}
```

**Token Exchange** (OAuth callback):
```php
public function callback(string $slug)
{
    $connector = Connector::where('slug', $slug)->firstOrFail();
    $code = request('code');
    $state = request('state');  // Validate against session
    
    $tokenData = OAuthService::exchangeCodeForToken($connector, $code);
    
    ConnectorToken::updateOrCreate(
        ['user_id' => auth()->id(), 'connector_slug' => $connector->slug],
        [
            'access_token' => $tokenData['access_token'],
            'refresh_token' => $tokenData['refresh_token'] ?? null,
            'expires_at' => $tokenData['expires_at'],
        ]
    );
    
    UserConnector::updateOrCreate(
        ['user_id' => auth()->id(), 'connector_id' => $connector->id],
        ['is_connected' => true, 'connected_at' => now()]
    );
    
    return redirect('/dashboard/connectors')->with('success', 'Connected!');
}
```

**Disconnect Flow**:
```php
public function disconnect(string $slug)
{
    $connector = Connector::where('slug', $slug)->firstOrFail();
    $token = ConnectorToken::where('user_id', auth()->id())
        ->where('connector_slug', $slug)
        ->first();
    
    if ($token) {
        OAuthService::revokeToken($token);  // Revoke at provider
        $token->delete();
    }
    
    UserConnector::where('user_id', auth()->id())
        ->where('connector_id', $connector->id)
        ->update(['is_connected' => false]);
    
    return redirect('/dashboard/connectors');
}
```

---

## Connector Token Encryption

### Model Configuration
**File**: `app/Models/ConnectorToken.php`

```php
protected function casts(): array
{
    return [
        'access_token'  => 'encrypted',    // Encrypted at rest
        'refresh_token' => 'encrypted',    // Encrypted at rest
        'expires_at'    => 'datetime',
    ];
}
```

### How It Works
- Tokens encrypted with `APP_KEY` when saved to database
- Automatically decrypted when accessed in code
- Encryption/decryption transparent to developer

### Security Practices
- Never log tokens in error messages or debug output
- Never expose tokens to frontend (only use server-side)
- Revoke tokens before deleting (if possible)
- Use HTTPS only (enforce via session cookie config)
- Rotate `APP_KEY` carefully (previous keys needed for old tokens)

---

## Anthropic API Integration

### Configuration
**File**: `.env`
```
ANTHROPIC_API_KEY=sk-ant-...
ANTHROPIC_MODEL=claude-sonnet-4-5
```

### ChatController & DashboardChatController

```php
public function send(Request $request)
{
    // 1. Validate request
    $request->validate(['message' => 'required|string|max:1000']);
    
    // 2. Rate limiting (30 requests per 1 minute per IP)
    // Applied via throttle:30,1 middleware
    
    // 3. Maintain conversation history in session
    $history = session()->get('chat_history', []);
    $history[] = ['role' => 'user', 'content' => $request->message];
    $history = array_slice($history, -10);  // Keep last 10 messages
    
    // 4. Call Anthropic API via Http facade (no SDK)
    $response = Http::post('https://api.anthropic.com/v1/messages', [
        'model' => config('app.anthropic_model'),
        'max_tokens' => 1024,
        'messages' => $history,
        'system' => 'You are Aria, a helpful AI assistant...',
    ]);
    
    // 5. Extract reply
    $reply = $response->json('content.0.text');
    
    // 6. Update history with assistant response
    $history[] = ['role' => 'assistant', 'content' => $reply];
    session()->put('chat_history', $history);
    
    // 7. Return JSON response
    return response()->json(['reply' => $reply]);
}
```

### Request/Response Format

**Request** (to Anthropic API):
```json
{
  "model": "claude-sonnet-4-5",
  "max_tokens": 1024,
  "messages": [
    { "role": "user", "content": "Hello!" },
    { "role": "assistant", "content": "Hi! How can I help?" },
    { "role": "user", "content": "Tell me about agents" }
  ]
}
```

**Response** (from Anthropic API):
```json
{
  "id": "msg_...",
  "type": "message",
  "role": "assistant",
  "content": [
    {
      "type": "text",
      "text": "Agents are AI-powered assistants..."
    }
  ],
  "model": "claude-sonnet-4-5",
  "usage": { "input_tokens": 100, "output_tokens": 150 }
}
```

### Features
- **Conversation History**: Last 10 messages stored per user session
- **System Prompt**: Customizable per endpoint (contact vs. dashboard)
- **Rate Limiting**: 30 requests per minute per IP (shared across `/chat` and `/dashboard/chat`)
- **Error Handling**: Returns 500 on API error, 429 on rate limit

### Static Chatbot (Not This Service)
The static `/contact` page also includes `public_html/js/chatbot.js` which:
- Classifies user intents locally with node-nlp (browser-side)
- Routes simple questions to pre-trained intents
- Falls back to `POST /chat` for complex queries
- Works offline initially (no API required for intent classification)

---

## Service Architecture Pattern

### When to Create Services
- Reusable business logic across controllers
- Complex multi-step workflows
- External API integrations
- Data transformations/formatting
- Background job coordination

### Dependency Injection Example
```php
// Service class
namespace App\Services;

class AgentService
{
    public function getActiveAgents(int $limit = 10): Collection
    {
        return Agent::active()->limit($limit)->get();
    }
    
    public function loadAgentWithRelations(Agent $agent): Agent
    {
        return $agent->load(['skills', 'connectors']);
    }
}

// Controller
class AgentController extends Controller
{
    public function __construct(private AgentService $agentService) {}
    
    public function index()
    {
        $agents = $this->agentService->getActiveAgents();
        return view('agents.index', ['agents' => $agents]);
    }
}
```

### Service Testing
```php
// tests/Unit/Services/OAuthServiceTest.php
class OAuthServiceTest extends TestCase
{
    public function test_exchange_code_for_token(): void
    {
        $connector = Connector::factory()->create(['is_oauth' => true]);
        $code = 'auth_code_123';
        
        Http::fake(['api.oauth.com/*' => Http::response([
            'access_token' => 'token_xyz',
            'expires_in' => 3600,
        ])]);
        
        $result = OAuthService::exchangeCodeForToken($connector, $code);
        
        $this->assertEquals('token_xyz', $result['access_token']);
    }
}
```
