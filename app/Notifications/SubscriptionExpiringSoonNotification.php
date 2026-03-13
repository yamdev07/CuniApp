<?php
// app/Notifications/SubscriptionExpiringSoonNotification.php
namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringSoonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Subscription $subscription;
    public int $daysRemaining;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription, int $daysRemaining)
    {
        $this->subscription = $subscription;
        $this->daysRemaining = $daysRemaining;
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
            ->subject('⚠️ Abonnement Bientôt Expiré - CuniApp Élevage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre abonnement arrive à expiration dans ' . $this->daysRemaining . ' jours.')
            ->line('Plan actuel: ' . $this->subscription->plan->name)
            ->line('Date d\'expiration: ' . $this->subscription->end_date->format('d/m/Y'))
            ->line('Pour éviter toute interruption de service, veuillez renouveler votre abonnement.')
            ->action('Renouveler maintenant', url('/subscription/plans'))
            ->line('Si vous avez des questions, n\'hésitez pas à contacter notre support.')
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
            'type' => 'warning',
            'title' => '⚠️ Abonnement Bientôt Expiré',
            'message' => 'Votre abonnement expire dans ' . $this->daysRemaining . ' jours. Pensez à le renouveler.',
            'action_url' => route('subscription.plans'),
            'subscription_id' => $this->subscription->id,
            'days_remaining' => $this->daysRemaining,
            'end_date' => $this->subscription->end_date->format('d/m/Y'),
        ];
    }
}