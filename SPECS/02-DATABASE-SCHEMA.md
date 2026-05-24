# Database Schema Specification

## Core Tables

### users
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255),
  remember_token VARCHAR(100) NULL,
  is_admin BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```
- **Model**: `App\Models\User`
- **Relationships**: 
  - `hasMany(Subscription)`
  - `hasMany(UserConnector)`

### agents
```sql
CREATE TABLE agents (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  slug VARCHAR(255) UNIQUE,
  name VARCHAR(255),
  description LONGTEXT,
  badge VARCHAR(255) NULL,
  rating DECIMAL(3, 2) DEFAULT 5.00,
  users_count INT DEFAULT 0,
  price DECIMAL(10, 2) NULL,
  category VARCHAR(255) NULL,
  is_featured BOOLEAN DEFAULT FALSE,
  is_active BOOLEAN DEFAULT TRUE,
  sort_order INT DEFAULT 0,
  
  -- Rich content (JSON cast)
  features JSON NULL,              -- Array of feature strings
  includes JSON NULL,              -- Array of inclusion strings
  use_cases JSON NULL,             -- Array of use case objects
  pricing_plans JSON NULL,         -- Array of pricing plan objects
  faqs JSON NULL,                  -- Array of FAQ objects
  
  -- CTA & checkout
  target_audience VARCHAR(255) NULL,
  tagline VARCHAR(255) NULL,
  cta_headline VARCHAR(255) NULL,
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
