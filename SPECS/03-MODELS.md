# Models Specification

## User Model

**File**: `app/Models/User.php`

### Attributes
```php
$fillable = ['name', 'email', 'password', 'is_admin'];

// Casts
'email_verified_at' => 'datetime',
'password'          => 'hashed',  // Auto-hashes via mutator
'is_admin'          => 'boolean',
```

### Relationships
```php
public function subscriptions()      // hasMany(Subscription)
public function userConnectors()     // hasMany(UserConnector)
```

### Usage
- Standard Laravel authentication model
- `is_admin` determines access to `/admin/*` routes
- Password automatically hashed on assignment

---

## Agent Model

**File**: `app/Models/Agent.php`

### Attributes
```php
$fillable = [
  'slug', 'name', 'description', 'badge', 'rating',
  'users_count', 'price', 'category', 'is_featured', 'is_active', 'sort_order',
  'features', 'includes', 'use_cases', 'pricing_plans', 'faqs',
  'target_audience', 'tagline', 'cta_headline', 'cta_sub', 'checkout_name',
];

// Casts
'rating'        => 'decimal:2',
'is_featured'   => 'boolean',
'is_active'     => 'boolean',
'features'      => 'array',        // JSON → PHP array
'includes'      => 'array',
'use_cases'     => 'array',
'pricing_plans' => 'array',
'faqs'          => 'array',
```

### Relationships
```php
public function skills()             // belongsToMany(Skill)
public function connectors()         // belongsToMany(Connector)
public function subscriptions()      // hasMany(Subscription)
```

### Scopes
```php
public function scopeActive($query)
  // WHERE is_active = TRUE ORDER BY sort_order, name
```

### Array Fields Format Examples
```php
// features: Array of strings
["Feature 1", "Feature 2", "Feature 3"]

// use_cases: Array of objects
[
  { "title": "Use Case 1", "description": "..." },
  { "title": "Use Case 2", "description": "..." }
]

// pricing_plans: Array of objects
[
  { "name": "Plan A", "price": 9.99, "description": "..." },
  { "name": "Plan B", "price": 19.99, "description": "..." }
]

// faqs: Array of objects
[
  { "question": "Q1?", "answer": "A1." },
  { "question": "Q2?", "answer": "A2." }
]
```

---

## Skill Model

**File**: `app/Models/Skill.php`

### Attributes
```php
$fillable = [
  'slug', 'name', 'description', 'category', 'is_active', 'sort_order',
];

// Casts
'is_active' => 'boolean',
```

### Relationships
```php
public function agents()             // belongsToMany(Agent)
```

### Scopes
```php
public function scopeActive($query)
  // WHERE is_active = TRUE ORDER BY sort_order, name
```

---

## Subscription Model

**File**: `app/Models/Subscription.php`

### Attributes
```php
$fillable = ['user_id', 'agent_id', 'status', 'started_at', 'expires_at'];

// Casts
'started_at' => 'datetime',
'expires_at' => 'datetime',
```

### Relationships
```php
public function user(): BelongsTo    // belongsTo(User)
public function agent(): BelongsTo   // belongsTo(Agent)
```

---

## Connector Model

**File**: `app/Models/Connector.php`

### Attributes
```php
$fillable = [
  'slug', 'name', 'description', 'category', 'icon', 'website_url',
  'is_active', 'sort_order',
  'is_oauth', 'oauth_client_id', 'oauth_client_secret',
  'oauth_auth_url', 'oauth_token_url', 'oauth_scopes', 'oauth_extra_params',
  'config_schema',
];

// Casts
'is_active'           => 'boolean',
'is_oauth'            => 'boolean',
'oauth_client_secret' => 'encrypted',  // Laravel's encryption
'oauth_extra_params'  => 'array',
'config_schema'       => 'array',      // JSON schema for dynamic forms
```

### Relationships
```php
public function agents()             // belongsToMany(Agent)
public function userConnectors()     // hasMany(UserConnector)
public function tokens()             // hasMany(ConnectorToken, 'connector_slug', 'slug')
```

### Methods
```php
public function scopeActive($query)
  // WHERE is_active = TRUE ORDER BY sort_order, name

public function tokenForUser(int $userId): ?ConnectorToken
  // Get user's OAuth token for this connector
```

### OAuth Config Example
```php
[
  'is_oauth' => true,
  'oauth_client_id' => 'slack-app-id',
  'oauth_client_secret' => 'encrypted-secret',
  'oauth_auth_url' => 'https://slack.com/oauth/v2/authorize',
  'oauth_token_url' => 'https://slack.com/api/oauth.v2.access',
  'oauth_scopes' => 'chat:write users:read',
  'oauth_extra_params' => ['redirect_uri' => 'https://apispi.com/connectors/slack/callback']
]
```

---

## ConnectorToken Model

**File**: `app/Models/ConnectorToken.php`

### Attributes
```php
$fillable = [
  'user_id', 'connector_slug', 'access_token', 'refresh_token', 'expires_at'
];

// Casts
'access_token'  => 'encrypted',
'refresh_token' => 'encrypted',
'expires_at'    => 'datetime',
```

### Relationships
```php
public function user()               // belongsTo(User)
```

---

## Training Model

**File**: `app/Models/Training.php`

### Attributes
```php
$fillable = [
  'slug', 'name', 'description', 'instructor_name', 'instructor_bio',
  'duration_hours', 'difficulty_level', 'topics', 'includes',
  'is_active', 'sort_order',
];

// Casts
'topics'  => 'array',
'includes' => 'array',
'is_active' => 'boolean',
```

---

## UserConnector Model

**File**: `app/Models/UserConnector.php`

### Attributes
```php
$fillable = [
  'user_id', 'connector_id', 'is_connected', 'connected_at'
];

// Casts
'is_connected' => 'boolean',
'connected_at' => 'datetime',
```

### Relationships
```php
public function user()               // belongsTo(User)
public function connector()          // belongsTo(Connector)
public function tokens()             // hasMany(ConnectorToken)
```

---

## ActivityLog Model

**File**: `app/Models/ActivityLog.php`

### Attributes
```php
$fillable = [
  'action', 'description', 'user_id', 'actor_id', 'metadata'
];

// Casts
'metadata' => 'array',
```

### Static Helper
```php
public static function log(
  string $action,
  string $description,
  ?int $userId = null,
  ?int $actorId = null,
  ?array $metadata = null
): void
```

### Usage Examples
```php
// User subscribes to agent
ActivityLog::log(
  'subscription.created',
  'User subscribed to Aria Agent',
  userId: $user->id
);

// Admin creates training
ActivityLog::log(
  'training.created',
  'Created training: Laravel Mastery',
  userId: $admin->id,
  actorId: $admin->id,
  metadata: ['training_id' => $training->id]
);

// Admin acts on behalf of user
ActivityLog::log(
  'subscription.assigned',
  'Admin assigned Aria Agent',
  userId: $targetUser->id,
  actorId: $admin->id
);
```
