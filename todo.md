=============================================================================
CUNIAPP ÉLEVAGE - SUBSCRIPTION & PAYMENT SYSTEM IMPLEMENTATION
=============================================================================

OBJECTIVE:
Implement a mandatory subscription system where users must pay before 
accessing app functionality. Two user types: Regular Users & Administrators.

PRICING:
- Base: 2,500 FCFA/month
- 1 Month: 2,500 FCFA
- 3 Months: 7,500 FCFA
- 6 Months: 15,000 FCFA
- 1 Year (12 Months): 30,000 FCFA

PAYMENT METHODS:
- MTN MoMo (Benin)
- Celtis Cash
- Moov Pay

ACCESS CONTROL:
- Unsubscribed users can NAVIGATE but CANNOT perform CRUD actions
- Redirect to subscription page when attempting protected actions
- Admin users have full access + subscription management panel

=============================================================================
IMPLEMENTATION STEPS (VERIFY & IMPLEMENT IN ORDER)
=============================================================================

STEP 1: DATABASE MIGRATIONS
-----------------------------------------------------------------------------
□ 1.1 Create 'subscriptions' table migration
   - user_id (foreign key)
   - plan_type (enum: monthly, quarterly, semi_annual, annual)
   - price (decimal)
   - start_date (datetime)
   - end_date (datetime)
   - status (enum: active, expired, cancelled, pending)
   - payment_method (enum: momo, celtis, moov)
   - transaction_id (string, unique)
   - payment_reference (string)
   - created_at, updated_at

□ 1.2 Create 'subscription_plans' table migration
   - name (string)
   - duration_months (integer)
   - price (decimal)
   - is_active (boolean)
   - created_at, updated_at

□ 1.3 Create 'payment_transactions' table migration
   - user_id (foreign key)
   - subscription_id (foreign key, nullable)
   - amount (decimal)
   - payment_method (enum)
   - transaction_id (string, unique)
   - status (enum: pending, completed, failed, refunded)
   - provider_response (json)
   - created_at, updated_at

□ 1.4 Add 'role' column to users table migration
   - role (enum: user, admin) default 'user'

□ 1.5 Add 'subscription_status' to users table migration
   - subscription_status (enum: active, inactive, expired) default 'inactive'
   - subscription_ends_at (datetime, nullable)

STEP 2: MODELS
-----------------------------------------------------------------------------
□ 2.1 Create Subscription model
   - Fillable fields
   - Relationships (belongsTo User)
   - Scopes (active, expired, pending)
   - Accessors (is_active, days_remaining, is_expired)

□ 2.2 Create SubscriptionPlan model
   - Fillable fields
   - Static methods to get plans
   - Price calculation helpers

□ 2.3 Create PaymentTransaction model
   - Fillable fields
   - Relationships (belongsTo User, belongsTo Subscription)
   - Status helpers

□ 2.4 Update User model
   - Add role field to fillable
   - Add subscription_status to fillable
   - Add subscription_ends_at to casts
   - Add relationships (hasMany Subscription, hasMany PaymentTransaction)
   - Add methods: hasActiveSubscription(), isSubscribed(), isAdmin()

STEP 3: MIDDLEWARE
-----------------------------------------------------------------------------
□ 3.1 Create 'CheckSubscription' middleware
   - Check if user has active subscription
   - Allow navigation routes
   - Block CRUD action routes
   - Redirect to subscription page with intended URL

□ 3.2 Create 'CheckAdminRole' middleware
   - Verify user role is 'admin'
   - Return 403 if not admin

□ 3.3 Register middleware in Kernel.php or bootstrap/app.php

STEP 4: CONTROLLERS
-----------------------------------------------------------------------------
□ 4.1 Create SubscriptionController
   - index() - Show available plans
   - create() - Show subscription form
   - store() - Process subscription request
   - show() - Show subscription details
   - renew() - Renew existing subscription
   - cancel() - Cancel subscription

