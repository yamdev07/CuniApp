Here is a comprehensive, step-by-step implementation prompt based on your requirements and the existing codebase structure provided in the text file. You can use this prompt to guide an AI developer or a development team to execute this feature accurately.

***

# Implementation Prompt: Multi-Tenancy (Firm-Based) & Subscription User Limits

**Context:**
We are upgrading an existing Laravel application (**CuniApp Élevage**) currently focused on rabbit breeding management. The current architecture is **User-Centric** (data scoped via `user_id` using `app/Traits/BelongsToUser.php`). We need to evolve this into a **Firm-Centric (Multi-Tenancy)** architecture where a "Firm Admin" manages a company space, and "Employees" work under them, limited by subscription plans.

**Current Codebase Reference:**
Please refer to the provided file content (Pasted_Text_1774520154668.txt) for existing models (`User`, `Subscription`, `SubscriptionPlan`, `Male`, `Femelle`, etc.), controllers, and views (`welcome.blade.php`, `dashboard.blade.php`, `settings/index.blade.php`).

**Objective:**
Implement a hierarchy with three roles: **Super Admin**, **Firm Admin**, and **Employee**. Introduce a `Firm` entity. Enforce subscription limits on the number of employees per firm. Create dedicated dashboards for Firm Stats and Super Admin Global Stats.

---

## Step 1: Database Migrations & Schema Updates
**Task:** Create migrations to support Firms and update Users/Subscriptions.
1.  **Create `firms` table:**
    *   Columns: `id`, `name` (firm name), `description`, `owner_id` (links to Firm Admin User), `status` (active, banned), `created_at`, `timestamps`.
2.  **Update `users` table:**
    *   Add `firm_id` (nullable, foreign key to `firms`). *Note: Super Admins will have null firm_id.*
    *   Update `role` enum to include: `'super_admin'`, `'firm_admin'`, `'employee'`.
3.  **Update `subscriptions` table:**
    *   Add `firm_id` (nullable, foreign key). Subscriptions should belong to the Firm, not just the User.
    *   Ensure existing subscriptions are linked to the user's new firm during migration.
4.  **Update `subscription_plans` table:**
    *   Add `max_users` (integer). Set 1 & 3-month plans to `5`, 6 & 12-month plans to `10`.
5.  **Update Data Tables (`males`, `femelles`, `saillies`, `sales`, etc.):**
    *   Add `firm_id` column to all breeding and transaction tables.
    *   *Strategy:* Data should be scoped by `firm_id` rather than `user_id` to allow employees to see shared data. Keep `user_id` for audit trails (who created the record).

## Step 2: Models & Relationships
**Task:** Update Eloquent models to reflect the new hierarchy.
1.  **`app/Models/Firm.php` (New):**
    *   Relationships: `hasMany(User::class)`, `hasMany(Subscription::class)`, `hasMany(Male::class)`, etc.
    *   Accessors: `activeUsersCount`, `subscriptionLimit`.
2.  **`app/Models/User.php`:**
    *   Update `role` handling.
    *   Relationships: `belongsTo(Firm::class)`, `hasMany(Employee::class)` (if self-referencing needed, otherwise via Firm).
    *   Helper Methods: `isSuperAdmin()`, `isFirmAdmin()`, `isEmployee()`, `canAddMoreUsers()`.
3.  **`app/Models/SubscriptionPlan.php`:**
    *   Ensure `max_users` is accessible.
4.  **`app/Models/Subscription.php`:**
    *   Link to `Firm`.
5.  **Update `app/Traits/BelongsToUser.php`:**
    *   **Critical:** Modify the global scope. If the user is an Employee or Firm Admin, scope queries by `firm_id` instead of `user_id` for breeding data. Super Admins should see all or scoped by firm if impersonating.

## Step 3: Authentication & Signup Flow
**Task:** Modify registration to capture Firm details for Firm Admins.
1.  **`resources/views/welcome.blade.php`:**
    *   Update the Registration form. Add fields: `firm_name`, `firm_description`.
    *   These fields are required if registering as a Firm Admin (default).
2.  **`app/Http/Controllers/Auth/RegisteredUserController.php`:**
    *   Update `store()` method.
    *   Transactionally create the `User`, the `Firm`, and link them.
    *   Set user `role` to `'firm_admin'`.
    *   Assign `firm_id` to the user.
3.  **`app/Http/Controllers/Auth/AuthenticatedSessionController.php`:**
    *   Ensure login checks `firm.status`. If `banned`, logout and show error.

