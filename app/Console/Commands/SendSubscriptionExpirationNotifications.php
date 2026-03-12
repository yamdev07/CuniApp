<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Notifications\SubscriptionExpiringSoonNotification;
use App\Notifications\SubscriptionExpiredNotification;
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

        // Check for subscriptions expiring in 7 days
        $expiringSoon = Subscription::where('status', 'active')
            ->whereBetween('end_date', [$now, $now->copy()->addDays(7)])
            ->with(['user', 'plan'])
            ->get();

        foreach ($expiringSoon as $subscription) {
            $daysRemaining = $subscription->end_date->diffInDays($now, false);

            // Only send if not already notified in the last 24 hours
            $lastNotification = $subscription->user->notifications()
                ->where('type', 'warning')
                ->where('title', 'like', '%Bientôt Expiré%')
                ->where('created_at', '>=', $now->copy()->subDay())
                ->first();

            if (!$lastNotification) {
                $subscription->user->notify(new SubscriptionExpiringSoonNotification($subscription, $daysRemaining));
                $count++;
                $this->info("Expiration warning sent to {$subscription->user->email} ({$daysRemaining} jours restants)");
            }
        }

        // Check for expired subscriptions
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

            // Send notification
            $subscription->user->notify(new SubscriptionExpiredNotification($subscription));
            $count++;
            $this->info("Expiration notification sent to {$subscription->user->email}");
        }

        $this->info("Expiration check complete. {$count} notifications sent.");
        return Command::SUCCESS;
    }
}
