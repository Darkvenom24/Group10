# Group 10 — Laravel Viva & Live Demo Guide (`student_guide_laravel.md`)

**Institution:** Government MCA College, Maninagar (GMCA)  
**Team Members:** Ridham Bambhaniya (`26GMCA61`), Riya Thakkar (`26GMCA33`), Vaibhav Senjaliya (`26GMCA52`)

---

## 1. URGENT Pre-Demo Setup Check (Run Once Before Launching)

If `laravel\vendor\autoload.php`, `laravel\.env`, or `laravel\database\database.sqlite` are missing on the presentation PC, `php artisan` commands inside `start-laravel.bat` will fail. Run these commands once in PowerShell from the workspace root:

```powershell
cd laravel
copy .env.example .env
C:\Users\vaibh\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\composer.bat install
php artisan key:generate
New-Item -ItemType File -Path "database\database.sqlite" -Force
php artisan migrate --force
php artisan db:seed --force
```

Once initialized, launch the local Laravel server anytime by running **`start-laravel.bat`** in the project root, which opens **`http://127.0.0.1:8000`**.

---

## 2. Two "Laravel" Modes in This Project (Important Distinction)

| Mode | URL / Entry Point | What It Is | When to Use |
| :--- | :--- | :--- | :--- |
| **Real Laravel 13 MVC App** | `http://127.0.0.1:8000` (via `start-laravel.bat`) | Full **Laravel (`^13.17`)** on **PHP 8.3** + **SQLite** + **Blade Views** + **Eloquent ORM** inside `laravel/`. | **Primary local live demo for Sir.** |
| **Vercel Cloud Mirror** | `/laravel` (`vercel.json` rewrites `/laravel` to `laravel-hub.html`) | Static HTML/JS hub synced with `api/phpmyadmin_api.php` / `php/phpmyadmin_api.php` (+ `api/laravel.php` summary), because Vercel runs serverless `vercel-php@0.7.3` instead of `php artisan serve`. | When demonstrating on the live Vercel deployment URL. |

---

## 3. Where Everything Lives in `laravel/` (MVC Architecture Map)

### Routes (`38` route definitions)
- **`laravel/routes/web.php`** — Defines all routes across:
  - Core & Auth: `/`, `/login`, `/quick-login/{username}`, `/logout`, `/switch-system/{code}`
  - Railway Portal (`/railway/*`): `/`, `/book`, `/bookings`, `/ticket/{id}`, `/ticket/{id}/edit`, `PUT /ticket/{id}`, `DELETE /ticket/{id}`, `/systems`
  - Team Members (`/members/*`): `/`, `/{id}`
  - Practicals (`/practicals/*`): `/1`, `/2`, `/4`, `/7` (GET & POST), `/7-data`
  - PHP Suite (`/php/*`): `/`, `/9-1` to `/11-4`, `/12`, `/db-explorer`

### Controllers (`laravel/app/Http/Controllers/`)
- **`HomeController.php`** — Auto-logs in `ridham61` if guest, computes `$stats` (`total_users`, `total_systems`, `total_bookings`, `total_revenue`, `p7_students`, `user_bookings`), and renders `home`.
- **`AuthController.php`** — Standard login/logout, `quickLogin($username)` for 1-click evaluator account switching, and `switchSystem($code)` which stores `active_system_code` in Laravel `session()`.
- **`RailwayPortalController.php`** — Division-specific trains (`getTrainsForSystem`), new ticket booking (`book`), role-scoped & paginated bookings (`bookings`), printable Electronic Reservation Slip (`showTicket`), Edit/Update/Cancel CRUD (`editTicket`, `updateTicket`, `cancelTicket`), and multi-division overview (`systems`).
- **`MemberController.php`** — Group 10 team cards (`index`) and individual member profiles (`show`).
- **`PracticalsController.php`** — Practicals 1, 2, 4 (Calculator), Practical 7 registration form submission (`Practical7Student::create`), and Practical 7 data list (`paginate(10)`).
- **`PhpPracticalsController.php`** — PHP Practicals `9.1`–`9.3`, `10.1`–`10.2`, `11.1`–`11.4`, `12`, and `dbExplorer`.

### Models & Database Seeding
- **Eloquent Models (`laravel/app/Models/`)**: `User.php`, `RailwayBooking.php`, `RailwaySystem.php`
- **Database Seeder (`laravel/database/seeders/DatabaseSeeder.php`)**:
  - **5 Railway Divisions**:
    1. `WR-ADI` — Western Railway (Admin: K. P. Rathod, `station_master`)
    2. `NR-NDLS` — Northern Railway (Admin: Rajesh Sharma, `station_master_ndls`)
    3. `CR-CSMT` — Central Railway (Admin: Sanjay Deshmukh, `station_master_csmt`)
    4. `SR-MAS` — Southern Railway (Admin: M. S. Ramanathan, `station_master_mas`)
    5. `ER-HWH` — Eastern Railway (Admin: Subhashish Roy, `station_master_hwh`)
  - **9 Users (all password `password123`)**:
    - 5 Primary Accounts: `ridham61` (`26GMCA61`), `riya33` (`26GMCA33`), `vaibhav52` (`26GMCA52`), `station_master` (`SM-ADI-01`, `is_admin = true`), `aarav05` (`26GMCA05`)
    - 4 Additional Division Station Masters
  - **525 Railway Bookings**: Seeds **105 entries per primary user** (`5 × 105 = 525`) across all 5 railway divisions.
  - **4 Practical 7 Student Records**: Seeded for `26GMCA52`, `26GMCA61`, `26GMCA33`, and `26GMCA05`.

