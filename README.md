# Groupe Abbatiello — Franchise Management Platform

A production-grade internal management platform for **Groupe Abbatiello**, built with Laravel 12 and Filament 5. Manages brands, restaurants, users, and roles across the franchise network.

---

## Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Admin Panel | Filament 5 (Livewire 4) |
| Roles & Permissions | Spatie laravel-permission |
| Database | MySQL / MariaDB |
| Auth | Laravel Fortify |
| PHP | 8.2+ |

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

### 3. Install Spatie laravel-permission
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 4. Run migrations & seed
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 5. Build assets
```bash
npm run build
# or for development:
npm run dev
```

### 6. Serve
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

| Role | Access |
|---|---|
| `super_admin` | Full platform — bypasses all Gate checks via `Gate::before()` |
| `admin` | Full CRUD on brands, stores, users — scoped to own brand |
| `manager` | View/edit own store + manage users of own store |
| `employee` | Panel access only (dashboard, my info, holidays) |

---

## File Structure

```
app/
├── Filament/
│   ├── Pages/
│   │   ├── Auth/Login.php           ← Custom Groupe Abbatiello login page
│   │   ├── Dashboard.php            ← Cards + birthday calendar
│   │   └── MyInformations.php       ← User self-service profile page
│   ├── Resources/
│   │   ├── Brands/BrandResource.php
│   │   ├── Stores/StoreResource.php
│   │   ├── Themes/ThemeResource.php
│   │   └── Users/UserResource.php
│   └── Widgets/
│       └── UserStatsWidget.php      ← Active/inactive/manager counts
├── Http/Middleware/
│   └── SetLocale.php                ← Reads locale from DB, sets App::setLocale()
├── Livewire/
│   └── LocaleSwitcher.php           ← FR/EN toggle, saves to users.locale
├── Models/
│   ├── Brand.php
│   ├── Store.php
│   ├── Theme.php
│   └── User.php                     ← HasRoles, SoftDeletes, FilamentUser
└── Providers/
    └── AuthServiceProvider.php      ← Gate::before() super_admin bypass

database/
├── migrations/
│   ├── ..._create_themes_table.php
│   ├── ..._create_brands_table.php
│   ├── ..._create_stores_table.php
│   ├── ..._modify_users_table.php
│   └── ..._add_soft_deletes_to_users_table.php
└── seeders/
    ├── RoleSeeder.php     → 4 roles + 15 permissions
    ├── ThemeSeeder.php    → Rouge, Rose themes
    ├── BrandSeeder.php    → Salvatoré, Crèmerie Chez Mamie
    ├── StoreSeeder.php    → 5 stores across 2 brands
    └── UserSeeder.php     → 12 users covering all role/state combinations

lang/
├── fr/app.php             ← French translations (default)
└── en/app.php             ← English translations

resources/views/
├── filament/
│   ├── auth/pages/login.blade.php
│   └── pages/
│       ├── dashboard.blade.php
│       └── my-informations.blade.php
└── livewire/
    └── locale-switcher.blade.php

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
| Soft deletes on users | `deleted_at` | Data preserved, restorable via TrashedFilter |
| No soft deletes on users originally | `terminated_at` + `is_active` | Business logic separate from data deletion |
| Roles | Spatie, global, 1 per user | `syncRoles()` enforces single role |
| `super_admin` | `Gate::before()` bypass | No permissions assigned — bypasses all checks |
| Language storage | `users.locale` in DB | Persists across sessions and devices |
| Login page | Custom Blade view | Groupe Abbatiello brand identity with all 8 sub-brands |
| `is_active` on Store | Default `false` | Inactive until officially opened |
| `is_active` on User | Default `true` | Active on creation |

---

## i18n

The platform supports **French** (default) and **English**. Language is stored per user in `users.locale` and applied via `SetLocale` middleware.

The FR/EN switcher appears in the topbar. Switching updates the DB and reloads the page — all labels, section titles, helper texts, filters, and navigation items respond to the locale.

---

## Dashboard Features

- **My informations** — self-service profile edit (name, email, phone, birth date, password)
- **Harassment policy** — downloads the PDF in the user's current language
- **My holidays** — links to the Monday.com form
- **Birthday calendar** — shows employee birthdays for the current month, navigable by month