□ 4.2 Create PaymentController
   - initiate() - Start payment process
   - callback() - Handle payment provider callback
   - verify() - Verify payment status
   - webhook() - Handle payment webhooks

□ 4.3 Create Admin/SubscriptionManagementController
   - index() - List all users with subscription status
   - show() - View user subscription details
   - activate() - Manually activate subscription
   - deactivate() - Deactivate subscription
   - extend() - Extend subscription period

□ 4.4 Update existing Controllers (add subscription check)
   - MaleController
   - FemelleController
   - SaillieController
   - MiseBasController
   - NaissanceController
   - SaleController
   - LapinController
   (Add middleware or check in constructor)

STEP 5: ROUTES
-----------------------------------------------------------------------------
□ 5.1 Add subscription routes (auth middleware)
   - GET /subscription - View plans
   - GET /subscription/choose - Select plan
   - POST /subscription/purchase - Purchase plan
   - GET /subscription/status - Check status
   - POST /subscription/cancel - Cancel

□ 5.2 Add payment routes
   - POST /payment/initiate - Start payment
   - GET /payment/callback/{provider} - Payment callback
   - POST /payment/webhook/{provider} - Webhook handler

□ 5.3 Add admin routes (admin middleware)
   - GET /admin/subscriptions - Manage all subscriptions
   - GET /admin/users - View all users
   - POST /admin/subscriptions/{id}/activate
   - POST /admin/subscriptions/{id}/deactivate

□ 5.4 Apply CheckSubscription middleware to CRUD routes

STEP 6: VIEWS/UI
-----------------------------------------------------------------------------
□ 6.1 Create subscription plans view (resources/views/subscription/plans.blade.php)
   - Display 4 plan cards (1, 3, 6, 12 months)
   - Price display in FCFA
   - "Subscribe" buttons
   - Current subscription status display

□ 6.2 Create payment modal/view
   - Select payment method (MoMo, Celtis, Moov)
   - Phone number input
   - Payment confirmation
   - Loading state

□ 6.3 Create subscription status page
   - Current plan details
   - Days remaining
   - Renewal button
   - Payment history

□ 6.4 Create admin subscription management view
   - User list with subscription status
   - Filter by status (active, expired, none)
   - Quick actions (activate, extend, view)

□ 6.5 Update layout to show subscription status in header
   - Badge showing subscription status
   - Warning if expiring soon
   - Link to subscription page

□ 6.6 Create "Subscription Required"拦截 page
   - Shown when user tries CRUD without subscription
   - Explain why access is blocked
   - Direct link to subscribe
   - Keep navigation accessible

STEP 7: PAYMENT API INTEGRATION
-----------------------------------------------------------------------------
□ 7.1 Create PaymentService interface
   - initiatePayment()
   - verifyPayment()
   - refundPayment()

□ 7.2 Create MTN MoMo Payment Provider class
   - API credentials from settings
   - Request formatting
   - Response handling
   - Error handling

□ 7.3 Create Celtis Cash Payment Provider class
   - Same structure as MoMo

□ 7.4 Create Moov Pay Payment Provider class
   - Same structure as MoMo

□ 7.5 Create PaymentFactory for provider selection

□ 7.6 Store payment provider credentials in Settings table
   - momo_api_key, momo_api_secret
   - celtis_api_key, celtis_api_secret
   - moov_api_key, moov_api_secret

STEP 8: NOTIFICATIONS & EMAILS
-----------------------------------------------------------------------------
□ 8.1 Create SubscriptionActivated notification
□ 8.2 Create SubscriptionExpiringSoon notification (7 days before)
□ 8.3 Create SubscriptionExpired notification
□ 8.4 Create PaymentSuccessful notification
□ 8.5 Create PaymentFailed notification
□ 8.6 Create email templates for each notification

STEP 9: ADMIN PANEL ENHANCEMENTS
-----------------------------------------------------------------------------
□ 9.1 Add Admin navigation section (separate from user nav)
□ 9.2 Create Admin Dashboard with subscription metrics
   - Total active subscriptions
   - Revenue this month
   - Expiring soon count
   - New subscriptions graph

