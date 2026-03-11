<?php

namespace App\Notifications;

use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public PaymentTransaction $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct(PaymentTransaction $transaction)
    {
        $this->transaction = $transaction;
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
            ->subject('❌ Échec du Paiement - CuniApp Élevage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre paiement n\'a pas pu être traité.')
            ->line('Montant: ' . number_format($this->transaction->amount, 0, ',', ' ') . ' FCFA')
            ->line('Raison: ' . ($this->transaction->failure_reason ?? 'Non spécifiée'))
            ->action('Réessayer le paiement', url('/subscription/plans'))
            ->line('Si le problème persiste, contactez notre support.')
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
            'title' => '❌ Échec du Paiement',
            'message' => 'Votre paiement a échoué. Veuillez réessayer ou contacter le support.',
            'action_url' => route('subscription.plans'),
            'transaction_id' => $this->transaction->transaction_id,
        ];
    }
}
