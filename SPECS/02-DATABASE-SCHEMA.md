# Database Schema Specification

## Core Tables

### users
- **Primary Key**: `id` (bigint)
- **Unique**: `email`
- **Columns**:
  - `name` varchar(255)
  - `email` varchar(255) 
  - `email_verified_at` timestamp nullable
  - `password` varchar(255) — hashed via `Hash::make()`
  - `remember_token` varchar(100) nullable
  - `is_admin` boolean default false
  - `created_at`, `updated_at` timestamps

**Model**: `App\Models\User`  
**Relationships**:
- `hasMany(Subscription)`
- `hasMany(UserConnector)`

---

### agents
- **Primary Key**: `id` (bigint)
- **Unique**: `slug`
- **Columns**:
  - `slug` varchar(255) — URL-friendly identifier
  - `name` varchar(255)
  - `description` longtext nullable
  - `badge` varchar(255) nullable — e.g., "popular", "recommended"
  - `rating` decimal(3,2) default 5.00
  - `users_count` int default 0 — cached subscription count
  - `price` decimal(10,2) nullable
  - `category` varchar(255) nullable
  - `is_featured` boolean default false
  - `is_active` boolean default true
  - `sort_order` int default 0
  - **Rich content (JSON)**:
    - `features` json nullable — array of feature strings
    - `includes` json nullable — array of inclusion strings
    - `use_cases` json nullable — array of use case objects
    - `pricing_plans` json nullable — array of pricing plan objects
    - `faqs` json nullable — array of FAQ objects
  - **CTA & checkout**:
    - `target_audience` varchar(255) nullable
    - `tagline` varchar(255) nullable
    - `cta_headline` varchar(255) nullable
    - `cta_sub` varchar(255) nullable
    - `checkout_name` varchar(255) nullable
  - `created_at`, `updated_at` timestamps

**Model**: `App\Models\Agent`  
**Scopes**:
- `active()` — WHERE `is_active=true` ORDER BY `sort_order`, `name`

**Relationships**:
- `belongsToMany(Skill)` via `agent_skill` pivot
- `belongsToMany(Connector)` via `agent_connector` pivot
- `hasMany(Subscription)`

---

### skills
- **Primary Key**: `id` (bigint)
- **Unique**: `slug`
- **Columns**:
  - `slug` varchar(255)
  - `name` varchar(255)
  - `description` longtext nullable
  - `category` varchar(255) nullable
  - `is_active` boolean default true
  - `sort_order` int default 0
  - `created_at`, `updated_at` timestamps

**Model**: `App\Models\Skill`  
**Scopes**:
- `active()` — WHERE `is_active=true` ORDER BY `sort_order`, `name`

**Relationships**:
- `belongsToMany(Agent)` via `agent_skill` pivot

---

### agent_skill (Pivot)
- **Primary Key**: `id` (bigint)
- **Unique Constraint**: `(agent_id, skill_id)`
- **Columns**:
  - `agent_id` bigint → `agents.id` CASCADE
  - `skill_id` bigint → `skills.id` CASCADE
  - `name` varchar(255) nullable — skill name for this agent (override)
  - `description` longtext nullable — skill description (override)
  - `category` varchar(255) nullable
  - `refreshed_at` timestamp nullable — last update time
  - `sort_order` int default 0
  - `created_at` timestamp

**Model**: `App\Models\Pivot` (implicit)  
**Pivot Methods**: `withPivot(['name', 'description', 'category', 'refreshed_at'])`

---

### subscriptions
- **Primary Key**: `id` (bigint)
- **Columns**:
  - `user_id` bigint → `users.id` CASCADE
  - `agent_id` bigint → `agents.id` CASCADE
  - `status` varchar(255) default 'active' — e.g., "active", "expired", "paused"
  - `started_at` timestamp nullable
  - `expires_at` timestamp nullable
  - `created_at`, `updated_at` timestamps

**Model**: `App\Models\Subscription`  
**Relationships**:
- `belongsTo(User)`
- `belongsTo(Agent)`

---

### connectors
- **Primary Key**: `id` (bigint)
- **Unique**: `slug`
- **Columns**:
  - `slug` varchar(255)
  - `name` varchar(255)
  - `description` longtext nullable
  - `category` varchar(255) nullable
  - `icon` varchar(255) nullable — icon class or URL
  - `website_url` varchar(255) nullable
  - `is_active` boolean default true
  - `sort_order` int default 0
  - **OAuth Configuration**:
    - `is_oauth` boolean default false
    - `oauth_client_id` varchar(255) nullable
    - `oauth_client_secret` varchar(255) nullable ENCRYPTED
    - `oauth_auth_url` varchar(255) nullable — e.g., `https://oauth.provider.com/authorize`
    - `oauth_token_url` varchar(255) nullable — e.g., `https://oauth.provider.com/token`
    - `oauth_scopes` varchar(255) nullable — space-separated scopes
    - `oauth_extra_params` json nullable — additional OAuth parameters
  - **Dynamic Configuration**:
    - `config_schema` json nullable — JSON schema for dynamic config UI
  - `created_at`, `updated_at` timestamps

