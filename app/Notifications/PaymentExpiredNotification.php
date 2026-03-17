<?php

namespace App\Notifications;

use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public PaymentTransaction $transaction;

    public function __construct(PaymentTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⏰ Paiement Expiré - CuniApp Élevage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre paiement n\'a pas été finalisé à temps.')
            ->line('Montant: ' . number_format($this->transaction->amount, 0, ',', ' ') . ' FCFA')
            ->line('Transaction ID: ' . $this->transaction->transaction_id)
            ->action('Réessayer le paiement', route('subscription.plans'))
            ->line('Vous pouvez initier un nouveau paiement depuis votre espace abonnement.')
            ->salutation('L\'équipe CuniApp Élevage');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'warning',
            'title' => '⏰ Paiement Expiré',
            'message' => 'Votre paiement a expiré. Veuillez initier un nouveau paiement.',
            'action_url' => route('subscription.plans'),
            'transaction_id' => $this->transaction->transaction_id,
        ];
    }
}
