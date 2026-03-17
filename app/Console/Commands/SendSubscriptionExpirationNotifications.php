<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Notifications\SubscriptionExpiringSoonNotification;
use App\Notifications\SubscriptionExpiredNotification;
use App\Notifications\SubscriptionRenewalReminderNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendSubscriptionExpirationNotifications extends Command
{
    protected $signature = 'subscriptions:check-expiration';
    protected $description = 'Check for expiring/expired subscriptions and send notifications';

    public function handle(): int
    {
        $now = Carbon::now();
        $count = 0;

        // ✅ 7 days before expiration
        $expiringIn7Days = Subscription::where('status', 'active')
            ->whereBetween('end_date', [$now->copy()->addDays(7), $now->copy()->addDays(8)])
            ->with(['user', 'plan'])
            ->get();

        foreach ($expiringIn7Days as $subscription) {
            $subscription->user->notify(new SubscriptionExpiringSoonNotification($subscription, 7));
            $count++;
            $this->info("7-day expiration warning sent to {$subscription->user->email}");
        }

        // ✅ 3 days before expiration
        $expiringIn3Days = Subscription::where('status', 'active')
            ->whereBetween('end_date', [$now->copy()->addDays(3), $now->copy()->addDays(4)])
            ->with(['user', 'plan'])
            ->get();

        foreach ($expiringIn3Days as $subscription) {
            $subscription->user->notify(new SubscriptionExpiringSoonNotification($subscription, 3));
            $count++;
            $this->info("3-day expiration warning sent to {$subscription->user->email}");
        }

        // ✅ 1 day before expiration
        $expiringIn1Day = Subscription::where('status', 'active')
            ->whereBetween('end_date', [$now->copy()->addDay(), $now->copy()->addDays(2)])
            ->with(['user', 'plan'])
            ->get();

        foreach ($expiringIn1Day as $subscription) {
            $subscription->user->notify(new SubscriptionExpiringSoonNotification($subscription, 1));
            $count++;
            $this->info("1-day expiration warning sent to {$subscription->user->email}");
        }

        // ✅ Check for expired subscriptions
        $expired = Subscription::where('status', 'active')
            ->where('end_date', '<', $now)
            ->with(['user', 'plan'])
            ->get();

        foreach ($expired as $subscription) {
            // Update subscription status
            $subscription->update(['status' => 'expired']);

            // Update user subscription status
            $subscription->user->update([
                'subscription_status' => 'expired',
                'subscription_ends_at' => $subscription->end_date,
            ]);

            // ✅ Send expiration notification
            $subscription->user->notify(new SubscriptionExpiredNotification($subscription));
            $count++;
            $this->info("Expiration notification sent to {$subscription->user->email}");
        }

        $this->info("Expiration check complete. {$count} notifications sent.");
        return Command::SUCCESS;
    }
}
