<?php
// app/Notifications/SubscriptionExpiredNotification.php
namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiredNotification extends Notification implements ShouldQueue
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
            ->subject('❌ Abonnement Expiré - CuniApp Élevage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre abonnement est arrivé à expiration.')
            ->line('Plan expiré: ' . $this->subscription->plan->name)
            ->line('Date d\'expiration: ' . $this->subscription->end_date->format('d/m/Y'))
            ->line('Certaines fonctionnalités de l\'application ne sont plus accessibles.')
            ->line('Pour retrouver un accès complet, veuillez souscrire à un nouvel abonnement.')
            ->action('Voir les abonnements', url('/subscription/plans'))
            ->line('Nous espérons vous revoir bientôt !')
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
            'type' => 'error',
            'title' => '❌ Abonnement Expiré',
            'message' => 'Votre abonnement est expiré. Veuillez le renouveler pour retrouver un accès complet.',
            'action_url' => route('subscription.plans'),
            'subscription_id' => $this->subscription->id,
            'end_date' => $this->subscription->end_date->format('d/m/Y'),
        ];
    }
}