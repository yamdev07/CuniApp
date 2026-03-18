<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionRenewalReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Subscription $subscription;
    public int $daysUntilExpiry;

    public function __construct(Subscription $subscription, int $daysUntilExpiry)
    {
        $this->subscription = $subscription;
        $this->daysUntilExpiry = $daysUntilExpiry;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔔 Rappel: Abonnement expire dans ' . $this->daysUntilExpiry . ' jours')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre abonnement arrive à expiration dans ' . $this->daysUntilExpiry . ' jours.')
            ->line('Plan actuel: ' . $this->subscription->plan->name)
            ->line('Date d\'expiration: ' . $this->subscription->end_date->format('d/m/Y'))
            ->action('Renouveler maintenant', route('subscription.plans'))
            ->line('Renouvelez dès maintenant pour éviter toute interruption de service.')
            ->salutation('L\'équipe CuniApp Élevage');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'warning',
            'title' => '🔔 Rappel de Renouvellement',
            'message' => 'Votre abonnement expire dans ' . $this->daysUntilExpiry . ' jours. Renouvelez maintenant.',
            'action_url' => route('subscription.plans'),
            'subscription_id' => $this->subscription->id,
            'days_remaining' => $this->daysUntilExpiry,
        ];
    }
}