**Model**: `App\Models\Connector`  
**Scopes**:
- `active()` — WHERE `is_active=true` ORDER BY `sort_order`, `name`

**Relationships**:
- `belongsToMany(Agent)` via `agent_connector` pivot
- `hasMany(UserConnector)`

---

### user_connectors
- **Primary Key**: `id` (bigint)
- **Columns**:
  - `user_id` bigint → `users.id` CASCADE
  - `connector_id` bigint → `connectors.id` CASCADE
  - `is_connected` boolean default false
  - `connected_at` timestamp nullable
  - `config` json nullable — user's saved config for this connector
  - `created_at`, `updated_at` timestamps

**Model**: `App\Models\UserConnector`  
**Relationships**:
- `belongsTo(User)`
- `belongsTo(Connector)`

---

### connector_tokens
- **Primary Key**: `id` (bigint)
- **Columns**:
  - `user_id` bigint → `users.id` CASCADE
  - `connector_slug` varchar(255)
  - `access_token` longtext ENCRYPTED
  - `refresh_token` longtext ENCRYPTED nullable
  - `expires_at` timestamp nullable
  - `created_at`, `updated_at` timestamps

**Model**: `App\Models\ConnectorToken`  
**Encryption**: `access_token` and `refresh_token` auto-encrypted via `encrypted` cast  
**Relationships**:
- `belongsTo(User)`

---

### activity_logs
- **Primary Key**: `id` (bigint)
- **Columns**:
  - `user_id` bigint nullable → `users.id` CASCADE — user being acted upon
  - `actor_id` bigint nullable → `users.id` CASCADE — admin acting on behalf
  - `action` varchar(255) — e.g., "created", "updated", "deleted"
  - `description` longtext
  - `metadata` json nullable — additional context
  - `created_at` timestamp

**Model**: `App\Models\ActivityLog`  
**Static Helper**: `ActivityLog::log($action, $description, $userId, $actorId, $metadata)`  
**Usage**: Called from controllers to track user/admin actions

---

### trainings
- **Primary Key**: `id` (bigint)
- **Columns**:
  - `slug` varchar(255) unique nullable
  - `title` varchar(255)
  - `description` longtext nullable
  - `content` longtext nullable — rich content/markdown
  - **Rich metadata (JSON)**:
    - `topics` json nullable — array of topic strings
    - `includes` json nullable — array of inclusion strings
    - `use_cases` json nullable — array of use case objects
    - `faqs` json nullable — array of FAQ objects
  - `is_published` boolean default false
  - `sort_order` int default 0
  - `created_at`, `updated_at` timestamps

**Model**: `App\Models\Training`

---

### avatar_leads
- **Primary Key**: `id` (bigint)
- **Columns**:
  - `name` varchar(255) nullable
  - `email` varchar(255) nullable
  - `phone` varchar(20) nullable
  - `company` varchar(255) nullable
  - `message` longtext nullable
  - `source` varchar(255) nullable — referral source
  - `created_at` timestamp

**Model**: `App\Models\AvatarLead`  
**Purpose**: Capture leads from `/digital-avatars` form

---

