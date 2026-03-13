<?php
// app/Notifications/SubscriptionActivatedNotification.php
namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionActivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Subscription $subscription;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Abonnement Activé - CuniApp Élevage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre abonnement a été activé avec succès !')
            ->line('Plan: ' . $this->subscription->plan->name)
            ->line('Durée: ' . $this->subscription->plan->duration_months . ' mois')
            ->line('Date d\'expiration: ' . $this->subscription->end_date->format('d/m/Y'))
            ->action('Voir mon abonnement', url('/subscription/status'))
            ->line('Merci pour votre confiance !')
            ->salutation('L\'équipe CuniApp Élevage');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'success',
            'title' => '✅ Abonnement Activé',
            'message' => 'Votre abonnement ' . $this->subscription->plan->name . ' a été activé avec succès.',
            'action_url' => route('subscription.status'),
            'subscription_id' => $this->subscription->id,
            'end_date' => $this->subscription->end_date->format('d/m/Y'),
        ];
    }
}