□ 9.3 Add user subscription management to Admin panel
□ 9.4 Add payment transaction logs view
□ 9.5 Add revenue reports/export

STEP 10: TESTING & VALIDATION
-----------------------------------------------------------------------------
□ 10.1 Create Feature Tests for subscription flow
□ 10.2 Create Feature Tests for payment flow
□ 10.3 Create Feature Tests for access control
□ 10.4 Create Feature Tests for admin functions
□ 10.5 Test all payment provider integrations
□ 10.6 Test middleware blocking behavior
□ 10.7 Test subscription expiration handling

=============================================================================
PRIORITY ORDER (IMPLEMENT IN THIS SEQUENCE)
=============================================================================

PHASE 1 - CORE INFRASTRUCTURE (Steps 1-3)
  → Migrations, Models, Middleware
  → Foundation for everything else

PHASE 2 - USER FLOW (Steps 4-6)
  → Controllers, Routes, Views
  → Users can subscribe and pay

PHASE 3 - PAYMENT INTEGRATION (Step 7)
  → Payment provider APIs
  → Actual money processing

PHASE 4 - ADMIN & MONITORING (Steps 8-9)
  → Admin panel, Notifications
  → Management & alerts

PHASE 5 - TESTING (Step 10)
  → Ensure everything works
  → Security validation

=============================================================================
VERIFICATION CHECKLIST FOR EACH STEP
=============================================================================

For each step above, verify:
□ Does this exist in current codebase?
□ If yes, does it meet requirements?
□ If no, create implementation
□ Test the implementation
□ Document any dependencies

=============================================================================
ADDITIONAL REQUIREMENTS
=============================================================================

1. GRACE PERIOD: Allow 3 days grace period after expiration
2. AUTO-RENEWAL: Option for users to enable auto-renewal
3. PAYMENT HISTORY: Users can view all past payments
4. INVOICES: Generate PDF invoices for payments
5. MULTI-CURRENCY: Store prices in FCFA but support conversion
6. WEBHOOK SECURITY: Verify webhook signatures from payment providers
7. RATE LIMITING: Prevent payment spam/abuse
8. AUDIT LOG: Log all subscription changes for admin review
9. SOFT DELETE: Don't hard delete subscriptions (keep records)
10. BACKUP PAYMENT: Allow manual payment confirmation by admin

=============================================================================
END OF IMPLEMENTATION PLAN
=============================================================================
```

---

## 🎯 HOW TO USE THIS PROMPT

**Your workflow should be:**

1. **Give me the prompt above** and say: *"Verify Step 1 (Database Migrations) against my codebase"*
2. **I'll check** if migrations exist, if they match requirements
3. **I'll create** missing migrations with proper structure
4. **Then you say**: *"Verify Step 2 (Models)"*
5. **Repeat** until all steps are complete

---

## ⚡ QUICK START COMMAND

To begin, just tell me:

```
"Start implementing the subscription system. Begin with Step 1: Database Migrations. 
Check what exists and create what's missing."
```

---

## 📊 ESTIMATED TIMELINE

| Phase | Steps | Estimated Time |
|-------|-------|----------------|
| Phase 1 | 1-3 | 2-3 hours |
| Phase 2 | 4-6 | 4-5 hours |
| Phase 3 | 7 | 6-8 hours (API integration) |
| Phase 4 | 8-9 | 3-4 hours |
| Phase 5 | 10 | 2-3 hours |
| **Total** | **All** | **~20 hours** |

---

## 🔐 SECURITY CONSIDERATIONS

1. **Webhook Verification** - Always verify payment provider signatures
2. **Idempotency** - Prevent duplicate payment processing
3. **User Authorization** - Users can only view/modify their own subscriptions
4. **Admin Protection** - Admin routes must be heavily protected
5. **Payment Data** - Never store sensitive payment info (use tokens)
6. **HTTPS Required** - All payment routes must use HTTPS in production