### Blade Views (`laravel/resources/views/`)
- **Master Layout (`layouts/app.blade.php`)**:
  - Top bar with **Division System** switcher dropdown & **Active User (1-Click Switch)** dropdown
  - Navigation bar (`Home Hub`, `Railway Portal`, `Practicals`, `PHP Practicals`, `Team Group 10`, `+ Book Train Ticket`)
  - Green context strip displaying active railway division, zone, and designated Station Master
- **Page Views**:
  - `home.blade.php` — Dashboard metrics, 1-click division pills, 5 user cards (`105 Records` each), and Practicals 1–12 matrix
  - `auth/login.blade.php` — Credential login (`password123`) + 1-Click Evaluator Quick Switch
  - `railway/index.blade.php` — Ticket reservation form (trains scoped to active division)
  - `railway/bookings.blade.php` — Master Bookings table with Eloquent pagination (`10, 15, 25, 50, 100`), search, and class filter
  - `railway/ticket.blade.php` — Printable Electronic Reservation Slip (ERS) (`window.print()`)
  - `railway/edit.blade.php` — Update reservation form (`PUT /railway/ticket/{id}`)
  - `railway/systems.blade.php` — Overview of all 5 Railway Divisions
  - `practicals/php/db_explorer.blade.php` — Interactive table & SQL explorer for SQLite tables

---

## 4. Step-by-Step Live Demo Script for Sir (`http://127.0.0.1:8000`)

1. **Home Hub (`/`)**
   - Opens auto-signed-in as **Ridham Bambhaniya (`ridham61`)**.
   - Show the **5 User Account cards** (each with **`105 Records`**) and the **1-Click Switch Division** pills showing booking counts across the 5 divisions.
2. **Role Scope & Eloquent Pagination (`/railway/bookings`)**
   - Open **Railway Portal → Master Bookings List**.
   - As `ridham61`, `$scope` defaults to `'my'`, showing **105 records** paginated (`15` per page by default; switchable between `10, 15, 25, 50, 100`).
   - Open the **Top-Right User Dropdown → Station Master (`station_master`)** (`/quick-login/station_master`).
   - Because `isStationMaster()` returns `true`, `$scope` defaults to `'all'` and displays all **525+ bookings** across all users and divisions.
   - Optionally open **Sign In Page** (`/login`) from the dropdown to show standard credential login (`password123`) alongside 1-click switching.
3. **5 Distinct Railway Divisions (`/railway/systems` or Top-Left `Division System` Dropdown)**
   - Switch from `WR-ADI` (Western Railway) to another division such as `NR-NDLS` (Northern Railway).
   - Point out the green context banner updating to the selected division and its designated Station Master.
4. **Full CRUD & Printable E-Ticket (`/railway`)**
   - Click **`+ Book Train Ticket`** (`/railway`) and show that the train dropdown dynamically loads trains for the active division.
   - Submit a booking → redirects to the **Electronic Reservation Slip (ERS)** (`/railway/ticket/{id}`) with **Print E-Ticket Slip**.
   - Return to **Master Bookings List** (`/railway/bookings`) → click **Edit** (`/railway/ticket/{id}/edit`, submits `PUT`) to modify class/status, then **Delete/Cancel** (`DELETE /railway/ticket/{id}`) to demonstrate full database CRUD.
5. **All Practicals (1–12) & Database Explorer Inside Laravel**
   - **Practicals Dropdown**: Show **Practical 4 (Calculator)** (`/practicals/4`), **Practical 7 (Academic Form)** (`/practicals/7`), and **Practical 7 (Student Data)** (`/practicals/7-data`).
   - **PHP Practicals Dropdown**: Open any exercise (`9.1`–`12`) and finish on **phpMyAdmin Database Explorer** (`/php/db-explorer`) to inspect live SQLite tables (`users`, `railway_bookings`, `railway_systems`, `practical7_students`) with row counts and SQL preview.
   - **Team Group 10 Dropdown** (`/members`): Show Ridham (`26GMCA61`), Riya (`26GMCA33`), and Vaibhav (`26GMCA52`).

---

## 5. Quick Viva Reference ("Where Is X in the Code?")

| Question from Sir | File to Open | What to Point Out |
| :--- | :--- | :--- |
| "Where are routes defined?" | `laravel/routes/web.php` | All `Route::get`, `Route::post`, `Route::put`, `Route::delete`, and route prefixes (`railway`, `members`, `practicals`, `php`). |
| "How did you seed 525 records (105 per user) & 5 divisions?" | `laravel/database/seeders/DatabaseSeeder.php` | Loops `1..105` over `$primaryUsers` (`ridham61`, `riya33`, `vaibhav52`, `station_master`, `aarav05`) across 5 `RailwaySystem` records. |
| "Where does pagination happen?" | `laravel/app/Http/Controllers/RailwayPortalController.php` | Inside `bookings()`: `$perPage` (`15`, allowed `[10, 15, 25, 50, 100]`) and `$query->paginate($perPage)->withQueryString()`. |
| "How does Station Master admin differ from normal users?" | `RailwayPortalController.php` & `User.php` | `$scope = $request->query('scope', $user->isStationMaster() ? 'all' : 'my');` — admin defaults to viewing all records, members default to their own. |
| "How does switching Railway Divisions work?" | `laravel/app/Http/Controllers/AuthController.php` | `switchSystem($code)` stores `active_system_code` in `session()`, and `RailwayPortalController` loads division trains/headers accordingly. |
| "Where is the master template layout?" | `laravel/resources/views/layouts/app.blade.php` | Shared header, system/user dropdowns, nav menus, flash alerts, footer, and `@yield('content')`. |