## Step 4: Firm Management & Employee Limits
**Task:** Allow Firm Admins to manage employees within subscription limits.
1.  **`app/Http/Controllers/FirmController.php` (New):**
    *   `index()`: Show firm details, stats, and employee list.
    *   `storeEmployee()`: Create a new user with `role='employee'` and the same `firm_id`.
    *   **Validation:** Check `SubscriptionPlan.max_users` against `Firm.activeUsersCount`. Throw error if limit reached.
2.  **`resources/views/firm/index.blade.php` (New):**
    *   Display Firm Info (Name, Description).
    *   Display "Employee Usage": e.g., "3 / 5 Users Used".
    *   List employees with options to Edit/Deactivate.
3.  **`app/Http/Controllers/SettingsController.php`:**
    *   Update `index()` and `update()` to handle Firm settings (Name, Description) if the user is a Firm Admin.

## Step 5: Dashboards & Statistics
**Task:** Create specific views for Firm Admins and Super Admins.
1.  **Firm Admin Dashboard (`resources/views/dashboard.blade.php`):**
    *   Add a "Company Stats" section (visible only to Firm Admin).
    *   Graphs: Finance (Sales vs Expenses), Activities (Saillies/Naissances over time), Employee Activity (who logged in/created records).
    *   Use existing chart libraries in the app or integrate Chart.js.
2.  **Super Admin Dashboard (`resources/views/super-admin/dashboard.blade.php`):**
    *   **Global Stats:** Total Firms, Total Revenue (MRR/ARR), Active Subscriptions.
    *   **Firm Leaderboard:** Top 5 firms by revenue (Gold, Silver, Bronze badges).
    *   **Health:** Late payments, Login rates, Signup evolution graph.
    *   **Firm Management:** List all firms, ability to `ban`/`activate` a firm.
3.  **`app/Http/Controllers/SuperAdminController.php` (New):**
    *   Logic to aggregate data across all firms.
    *   Method to `banFirm($id)` which sets `firms.status = 'banned'`.

## Step 6: Middleware & Security
**Task:** Ensure employees cannot access admin settings.
1.  **`app/Http/Middleware/CheckFirmAdmin.php` (New):**
    *   Check if `auth()->user()->role === 'firm_admin'`. Redirect otherwise.
2.  **`app/Http/Middleware/CheckSuperAdmin.php` (New):**
    *   Check if `auth()->user()->role === 'super_admin'`.
3.  **Route Updates (`routes/web.php`):**
    *   Group Firm Settings routes under `Middleware::checkFirmAdmin`.
    *   Group Super Admin routes under `Middleware::checkSuperAdmin`.
    *   Ensure breeding routes (`males`, `femelles`, etc.) are accessible to `employee` and `firm_admin` within the same firm.

## Step 7: UI/UX Improvements
**Task:** Reflect the hierarchy in the interface.
1.  **Navigation (`resources/views/layouts/cuniapp.blade.php`):**
    *   Show "Company" link only for Firm Admins.
    *   Show "Super Admin" link only for Super Admins.
    *   Display Firm Name in the header next to the Logo.
2.  **Profile Page (`resources/views/profile/edit.blade.php`):**
    *   Show Firm Info (Read-only for employees, Editable for Admin).
    *   Show User Role.
3.  **Subscription Pages (`resources/views/subscription/*.blade.php`):**
    *   Display `max_users` limit clearly on the plan card (e.g., "Up to 5 Users").
    *   On `subscription.status`, show current user count vs limit.

## Step 8: Data Migration Strategy
**Task:** Handle existing data.
1.  Write a seeder or migration script to:
    *   Create a `Firm` for every existing `User` (who is not an admin).
    *   Set `user.role` to `'firm_admin'`.
    *   Update all breeding records (`males`, `femelles`, etc.) to have `firm_id` = `user.firm_id`.
    *   Update `subscriptions` to link to `firm_id`.

---

**Coding Standards:**
*   Follow the existing styling (Tailwind CSS, Bootstrap Icons, `cuni-card` classes).
*   Use French language for UI text (as per existing `welcome.blade.php` and `dashboard.blade.php`).
*   Maintain the existing trait structure (`BelongsToUser`) but adapt it for multi-tenancy.
*   Ensure all new controllers use the `Notifiable` trait where appropriate for alerts.

**Deliverables:**
1.  Migration files.
2.  Updated Models (`User`, `Firm`, `SubscriptionPlan`).
3.  New Controllers (`FirmController`, `SuperAdminController`).
4.  Updated Views (`welcome`, `dashboard`, `settings`, new `firm` views).
5.  Middleware files.
6.  Route definitions.

**Please start by generating the Database Migrations and the `Firm` Model.**