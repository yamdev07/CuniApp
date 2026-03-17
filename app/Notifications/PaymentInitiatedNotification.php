<?php

namespace App\Notifications;

use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentInitiatedNotification extends Notification implements ShouldQueue
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
            ->subject('🕐 Paiement en Cours - CuniApp Élevage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre paiement a été initié avec succès.')
            ->line('Montant: ' . number_format($this->transaction->amount, 0, ',', ' ') . ' FCFA')
            ->line('Transaction ID: ' . $this->transaction->transaction_id)
            ->line('Veuillez finaliser le paiement sur la page de FedaPay.')
            ->action('Finaliser le paiement', route('payment.initiate', $this->transaction->transaction_id))
            ->line('Ce lien expire dans 30 minutes.')
            ->salutation('L\'équipe CuniApp Élevage');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'info',
            'title' => '🕐 Paiement en Cours',
            'message' => 'Votre paiement de ' . number_format($this->transaction->amount, 0, ',', ' ') . ' FCFA est en attente de finalisation.',
            'action_url' => route('payment.initiate', $this->transaction->transaction_id),
            'transaction_id' => $this->transaction->transaction_id,
        ];
    }
}
