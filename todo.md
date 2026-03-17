# 📋 COMPREHENSIVE TESTING & FIX PROMPT

```markdown
## CONTEXT:
- **Laravel Version**: 12.x (middleware registered in `bootstrap/app.php`, NOT `app/Http/Kernel.php`)
- **Payment Provider**: FedaPay (Mobile Money: MTN MoMo, Moov, Celtis Cash)
- **Current Issue**: Two critical problems need resolution

## PROBLEMS TO FIX:

### 1. ✅ SANCTUM MIDDLEWARE ERROR (Critical - Breaks API)
**File**: `bootstrap/app.php`
**Issue**: `\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class` is referenced but Sanctum package is not installed/defined
**Error Type**: Class not found / Undefined type

**SOLUTION NEEDED**:
- Either install Laravel Sanctum: `composer require laravel/sanctum`
- OR remove the middleware line if Sanctum is not needed for this project
- Provide the exact corrected code for `bootstrap/app.php`

### 2. ✅ PAYMENT FORM AJAX SUBMISSION
**File**: `resources/views/payment/initiate.blade.php`
**Issue**: Payment form submits as standard HTML but controller returns JSON → users see raw JSON instead of redirecting to FedaPay
**SOLUTION**: Update form to intercept submission with JavaScript, send via AJAX/Fetch API, handle JSON response, redirect to FedaPay checkout URL

## TESTING CHECKLIST TO VERIFY:

### Phase 1: Payment Flow Testing
- [ ] Navigate to `/subscription/plans`
- [ ] Select a plan and click "S'abonner"
- [ ] Verify redirect to `/payment/initiate/{transaction_id}`
- [ ] Enter phone number (test auto-format +229)
- [ ] Check validation errors appear for invalid phone
- [ ] Accept terms checkbox
- [ ] Click "Payer" button
- [ ] Verify loading state appears on button
- [ ] Verify success toast shows ("Paiement initié ! Redirection vers FedaPay...")
- [ ] Verify redirect to FedaPay checkout URL
- [ ] Complete test payment in FedaPay sandbox
- [ ] Verify redirect back to `/subscription/status`
- [ ] Verify success message displays
- [ ] Verify subscription status shows "Actif"

### Phase 2: Payment Callback Handling
- [ ] After FedaPay payment, check callback URL receives data
- [ ] Verify transaction status updates to "completed"
- [ ] Verify subscription activates automatically
- [ ] Verify invoice is generated
- [ ] Verify email notification is sent
- [ ] Check `storage/logs/laravel.log` for errors

### Phase 3: Invoice Generation
- [ ] Navigate to `/invoices`
- [ ] Verify new invoice appears after payment
- [ ] Click "PDF" button
- [ ] Verify PDF downloads correctly
- [ ] Check invoice has correct amount, date, number
- [ ] Verify invoice email notification received

### Phase 4: Subscription Protection Middleware
- [ ] Create test user WITHOUT subscription
- [ ] Try to access `/males` (protected route)
- [ ] Verify redirect to `/subscription/plans`
- [ ] Verify warning message displays
- [ ] Purchase subscription
- [ ] Try to access `/males` again
- [ ] Verify access is granted

### Phase 5: Admin Subscription Management
- [ ] Login as admin
- [ ] Navigate to `/admin/subscriptions`
- [ ] Verify user list displays
- [ ] Click "Activer Abonnement" on inactive user
- [ ] Select plan and duration
- [ ] Verify subscription activates
- [ ] Verify invoice is generated
- [ ] Verify user receives email

### Phase 6: Failed Payment Handling
- [ ] Initiate payment
- [ ] Cancel at FedaPay checkout
- [ ] Verify redirect back to `/subscription/status`
- [ ] Verify transaction status is "failed" or "pending"
- [ ] Verify error message displays
- [ ] Try payment again with same transaction

### Phase 7: Phone Number Validation
- [ ] Test: `01524152` → should auto-format to `+22901524152`
- [ ] Test: `+22901524152` → should accept as-is
- [ ] Test: `123` → should show error (too short)
- [ ] Test: `abcdefgh` → should show error (invalid chars)
- [ ] Test: Empty → should show error (required)

### Phase 8: Edge Cases
- [ ] Test with slow internet (loading state persists)
- [ ] Test with JavaScript disabled (graceful degradation)
- [ ] Test session timeout during payment
- [ ] Test duplicate transaction submission
- [ ] Test with expired subscription
- [ ] Test admin manual activation flow

## EXPECTED USER FLOW:

```
1. User clicks "S'abonner" on plan
        ↓
