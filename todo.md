# 🛡️ CuniApp Élevage: Critical Security Remediation Prompt

**Objective:** Execute the following security hardening tasks to ensure strict multi-tenant data isolation, defense-in-depth authorization, and audit compliance.  
**Role:** Senior Laravel Security Engineer  
**Constraint:** Do not break existing functionality. All changes must be tested against existing unit tests.  
**Priority:** Critical → High → Medium  

---

## 🔴 PHASE 1: CRITICAL DATA ISOLATION (Immediate)

### TASK 1.1: Enforce User Isolation on Notification Model
**Context:** The `Notification` model currently lacks the `BelongsToUser` trait. While the Controller filters by `user_id`, the Model does not enforce this globally, creating a risk of data leakage if accessed directly elsewhere.  
**File:** `app/Models/Notification.php`  
**Action:**  
1. Import `App\Traits\BelongsToUser`.  
2. Add `use BelongsToUser;` to the trait list.  
3. Ensure `user_id` is in the `$fillable` array.  
**Verification:**  
```php
// Test
$notification = Notification::create(['user_id' => 2, 'title' => 'Test']);
Auth::loginUsingId(1);
// Should return empty collection if scope works
$check = Notification::all(); 
assert($check->isEmpty());
```

### TASK 1.2: Implement Defense-in-Depth in Dashboard Controller
**Context:** `DashboardController` relies solely on Global Scopes for data filtering. If the `BelongsToUser` trait is bypassed or disabled, all user data leaks.  
**File:** `app/Http/Controllers/DashboardController.php`  
**Action:**  
1. In `index()`, explicitly add `->where('user_id', auth()->id())` to all model queries (`Male`, `Femelle`, `Saillie`, etc.).  
2. Do not rely *only* on the global scope.  
**Code Snippet:**  
```php
// BEFORE
$nbMales = Male::count();
// AFTER
$nbMales = Male::where('user_id', auth()->id())->count();
```
**Verification:** Manually inspect queries in Laravel Debugbar to ensure `where user_id = ?` is present in SQL.

### TASK 1.3: Secure Admin Data Access with Audit Logging
**Context:** Admin users bypass `BelongsToUser` global scopes. There is no record of when an admin views another user's private data.  
**File:** `app/Traits/BelongsToUser.php`  
**Action:**  
1. Inside the `addGlobalScope` method, add an `else` block for `auth()->user()->isAdmin()`.  
2. Log the access event to the `audit` channel.  
**Code Snippet:**  
```php
if (!auth()->user()->isAdmin()) {
    $builder->where('user_id', auth()->id());
} else {
    \Log::channel('audit')->info('Admin Data Access', [
        'admin_id' => auth()->id(),
        'model' => get_class($builder->getModel()),
        'timestamp' => now()
    ]);
}
```
**Verification:** Trigger an admin view action and check `storage/logs/audit.log`.

---

## 🟠 PHASE 2: AUTHORIZATION & ACCESS CONTROL (High Priority)

### TASK 2.1: Harden Webhook IP Verification
**Context:** `VerifyWebhookIp` middleware relies on `Setting::get('webhook_ip_whitelist')`. If settings are cached or null, it might allow all IPs.  
**File:** `app/Http/Middleware/VerifyWebhookIp.php`  
**Action:**  
1. Add a fallback to deny access if the whitelist is empty but the environment is `production`.  
2. Ensure the middleware is strictly applied to webhook routes in `routes/web.php`.  
**Verification:**  
```bash
# Test from non-whitelisted IP
curl -X POST https://cuniapp-test.loca.lt/payment/webhook/fedapay
# Should return 403
```

### TASK 2.2: Explicit Ownership Check in Resource Controllers
**Context:** Route Model Binding + Global Scope is good, but explicit checks in `show`, `edit`, `update`, `destroy` methods prevent IDOR if scopes fail.  
**Files:** `app/Http/Controllers/{Male,Femelle,Saillie,Naissance,Sale}Controller.php`  
**Action:**  
1. In `show`, `edit`, `update`, `destroy` methods, add:  
```php
if ($record->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
    abort(403, 'Unauthorized access to this record.');
}
```
**Verification:** Attempt to access another user's record ID while logged in as a standard user. Expect 403.

### TASK 2.3: Secure Email Verification Rate Limiting
**Context:** `EmailVerificationCodeController` stores codes in Cache. Ensure brute-force protection is active.  
**File:** `app/Http/Controllers/Auth/EmailVerificationCodeController.php`  
**Action:**  
1. Ensure `verify` method uses `RateLimiter` to prevent guessing the 6-digit code.  
2. Limit to 5 attempts per 10 minutes per email.  
**Verification:** Attempt to verify with wrong codes 6 times rapidly. Account should be throttled.

---

## 🟡 PHASE 3: CONFIGURATION & ENVIRONMENT (Medium Priority)

### TASK 3.1: Remove .env from Version Control
**Context:** The `.env` file was exposed in the project upload. This contains DB credentials and API keys.  
**File:** `.gitignore`  
**Action:**  
1. Ensure `.env` is listed in `.gitignore`.  
2. Rotate all exposed keys immediately (Database password, FedaPay keys, Mail credentials).  
**Verification:** Run `git ls-files | grep .env`. Should return nothing.

### TASK 3.2: Secure FedaPay Webhook Secret
**Context:** Webhook secret is stored in `Settings` table and `.env`.  
**File:** `app/Services/FedaPayService.php`  
**Action:**  
1. Prioritize `.env` variable `FEDAPAY_WEBHOOK_SECRET` over database settings for critical security keys.  
2. Ensure the secret is never logged in plain text.  
**Verification:** Check `storage/logs/laravel.log` after a webhook event. No secrets should be visible.

### TASK 3.3: Enable HTTPS Enforcement
**Context:** Payment and Auth routes must use HTTPS.  
**File:** `app/Http/Middleware/TrustProxies.php` & `.env`  
**Action:**  
1. Set `APP_URL` to `https://` in `.env`.  
2. Ensure `TrustProxies` middleware is configured to trust your load balancer/proxy (e.g., Cloudflare, Localtunnel).  
**Verification:** Attempt to access `/login` via `http://`. Should redirect to `https://`.

---

## ✅ VERIFICATION CHECKLIST (Definition of Done)

- [ ] **Data Isolation:** User A cannot see User B's data via API or UI.
- [ ] **Admin Audit:** All admin data access is logged in `storage/logs/audit.log`.
- [ ] **Defense-in-Depth:** Controllers explicitly check `user_id` regardless of Global Scopes.
- [ ] **Webhook Security:** Webhooks reject requests from non-whitelisted IPs.
- [ ] **Secrets:** No API keys or DB passwords are committed to Git.
- [ ] **Rate Limiting:** Verification codes cannot be brute-forced.

---

**Instruction to Developer:**  
Execute Phase 1 tasks immediately. Commit changes with message `security: enforce data isolation and audit logging`. Do not proceed to Phase 2 until Phase 1 tests pass.