# 🔧 TODO: Fix Multi-Tenancy CRUD Issues Across All Modules

## 📋 Problem Summary

**Critical Issue**: All CRUD operations are failing across `Male`, `Femelle`, `MiseBas`, `Saillie`, `Naissance`, and `Expense` modules.

**Root Cause**: `firm_id` column is NULL for existing users after multi-tenancy migration, causing database integrity violations.

**Error Pattern**:
```
SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'firm_id' cannot be null
SQL: insert into `firm_audit_logs` (`firm_id`, `user_id`, `action`, ...)
```

---

## 🎯 Step-by-Step Fix Plan

### Step 1: Database Migration Fixes

**Task**: Make `firm_id` handling more resilient

1. **Update `firm_audit_logs` migration**:
   ```php
   // database/migrations/xxxx_xx_xx_update_firm_audit_logs_firm_id.php
   Schema::table('firm_audit_logs', function (Blueprint $table) {
       $table->unsignedBigInteger('firm_id')->nullable()->change();
   });
   ```

2. **Update `expenses` migration**:
   ```php
   // database/migrations/xxxx_xx_xx_update_expenses_firm_id.php
   Schema::table('expenses', function (Blueprint $table) {
       $table->unsignedBigInteger('firm_id')->nullable()->change();
   });
   ```

3. **Create data migration to assign firms to existing users**:
   ```php
   // database/migrations/xxxx_xx_xx_assign_firms_to_existing_users.php
   public function up()
   {
       $usersWithoutFirm = User::whereNull('firm_id')->get();
       
       foreach ($usersWithoutFirm as $user) {
           // Create a firm for each user without one
           $firm = Firm::create([
               'name' => "Entreprise de {$user->name}",
               'description' => 'Entreprise créée automatiquement',
               'owner_id' => $user->id,
               'status' => 'active',
           ]);
           
           $user->update(['firm_id' => $firm->id]);
           
           // Update all breeding records for this user
           DB::table('males')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
           DB::table('femelles')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
           DB::table('saillies')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
           DB::table('mises_bas')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
           DB::table('naissances')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
           DB::table('lapereaux')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
           DB::table('sales')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
           DB::table('expenses')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
       }
   }
   ```

---

### Step 2: Fix `FirmAuditLog` Model

**File**: `app/Models/FirmAuditLog.php`

**Current Issue**: `log()` method doesn't automatically get `firm_id` from user

**Fix**:
```php
public static function log($firmId = null, $userId = null, $action, $field = null, $oldValue = null, $newValue = null)
{
    // Auto-detect from authenticated user if not provided
    if (!$userId && auth()->check()) {
        $userId = auth()->id();
    }
    
    if (!$firmId && auth()->check()) {
        $firmId = auth()->user()->firm_id;
    }
    
    // Fallback: if still no firm_id, use first available firm or skip logging
    if (!$firmId) {
        // Option 1: Skip audit log (safer)
        return null;
        
        // Option 2: Create default firm (aggressive)
        // $firmId = Firm::firstOrCreate(['name' => 'Default'])->id;
    }
    
    return self::create([
        'firm_id' => $firmId,
        'user_id' => $userId,
        'action' => $action,
        'field' => $field,
        'old_value' => $oldValue ? json_encode($oldValue) : null,
        'new_value' => $newValue ? json_encode($newValue) : null,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);
}
```

---

### Step 3: Fix `BelongsToUser` Trait

**File**: `app/Traits/BelongsToUser.php`

**Current Issue**: Global scope doesn't handle users without `firm_id`

**Fix**:
```php
protected static function bootBelongsToUser()
{
    // Auto-assign user_id and firm_id on creation
    static::creating(function ($model) {
        if (auth()->check()) {
            $user = auth()->user();
            
            if (!$model->user_id) {
                $model->user_id = $user->id;
            }
            
            // ✅ CRITICAL: Only set firm_id if user has one
            if ($user->firm_id && !$model->firm_id) {
                $model->firm_id = $user->firm_id;
            } elseif (!$model->firm_id) {
                // Fallback: try to get firm from user relationship
                $model->firm_id = $user->firm?->id;
            }
        }
    });
    
    // Global Scope for data isolation
    static::addGlobalScope('firm', function ($builder) {
        if (!auth()->check()) {
            return;
        }
        
        $user = auth()->user();
        $modelTable = $builder->getModel()->getTable();
        
        // Super Admin sees all
        if ($user->isSuperAdmin()) {
            return;
        }
        
        // Firm Admin/Employee: scope by firm_id
        if ($user->firm_id && in_array($user->role, ['firm_admin', 'employee'])) {
            $builder->where("{$modelTable}.firm_id", $user->firm_id);
        }
        // Fallback: scope by user_id only (for users without firm)
        elseif (auth()->id()) {
            $builder->where("{$modelTable}.user_id", auth()->id());
        }
    });
}
```