## Migrations Overview
- **0001_01_01_000000**: Create users table (Laravel default)
- **0001_01_01_000001**: Create cache table (Laravel default)
- **0001_01_01_000002**: Create jobs table (Laravel default)
- **2026_05_20_100000**: Add `is_admin` to users
- **2026_05_20_200000**: Create agents table
- **2026_05_20_300000**: Create subscriptions table
- **2026_05_21_083544**: Add rich content columns to agents
- **2026_05_21_210817**: Create skills table
- **2026_05_22_030151**: Create agent_skill pivot table
- **2026_05_22_100000**: Create activity_logs table
- **2026_05_22_110000**: Create trainings table
- **2026_05_23_100000**: Create connectors table
- **2026_05_23_110000**: Create connector_tokens table
- **2026_05_23_120000**: Add OAuth fields to connectors
- **2026_05_23_130000**: Create user_connectors table
- **2026_05_24_100000**: Add config columns to connectors and user_connectors
- **2026_05_24_100000**: Add definition fields to agent_skill pivot
- **2026_05_24_110000**: Create agent_connector pivot table
- **2026_05_25_100000**: Create subscription_skill table (optional)
- **2026_05_25_110000**: Add metadata fields to connectors
- **2026_05_25_200000**: Create avatar_leads table
  cta_sub VARCHAR(255) NULL,
  checkout_name VARCHAR(255) NULL,
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```
- **Model**: `App\Models\Agent`
- **Scopes**: `active()` - filters `is_active=true`, orders by `sort_order`, `name`
- **Relationships**:
  - `belongsToMany(Skill)` via `agent_skill`
  - `belongsToMany(Connector)` via `agent_connector`
  - `hasMany(Subscription)`

### skills
```sql
CREATE TABLE skills (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  slug VARCHAR(255) UNIQUE,
  name VARCHAR(255),
  description LONGTEXT NULL,
  category VARCHAR(255) NULL,
  is_active BOOLEAN DEFAULT TRUE,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```
- **Model**: `App\Models\Skill`
- **Scopes**: `active()` - filters `is_active=true`, orders by `sort_order`, `name`
- **Relationships**: `belongsToMany(Agent)`

### agent_skill (Pivot)
```sql
CREATE TABLE agent_skill (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  agent_id BIGINT,
  skill_id BIGINT,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP,
  FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE,
  FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE,
  UNIQUE(agent_id, skill_id)
);
```

### subscriptions
```sql
CREATE TABLE subscriptions (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT,
  agent_id BIGINT,
  status VARCHAR(255) DEFAULT 'active',
  started_at TIMESTAMP NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE
);
```
- **Model**: `App\Models\Subscription`
- **Relationships**: 
  - `belongsTo(User)`
  - `belongsTo(Agent)`

### connectors
```sql
CREATE TABLE connectors (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  slug VARCHAR(255) UNIQUE,
  name VARCHAR(255),
  description LONGTEXT NULL,
  category VARCHAR(255) NULL,
  icon VARCHAR(255) NULL,
  website_url VARCHAR(255) NULL,
  is_active BOOLEAN DEFAULT TRUE,
  sort_order INT DEFAULT 0,
  
  -- OAuth fields
  is_oauth BOOLEAN DEFAULT FALSE,
  oauth_client_id VARCHAR(255) NULL,
  oauth_client_secret VARCHAR(255) NULL ENCRYPTED,
  oauth_auth_url VARCHAR(255) NULL,
  oauth_token_url VARCHAR(255) NULL,
  oauth_scopes VARCHAR(255) NULL,
  oauth_extra_params JSON NULL,
  
  -- Dynamic config schema
  config_schema JSON NULL,
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```
- **Model**: `App\Models\Connector`
- **Scopes**: `active()` - filters `is_active=true`, orders by `sort_order`, `name`
- **Relationships**:
  - `belongsToMany(Agent)` via `agent_connector`
  - `hasMany(UserConnector)`
  - `hasMany(ConnectorToken, 'connector_slug', 'slug')`

### agent_connector (Pivot)
```sql
CREATE TABLE agent_connector (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  agent_id BIGINT,
  connector_id BIGINT,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP,
  FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE,
  FOREIGN KEY (connector_id) REFERENCES connectors(id) ON DELETE CASCADE,
  UNIQUE(agent_id, connector_id)
);
```

### user_connectors
```sql
CREATE TABLE user_connectors (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT,
  connector_id BIGINT,
  is_connected BOOLEAN DEFAULT FALSE,
  connected_at TIMESTAMP NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (connector_id) REFERENCES connectors(id) ON DELETE CASCADE,
  UNIQUE(user_id, connector_id)
);
```
- **Model**: `App\Models\UserConnector`
- **Relationships**:
  - `belongsTo(User)`
  - `belongsTo(Connector)`
  - `hasMany(ConnectorToken)`

### connector_tokens
```sql
CREATE TABLE connector_tokens (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT,
  connector_slug VARCHAR(255),
  access_token LONGTEXT ENCRYPTED,
  refresh_token LONGTEXT ENCRYPTED NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (connector_slug) REFERENCES connectors(slug) ON DELETE CASCADE,
  UNIQUE(user_id, connector_slug)
);
```
- **Model**: `App\Models\ConnectorToken`
- **Relationships**: `belongsTo(User)`

### trainings
```sql
CREATE TABLE trainings (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  slug VARCHAR(255) UNIQUE,
  name VARCHAR(255),
  description LONGTEXT,
  instructor_name VARCHAR(255) NULL,
  instructor_bio LONGTEXT NULL,
  duration_hours INT NULL,
  difficulty_level VARCHAR(255) NULL,
  
  -- JSON arrays
  topics JSON NULL,
  includes JSON NULL,
  
  is_active BOOLEAN DEFAULT TRUE,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```
- **Model**: `App\Models\Training`

### activity_logs
```sql
CREATE TABLE activity_logs (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  action VARCHAR(255),
  description LONGTEXT,
  user_id BIGINT NULL,
  actor_id BIGINT NULL,
  metadata JSON NULL,
  created_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (actor_id) REFERENCES users(id) ON DELETE SET NULL
);
```
- **Model**: `App\Models\ActivityLog`
- **Static method**: `ActivityLog::log(action, description, userId?, actorId?, metadata?)`
- **Usage**: Called from controllers to track user actions

### cache & jobs (Laravel defaults)
- `cache`: Cache store for Laravel's cache system
- `jobs`: Queue jobs table

### sessions
```sql
CREATE TABLE sessions (
  id VARCHAR(255) PRIMARY KEY,
  user_id BIGINT NULL,
  ip_address VARCHAR(45) NULL,
  user_agent LONGTEXT NULL,
  payload LONGTEXT,
  last_activity INT,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX (user_id),
  INDEX (last_activity)
);
```
- **Requirement**: `SESSION_DRIVER=database` in `.env`
