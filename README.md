# Groupe Abbatiello — Franchise Management Platform

A production-grade internal management platform for **Groupe Abbatiello**, built with Laravel 12 and Filament 5. Manages brands, restaurants, users, and roles across the franchise network.

---

## Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Admin Panel | Filament 5 (Livewire 4) |
| Roles & Permissions | Spatie laravel-permission v6 |
| Database | MySQL / MariaDB |
| Auth | Laravel Fortify |
| PHP | 8.2+ |
| Testing | Pest |

---

## Brands

| Brand | Tag | Theme |
|---|---|---|
| Salvatoré | `SAL-` | Rouge (`#E40F18`) |
| Crèmerie Chez Mamie | `CCM` | Rose (`#F4919A`) |

---

## Installation

### 1. Clone & install dependencies
```bash
git clone https://github.com/saidiLamiya2/abbatiello-project.git
cd abbatiello-project
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### 2. Configure `.env`
```env
APP_NAME="Groupe Abbatiello"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Run migrations & seed
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 4. Build assets
```bash
npm run build
# or for development:
npm run dev
```

### 5. Serve
```bash
php artisan serve
```

Then open [http://127.0.0.1:8000](http://127.0.0.1:8000) — redirects automatically to `/admin/login`.

---

## Test logins (all use password: `password`)

| Email | Role | Scope |
|---|---|---|
| `superadmin@platform.test` | super_admin | Full platform access |
| `marco@groupeabbatiello.com` | admin | SAL brand |
| `sophie@groupeabbatiello.com` | admin | CCM brand |
| `c.jobin@operationfranchises.com` | manager | SAL Évènementiel |
| `marie.tremblay@groupeabbatiello.com` | manager | SAL Lebourgneuf |
| `jonathan.leblanc@groupeabbatiello.com` | manager | CCM Saint-Anselme |
| `beauport@cremeriechezmamie.com` | manager | CCM Beauport |
| `sarah.roy@groupeabbatiello.com` | employee | SAL Évènementiel (active) |
| `luc.gagnon@groupeabbatiello.com` | employee | SAL Évènementiel (work stoppage) |
| `julie.cote@groupeabbatiello.com` | employee | SAL Lebourgneuf (terminated) |
| `emilie.bergeron@groupeabbatiello.com` | employee | CCM Saint-Anselme (active) |
| `nicolas.fortin@groupeabbatiello.com` | employee | CCM Beauport (active) |

---

## Roles & Permissions

Permission convention: `Action:Model` (e.g. `ViewAny:Brand`, `Delete:User`)

| Role | Access | Permissions |
|---|---|---|
| `super_admin` | Full platform — `Gate::before()` bypass | None needed |
| `admin` | Full CRUD — scoped to own brand via Policy | All except `*:Theme` |
| `manager` | View/edit own store + manage own store users | `ViewAny/View/Update:Store` + `*:User` |
| `employee` | Panel access only | None |

---

## Architecture

Follows the layered architecture from `_GUIDE.md`:

```
app/
├── Actions/
│   └── Users/
│       └── AssignUserRole.php       ← Atomic: syncRoles() one-role-at-a-time
├── Enums/
│   ├── ProjectType.php              ← Nouveau | Corpo | Reprise | Vente
│   ├── UserLocale.php               ← fr | en
│   └── UserRole.php                 ← super_admin | admin | manager | employee
├── Filament/
│   ├── Pages/
│   │   ├── Auth/Login.php           ← Custom Groupe Abbatiello login page
│   │   ├── Dashboard.php            ← Cards + birthday calendar
│   │   └── MyInformations.php       ← User self-service profile
│   ├── Resources/
│   │   ├── Brands/
│   │   │   ├── BrandResource.php    ← Routing, access, wiring only
│   │   │   ├── Pages/
│   │   │   ├── Schemas/BrandForm.php
│   │   │   └── Tables/BrandsTable.php
│   │   ├── Stores/
│   │   │   ├── StoreResource.php
│   │   │   ├── Pages/
│   │   │   ├── Schemas/StoreForm.php
│   │   │   └── Tables/StoresTable.php
│   │   ├── Themes/
│   │   │   ├── ThemeResource.php
│   │   │   ├── Pages/
│   │   │   ├── Schemas/ThemeForm.php
│   │   │   └── Tables/ThemesTable.php
│   │   └── Users/
│   │       ├── UserResource.php
│   │       ├── Pages/
│   │       ├── Schemas/UserForm.php
│   │       └── Tables/UsersTable.php
│   └── Widgets/
│       └── UserStatsWidget.php      ← Active/inactive/manager counts
├── Http/Middleware/
│   └── SetLocale.php                ← Delegates to LocaleService
├── Livewire/
│   └── LocaleSwitcher.php           ← FR/EN toggle — delegates to LocaleService
├── Models/
│   ├── Brand.php
│   ├── Store.php                    ← casts project_type → ProjectType enum
│   ├── Theme.php                    ← SoftDeletes
│   └── User.php                     ← HasRoles, SoftDeletes, FilamentUser
├── Policies/
│   ├── BrandPolicy.php              ← ViewAny/View/Create/Update/Delete:Brand
│   ├── StorePolicy.php              ← Scoped: manager=own store, admin=own brand
│   ├── ThemePolicy.php              ← super_admin only; blocks delete if brand assigned
│   └── UserPolicy.php               ← Cannot delete self or higher role
├── Providers/
│   └── AuthServiceProvider.php      ← Registers policies + Gate::before() bypass
└── Services/
    └── LocaleService.php            ← switchFor() + resolveFor()

database/
├── migrations/
│   ├── ..._create_themes_table.php
│   ├── ..._create_brands_table.php
│   ├── ..._create_stores_table.php
│   ├── ..._modify_users_table.php
│   └── ..._add_soft_deletes_to_users_table.php
└── seeders/
    ├── RoleSeeder.php     → 4 roles + 20 permissions (Action:Model convention)
    ├── ThemeSeeder.php    → Rouge, Rose themes
    ├── BrandSeeder.php    → Salvatoré, Crèmerie Chez Mamie
    ├── StoreSeeder.php    → 5 stores across 2 brands
    └── UserSeeder.php     → 12 users covering all role/state combinations

lang/
├── fr/
│   ├── app.php            ← Custom UI strings (default locale)
│   ├── validation.php     ← French validation messages
│   ├── auth.php
│   ├── pagination.php
│   └── passwords.php
└── en/
    ├── app.php            ← Custom UI strings
    ├── validation.php
    ├── auth.php
    ├── pagination.php
    └── passwords.php

resources/views/
├── filament/
│   ├── auth/pages/login.blade.php   ← Dark luxury split-screen login
│   └── pages/
│       ├── dashboard.blade.php
│       └── my-informations.blade.php
└── livewire/
    └── locale-switcher.blade.php

tests/
├── Feature/
│   ├── Actions/AssignUserRoleTest.php
│   ├── Auth/LoginTest.php
│   ├── Filament/ResourceAccessTest.php
│   └── Services/LocaleServiceTest.php
└── Unit/
    └── Enums/EnumsTest.php

public/
├── documents/
│   ├── harassment-policy-fr.pdf
│   └── harassment-policy-en.pdf
└── images/
    ├── groupe_abbatiello_logo.png   ← Dark mode topbar logo
    └── logo_abbatiello_black.png    ← Light mode topbar logo
```

---

## Key Design Decisions

| Decision | Choice | Reason |
|---|---|---|
| Access control | Policies in `app/Policies/` | One policy per model, scoping logic centralized |
| Permission strings | `Action:Model` convention | Consistent, readable, aligns with guide |
| Soft deletes on users | `deleted_at` | Data preserved, restorable via TrashedFilter |
| Employment tracking | `terminated_at` + `is_active` | Business logic separate from data deletion |
| Roles | Spatie, global, 1 per user | `AssignUserRole` action enforces single role |
| `super_admin` | `Gate::before()` bypass | No permissions assigned — bypasses all checks |
| Enums | `ProjectType`, `UserRole`, `UserLocale` | Type safety, single source of truth |
| Language storage | `users.locale` in DB | Persists across sessions and devices |
| Locale logic | `LocaleService` | Reused by middleware and Livewire component |
| Role assignment | `AssignUserRole` action | Extracted from page lifecycle hooks |
| Resource structure | `Schemas/` + `Tables/` per resource | Slim Resource classes, separated concerns |
| Login page | Custom Blade view | Groupe Abbatiello brand identity with all 8 sub-brands |
| `is_active` on Store | Default `false` | Inactive until officially opened |
| `is_active` on User | Default `true` | Active on creation |

---

## i18n

The platform supports **French** (default) and **English**. Language is stored per user in `users.locale` and applied via `SetLocale` middleware → `LocaleService`.

- FR/EN switcher in the topbar — updates DB and reloads the page
- All labels, section titles, helper texts, filters, navigation items, and validation messages are translated
- Harassment policy PDF served in the user's active language

---

## Running Tests

```bash
# All tests
php artisan test --compact

# Targeted
php artisan test --compact --filter=AssignUserRoleTest
php artisan test --compact --filter=LocaleServiceTest
php artisan test --compact --filter=EnumsTest
php artisan test --compact --filter=LoginTest
php artisan test --compact --filter=ResourceAccessTest
```

---

## Dashboard Features

- **My informations** — self-service profile edit (name, email, phone, birth date, password)
- **Harassment policy** — downloads the PDF in the user's current language
- **My holidays** — links to the Monday.com form
- **Birthday calendar** — shows employee birthdays for the current month, navigable by month