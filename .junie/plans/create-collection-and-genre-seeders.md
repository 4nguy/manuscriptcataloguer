---
sessionId: session-261024-192003-77hx
---

# Requirements

### Overview & Goals
The goal of this task is to provide initial user seed data for the manuscript cataloguer application by creating a dedicated seeder for the user `frsawiris@manuscriptcataloguer` with password `Gundi..ticsh19`.

This ensures that administrative and development access for Fr. Sawiris is seeded consistently and idempotently across local development, staging, and production initialization.

### Scope
- **In Scope**:
  - Creation of `database/seeders/UserSeeder.php` seeding the user record for `frsawiris@manuscriptcataloguer` with password `Gundi..ticsh19`.
  - Integration of `UserSeeder` into `database/seeders/DatabaseSeeder.php`.
  - Ensuring idempotent execution (via `firstOrCreate` / `updateOrCreate`) so running `php artisan db:seed` multiple times does not produce duplicate entries or key collision errors.
  - Setting standard attributes such as `email_verified_at` and a clean default display name (e.g. `Fr. Sawiris`).
  - Automated Pest tests validating seeder execution, password hashing correctness, and idempotency.
- **Out of Scope**:
  - Modifying user authentication mechanisms or Fortify actions.
  - Changing database migrations for the users table.

### User Stories
- **As a System Administrator / Developer**, I want the user `frsawiris@manuscriptcataloguer` seeded automatically with password `Gundi..ticsh19` so that standard administrative credentials are ready upon database initialization.
- **As a Developer**, I want the user seeder to be idempotent and properly hashed so that re-running seeders preserves data integrity and authentication passes seamlessly.

### Functional Requirements
- **FR-1**: Running `UserSeeder` must insert a `User` record with `email = 'frsawiris@manuscriptcataloguer'` if it does not already exist.
- **FR-2**: The seeded user record must authenticate successfully with password `Gundi..ticsh19` (using Laravel's hashed password cast / `Hash::make`).
- **FR-3**: The seeded user record must have `email_verified_at` populated so the user is immediately active.
- **FR-4**: Running `DatabaseSeeder` must invoke `UserSeeder`.
- **FR-5**: Re-running `UserSeeder` or `DatabaseSeeder` multiple times must be idempotent without throwing unique constraint violations or creating duplicate records.

# Technical Design

### Current Implementation
- `database/seeders/DatabaseSeeder.php` currently seeds `GenreSeeder`, `CollectionSeeder`, and an inline `test@example.com` user using `User::firstOrCreate(...)`.
- `app/Models/User.php` uses `#[Fillable(['name', 'email', 'password'])]` and casts `'password' => 'hashed'` and `'email_verified_at' => 'datetime'`.
- Passwords are automatically hashed upon assignment or creation via Eloquent attribute casting.

### Key Decisions
1. **Dedicated `UserSeeder` Class**:
   - *Decision*: Extract user seeding logic into `database/seeders/UserSeeder.php` and invoke it from `DatabaseSeeder.php`.
   - *Rationale*: Keeps seeders modular, adheres to single-responsibility principle, and allows running `php artisan db:seed --class=UserSeeder` independently.
2. **Idempotency via `firstOrCreate`**:
   - *Decision*: Use Eloquent `User::firstOrCreate(['email' => 'frsawiris@manuscriptcataloguer'], [...])`.
   - *Rationale*: Prevents duplicate user creation and constraint errors on repeat migrations and seed runs.
3. **Password Hashing & Verified Status**:
   - *Decision*: Provide password `Gundi..ticsh19` (hashed by Eloquent cast) and set `email_verified_at => now()`, `name => 'Fr. Sawiris'`.
   - *Rationale*: Guarantees instant login capability without requiring manual email verification steps.

### Proposed Changes
1. **`database/seeders/UserSeeder.php`**:
   - Create seeder class extending `Illuminate\Database\Seeder`.
   - Seed `frsawiris@manuscriptcataloguer` with name `'Fr. Sawiris'`, password `'Gundi..ticsh19'`, and `email_verified_at = now()`.
   - Optionally maintain or include standard default test user setup.
2. **`database/seeders/DatabaseSeeder.php`**:
   - Update `run()` method to call `$this->call([GenreSeeder::class, CollectionSeeder::class, UserSeeder::class])`.
3. **`tests/Feature/Seeders/DatabaseSeederTest.php`**:
   - Add feature tests asserting `UserSeeder` creates `frsawiris@manuscriptcataloguer`, verifying password authentication with `Hash::check()`, and asserting idempotency.

### Data Models / Contracts

#### User Seed Data
```php
[
    'name' => 'Fr. Sawiris',
    'email' => 'frsawiris@manuscriptcataloguer',
    'password' => 'Gundi..ticsh19',
    'email_verified_at' => now(),
]
```

### File Structure
- `database/seeders/UserSeeder.php` *(new)*
- `database/seeders/DatabaseSeeder.php` *(modified)*
- `tests/Feature/Seeders/DatabaseSeederTest.php` *(modified)*

# Testing

### Validation Approach
Automated Pest feature tests will verify that `UserSeeder` creates the user with correct attributes, proper password hashing, and idempotent execution both standalone and within `DatabaseSeeder`.

### Key Scenarios
1. **User Creation & Password Verification**:
   - Run `UserSeeder`.
   - Assert `User` record exists for `frsawiris@manuscriptcataloguer`.
   - Assert `Hash::check('Gundi..ticsh19', $user->password)` returns `true`.
   - Assert `email_verified_at` is not null.
2. **Idempotency Verification**:
   - Run `UserSeeder` twice consecutively.
   - Assert `User::where('email', 'frsawiris@manuscriptcataloguer')->count()` is exactly 1.
3. **DatabaseSeeder Pipeline Execution**:
   - Run `DatabaseSeeder` via `$this->seed(DatabaseSeeder::class)`.
   - Assert collection, genre, and user seeders all execute cleanly.

### Test Changes
- Update `tests/Feature/Seeders/DatabaseSeederTest.php` to include test cases for `UserSeeder` execution, password verification, and idempotency.

# Delivery Steps

### ✓ Step 1: Create UserSeeder
Create `database/seeders/UserSeeder.php` to seed the user record for `frsawiris@manuscriptcataloguer` with password `Gundi..ticsh19`, name `Fr. Sawiris`, and verified email status.

### ✓ Step 2: Integrate with DatabaseSeeder and Update Test Coverage
Integrate `UserSeeder` into `DatabaseSeeder.php`, update Pest feature tests in `tests/Feature/Seeders/DatabaseSeederTest.php`, and verify with Pint.