---

### Step 4: Update Controllers to Handle Missing Firm

**Files**: All breeding controllers (`MaleController`, `FemelleController`, `MiseBasController`, `SaillieController`, `NaissanceController`, `ExpenseController`)

**Add at the beginning of `store()` and `update()` methods**:
```php
public function store(Request $request)
{
    // ✅ CRITICAL: Check if user has a firm
    if (!auth()->user()->firm_id) {
        return back()
            ->withErrors(['error' => 'Votre compte n\'est associé à aucune entreprise. Contactez le support.'])
            ->withInput();
    }
    
    // ... rest of store logic
}
```

**Update `FirmAuditLog::log()` calls**:
```php
// Before (causes error):
FirmAuditLog::log(
    auth()->user()->firm_id,  // ❌ Can be NULL
    auth()->id(),
    'male_created',
    'code',
    null,
    $male->code
);

// After (safe):
FirmAuditLog::log(
    null,  // ✅ Let the model auto-detect
    auth()->id(),
    'male_created',
    'code',
    null,
    $male->code
);
```

---

### Step 5: Add User Firm Validation Middleware

**Create**: `app/Http/Middleware/EnsureUserHasFirm.php`

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasFirm
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->firm_id) {
            return redirect()->route('profile.edit')
                ->withErrors(['error' => 'Votre compte doit être associé à une entreprise. Contactez le support.']);
        }
        
        return $next($request);
    }
}
```

**Register in `app/Http/Kernel.php`**:
```php
protected $routeMiddleware = [
    // ...
    'firm.required' => \App\Http\Middleware\EnsureUserHasFirm::class,
];
```

**Apply to breeding routes in `routes/web.php`**:
```php
Route::middleware(['verified', 'check.subscription', 'firm.required'])->group(function () {
    // All breeding routes
    Route::prefix('males')->name('males.')->group(function () {
        // ...
    });
    // ... other modules
});
```

---

### Step 6: Update Profile Page to Show Firm Status

**File**: `resources/views/profile/edit.blade.php`

**Add warning if user has no firm**:
```blade
@if (!auth()->user()->firm_id)
<div class="alert-cuni error" style="margin-bottom: 24px;">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div>
        <strong>Entreprise non configurée</strong>
        <p>Votre compte n'est associé à aucune entreprise. Certaines fonctionnalités seront limitées.</p>
        <p>Contactez le support à <a href="mailto:contact@anyxtech.com">contact@anyxtech.com</a></p>
    </div>
</div>
@endif
```

---

### Step 7: Test & Verify

**Checklist**:
- [ ] Run all migrations: `php artisan migrate`
- [ ] Run data migration: `php artisan db:seed --class=FirmAssignmentSeeder` (if created)
- [ ] Verify all users have `firm_id`: `DB::table('users')->whereNull('firm_id')->count()`
- [ ] Test creating a Male
- [ ] Test creating a Femelle
- [ ] Test creating a Saillie
- [ ] Test creating a MiseBas
- [ ] Test creating a Naissance
- [ ] Test creating an Expense
- [ ] Check `firm_audit_logs` table for new entries
- [ ] Verify no NULL `firm_id` in audit logs

---

## 🚨 Priority Order

1. **IMMEDIATE**: Fix `FirmAuditLog::log()` to handle NULL `firm_id` (Step 2)
2. **IMMEDIATE**: Make `firm_id` nullable in migrations (Step 1)
3. **HIGH**: Run data migration to assign firms to existing users (Step 1.3)
4. **HIGH**: Update `BelongsToUser` trait (Step 3)
5. **MEDIUM**: Update all controllers (Step 4)
6. **MEDIUM**: Add middleware (Step 5)
7. **LOW**: Update UI warnings (Step 6)

---

## 📝 Expected Outcome

After implementing these fixes:
- ✅ All CRUD operations work without `firm_id` errors
- ✅ Audit logs are created successfully (or safely skipped)
- ✅ Existing users can continue working
- ✅ New users automatically get firms assigned
- ✅ Data isolation still works correctly
- ✅ No data loss or corruption

---

## 🔍 Debug Commands

```bash
# Check users without firms
php artisan tinker
>>> \App\Models\User::whereNull('firm_id')->count()

# Check audit logs with NULL firm_id
>>> \App\Models\FirmAuditLog::whereNull('firm_id')->count()

# Test creating a male
>>> \App\Models\Male::create(['code' => 'TEST-001', 'nom' => 'Test', 'user_id' => 1, 'firm_id' => 1])

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

**Estimated Time**: 2-3 hours
**Risk Level**: Medium (data migration required)
**Backup Required**: ⚠️ YES - Backup database before running migrations