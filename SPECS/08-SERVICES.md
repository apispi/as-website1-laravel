# Services & OAuth Specification

## OAuthService

**File**: `app/Services/OAuthService.php`

### Purpose
Handles OAuth2 token exchange for connectors.

### Methods

```php
public function authorize(Connector $connector, User $user): string
  // Returns OAuth authorization URL for user to click
  // Generates state token for CSRF protection
  // Returns: full redirect URL to provider's auth endpoint

public function exchangeCodeForToken(Connector $connector, string $code): array
  // Exchanges authorization code for access token
  // Params: Connector instance, authorization code from callback
  // Returns: ['access_token' => '...', 'refresh_token' => '...', 'expires_at' => null|Carbon]
  // Uses Laravel's Http::post() to call connector's oauth_token_url

public function refreshAccessToken(ConnectorToken $token): void
  // Refreshes expired access token
  // Updates ConnectorToken record with new token
  // Recalculates expires_at

public function revokeToken(ConnectorToken $token): void
  // Revokes user's token at provider
  // Calls provider's revocation endpoint (if supported)
```

### Usage Example

```php
// Controller: initiate OAuth
public function authorize(string $slug)
{
    $connector = Connector::where('slug', $slug)->firstOrFail();
    $authUrl = OAuthService::authorize($connector, auth()->user());
    return redirect($authUrl);
}

// Callback handler
public function callback(string $slug)
{
    $connector = Connector::where('slug', $slug)->firstOrFail();
    $code = request('code');
    
    $tokenData = OAuthService::exchangeCodeForToken($connector, $code);
    
    ConnectorToken::updateOrCreate(
        ['user_id' => auth()->id(), 'connector_slug' => $connector->slug],
        [
            'access_token' => $tokenData['access_token'],
            'refresh_token' => $tokenData['refresh_token'],
            'expires_at' => $tokenData['expires_at'],
        ]
    );
    
    UserConnector::updateOrCreate(
        ['user_id' => auth()->id(), 'connector_id' => $connector->id],
        ['is_connected' => true, 'connected_at' => now()]
    );
    
    return redirect('/dashboard/connectors')->with('success', 'Connected!');
}

// Disconnect
public function disconnect(string $slug)
{
    $connector = Connector::where('slug', $slug)->firstOrFail();
    $token = ConnectorToken::where('user_id', auth()->id())
        ->where('connector_slug', $slug)
        ->first();
    
    if ($token) {
        OAuthService::revokeToken($token);
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

### Implementation
- `ConnectorToken.access_token` cast to `encrypted`
- `ConnectorToken.refresh_token` cast to `encrypted`
- Automatic encryption/decryption via Laravel's `APP_KEY`

### Model Definition
```php
protected function casts(): array
{
    return [
        'access_token'  => 'encrypted',
        'refresh_token' => 'encrypted',
        'expires_at'    => 'datetime',
    ];
}
```

### Security
- Tokens encrypted at rest in database
- Decrypted only in memory when accessed
- Never log or display tokens in error messages
- Revoke tokens on logout

---

## Anthropic API Integration

### ChatController Implementation
```php
public function send(Request $request)
{
    $request->validate(['message' => 'required|string|max:1000']);
    
    // Rate limit: 30 requests per minute per IP
    // RateLimiter::attempt('chat:' . $request->ip(), 30, ...)
    
    // Maintain conversation history in session
    $history = session()->get('chat_history', []);
    $history[] = ['role' => 'user', 'content' => $request->message];
    
    // Keep only last 10 messages
    $history = array_slice($history, -10);
    
    // Call Anthropic API
    $response = Http::post('https://api.anthropic.com/v1/messages', [
        'model' => config('app.anthropic_model'),  // claude-sonnet-4-5
        'max_tokens' => 1024,
        'messages' => $history,
        'system' => 'You are Aria, a helpful AI assistant...',
    ]);
    
    $reply = $response->json('content.0.text');
    
    // Add assistant reply to history
    $history[] = ['role' => 'assistant', 'content' => $reply];
    session()->put('chat_history', $history);
    
    return response()->json(['reply' => $reply]);
}
```

### Configuration
```php
// .env
ANTHROPIC_API_KEY=sk-...
ANTHROPIC_MODEL=claude-sonnet-4-5
```

### Rate Limiting
- Applied via middleware: `throttle:30,1` (30 requests per 1 minute per IP)
- Returns 429 Too Many Requests if exceeded

### Conversation History
- Stored in session (user-scoped)
- Last 10 messages maintained
- Resets on logout

### Error Handling
- API errors return 500 with generic message
- Network timeouts handled gracefully
- Never expose API key in error output

---

## Service Provider Pattern

### When to Create Services
- Reusable business logic across multiple controllers
- Complex workflows (multi-step processes)
- External API integrations
- Model transformations

### Example Structure
```php
namespace App\Services;

class AgentService
{
    public function getActiveAgents(int $limit = 10)
    {
        return Agent::active()->limit($limit)->get();
    }
    
    public function getAgentWithRelations(Agent $agent)
    {
        return $agent->load(['skills', 'connectors', 'trainings']);
    }
}
```

### Dependency Injection
```php
public function __construct(private AgentService $agentService)
{
    //
}

public function index()
{
    $agents = $this->agentService->getActiveAgents();
    return view('agents.index', compact('agents'));
}
```