2. Transaction created (status: pending)
        ↓
3. Redirect to /payment/initiate/{id}
        ↓
4. User enters phone + accepts terms
        ↓
5. AJAX submits to /payment/process
        ↓
6. FedaPay API called → checkout_url returned
        ↓
7. User redirected to FedaPay
        ↓
8. User confirms payment on phone
        ↓
9. FedaPay redirects to /payment/callback
        ↓
10. Transaction updated (status: completed)
        ↓
11. Subscription activated
        ↓
12. Invoice generated
        ↓
13. Email sent to user
        ↓
14. Redirect to /subscription/status with success
```

## DELIVERABLES NEEDED:

### 1. ✅ FIXED `bootstrap/app.php`
Provide the EXACT corrected code with either:
- Sanctum installed and properly configured, OR
- Sanctum middleware line removed if not needed

### 2. ✅ UPDATED `payment/initiate.blade.php`
Complete file with:
- AJAX form submission (prevent default)
- Loading state on button
- Toast notifications (success/error)
- Phone number auto-formatting (+229 prefix)
- Form validation feedback before submission
- Redirect feedback message ("Redirecting to payment...")

### 3. ✅ SERVER SETUP COMMANDS
```bash
# Storage link
php artisan storage:link

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cron jobs for subscription expiration
crontab -e
# Add: 0 8 * * * cd /path/to/project && php artisan subscriptions:check-expiration >> /dev/null 2>&1

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. ✅ QUICK TEST COMMANDS
```bash
# Test subscription command
php artisan subscriptions:check-expiration

# Test transaction cleanup
php artisan transactions:cleanup-pending

# View logs in real-time
tail -f storage/logs/laravel.log

# Test database
php artisan tinker
>>> App\Models\PaymentTransaction::latest()->first();
>>> App\Models\Subscription::latest()->first();
```

## IMPORTANT REQUIREMENTS:
- ✅ Keep French language in UI messages
- ✅ Maintain existing CuniApp design system styling
- ✅ Ensure CSRF token is properly handled in AJAX
- ✅ Keep error handling robust with user-friendly messages
- ✅ Fix the Sanctum middleware issue (either install or remove)
- ✅ Provide COMPLETE code blocks (not snippets)

## VERIFICATION STEPS AFTER FIX:
```bash
# Check middleware is registered
php artisan route:list | grep "subscription"

# Check routes are protected
php artisan route:list | grep "males"

# Verify FedaPay config
php artisan tinker
>>> config('services.fedapay.secret_key')
>>> App\Models\Setting::get('fedapay_environment')

# Check storage permissions
ls -la storage/app/invoices/
```

## TROUBLESHOOTING GUIDE:

| Issue | Solution |
|-------|----------|
| **JSON shown instead of redirect** | Ensure AJAX form submission with proper response handling |
| **CSRF token error** | Check `<meta name="csrf-token">` in layout |
| **FedaPay API fails** | Verify keys in `.env` and `Settings` table |
| **Invoice not generated** | Check `storage/app/invoices` permissions |
| **Email not sent** | Verify SMTP config in `.env` |
| **Middleware not working** | Clear cache: `php artisan config:clear` |
| **Sanctum class not found** | Install Sanctum OR remove middleware line |
| **Phone validation fails** | Check regex pattern in JavaScript |
```

---

## 🎯 SUMMARY OF WHAT NEEDS TO BE FIXED:

1. **Sanctum Middleware Issue** - The class `\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class` doesn't exist because Sanctum isn't installed. Either:
   - Run `composer require laravel/sanctum` and publish config, OR
   - Remove that line from `bootstrap/app.php` if API stateful requests aren't needed

2. **Payment Form AJAX** - Update `payment/initiate.blade.php` to handle JSON responses properly instead of standard form submission

3. **Complete Testing** - Follow the 8-phase testing checklist to verify everything works end-to-end

Please provide the complete fixed code files and confirmation that all tests pass! 🚀