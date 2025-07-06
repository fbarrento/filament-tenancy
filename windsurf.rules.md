# TenantForge Architecture & Coding Standards

## Project Information
- **Name:** tenant-forge
- **Description:** A multi-tenant application framework built with Laravel 12 and Filament 4
- **Frameworks:**
  - Laravel 12
  - Filament 4
  - PHP 8.4
  - Tailwind CSS

## 1. Coding Standards

- Use PHP v8.4 features
- Follow rules defined in pint.json
- Enforce strict types and array shapes via PHPStan
- Use typed properties and return types
- Always declare `strict_types=1` in new files

## 2. Project Structure & Architecture

- Delete `.gitkeep` files when adding a file to a directory
- Stick to existing structure—avoid creating new folders
- **NEVER** use `DB::` facade directly; use `Model::query()` instead
- No dependency changes without explicit approval

### Directory Structure and Patterns

#### Models
- **Directories:**
  - `app/Models`
  - `plugins/tenantforge/*/src/Models`
- **Namespaces:**
  - `App\Models`
  - `TenantForge\*\Models`
- **Rules:**
  - Avoid using `$fillable` property
  - Tenant models should use the `BelongsToTenant` trait
  - Central models should use the `CentralConnection` trait

#### Controllers
- **Directories:**
  - `app/Http/Controllers`
  - `plugins/tenantforge/*/src/Http/Controllers`
- **Namespaces:**
  - `App\Http\Controllers`
  - `TenantForge\*\Http\Controllers`
- **Rules:**
  - No abstract/base controllers
  - Keep controllers thin
  - Delegate business logic to Actions

#### Actions
- **Directories:**
  - `app/Actions`
  - `plugins/tenantforge/*/src/Actions`
- **Namespaces:**
  - `App\Actions`
  - `TenantForge\*\Actions`
- **Naming Convention:** `{Verb}{Entity}Action`
- **Usage Pattern:**
  ```php
  public function store(CreateTodoRequest $request, CreateTodoAction $action)
  {
      $user = $request->user();
      $action->handle($user, $request->validated());
  }
  ```

#### Http/Requests
- **Directories:**
  - `app/Http/Requests`
  - `plugins/tenantforge/*/src/Http/Requests`
- **Naming Convention:** `{Create|Update|Delete}{Entity}Request`
- **Rules:**
  - Use FormRequest for validation
  - Follow naming conventions

#### Filament Pages
- **Directories:**
  - `app/Filament/*/Pages`
  - `app/Filament/*/Clusters/*/Pages`
  - `plugins/tenantforge/*/src/Filament/*/Pages`
- **Namespaces:**
  - `App\Filament\*\Pages`
  - `App\Filament\*\Clusters\*\Pages`
  - `TenantForge\*\Filament\*\Pages`

#### Filament Resources
- **Directories:**
  - `app/Filament/*/Resources`
  - `app/Filament/*/Clusters/*/Resources`
  - `plugins/tenantforge/*/src/Filament/*/Resources`
- **Namespaces:**
  - `App\Filament\*\Resources`
  - `App\Filament\*\Clusters\*\Resources`
  - `TenantForge\*\Filament\*\Resources`

#### Livewire Components
- **Directories:**
  - `app/Livewire`
  - `plugins/tenantforge/*/src/Livewire`
- **Namespaces:**
  - `App\Livewire`
  - `TenantForge\*\Livewire`

#### Migrations
- **Directories:**
  - `database/migrations` (central/landlord)
  - `database/tenant` (tenant-specific)
  - `plugins/tenantforge/*/database/migrations` (plugin central)
  - `plugins/tenantforge/*/database/migrations/tenant` (plugin tenant)
- **Rules:**
  - Omit `down()` method in new migrations
  - Follow naming conventions with clear descriptive names

#### Service Providers
- **Directories:**
  - `app/Providers`
  - `plugins/tenantforge/*/src`
- **Namespaces:**
  - `App\Providers`
  - `TenantForge\*`
- **File Pattern:** `*Provider.php`

#### Blade Templates
- **Directories:**
  - `resources/views`
  - `plugins/tenantforge/*/resources/views`
- **File Pattern:** `*.blade.php`

## 3. Architecture Contexts

### Central/Landlord Context
- **Description:** Central/landlord functionality that manages tenants
- **Directories:**
  - `app/Filament/Admin`
  - `plugins/tenantforge/*/src/Filament/Admin`

### Tenant Context
- **Description:** Tenant-specific functionality for individual organizations
- **Directories:**
  - `app/Filament/Tenant`
  - `plugins/tenantforge/*/src/Filament/Tenant`

## 4. Plugin Architecture

- **Plugin Root:** `plugins/tenantforge`
- **Plugins:**
  1. **core:** Core functionality for the TenantForge framework
  2. **security:** Authentication and authorization functionality
  3. **tenancy:** Multi-tenancy implementation and management
  4. **support:** Shared utilities and helpers

## 5. Database Configuration

- **Multi-tenant:** Yes
- **Tenant Connection:** `tenant`
- **Central Connection:** `central`
- **Use UUIDs:** No
- **Tenant Models Trait:** `Stancl\Tenancy\Database\Concerns\BelongsToTenant`
- **Central Models Trait:** `Stancl\Tenancy\Database\Concerns\CentralConnection`

### Connection Management
- **Central Connection Trait:** `CentralConnection`
- **Tenant Scoping Trait:** `TenantScope`
- Always force connection context explicitly when working with models across contexts

## 6. Filament Configuration

- **Panels:**
  1. **Admin Panel:**
     - Path: `/admin`
     - Context: Central/landlord
  2. **App Panel:**
     - Path: `/app`
     - Context: Tenant

## 7. Testing

- Use **Pest PHP** for all tests
- Run `composer lint` after code changes
- Run `composer test` before finalizing
- Don't remove tests without approval
- All code must be tested
- Generate a `{Model}Factory` with each model

### Test Directory Structure
- **Console:** `tests/Feature/Console`
- **Controllers:** `tests/Feature/Http`
- **Actions:** `tests/Unit/Actions`
- **Models:** `tests/Unit/Models`
- **Jobs:** `tests/Unit/Jobs`
- **Livewire:** `tests/Feature/Livewire`
- **Filament:** `tests/Feature/Filament`

## 8. Styling & UI

- Use Tailwind CSS for styling
- Keep UI minimal and consistent
- Follow Filament design patterns for admin interfaces
- Follow project-specific design system for frontend components

## 9. Code Style & Best Practices

- **PSR Standards:** PSR-1, PSR-4, PSR-12
- Controllers should be thin and mainly handle HTTP concerns
- Always check tenant context in tenant-specific code
- Actions should follow the `{Verb}{Entity}Action` naming convention
- All database operations should explicitly declare their connection
- Central/tenant code should remain strictly separated
- Tenant-specific code should not depend on central models directly

## 10. Task Completion Requirements

- Recompile assets after frontend changes
- Follow all rules before marking tasks complete
- Ensure code passes linting and tests
- Make sure multi-tenant context is properly managed
- Document any architectural decisions or changes
