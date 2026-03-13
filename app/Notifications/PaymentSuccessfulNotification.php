<?php
// app/Notifications/PaymentSuccessfulNotification.php (already exists - verify namespace)
namespace App\Notifications;

use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessfulNotification extends Notification implements ShouldQueue
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
            ->subject('✅ Paiement Réussi - CuniApp Élevage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre paiement a été traité avec succès !')
            ->line('Montant: ' . number_format($this->transaction->amount, 0, ',', ' ') . ' FCFA')
            ->line('Méthode: ' . strtoupper($this->transaction->payment_method))
            ->line('Transaction ID: ' . $this->transaction->transaction_id)
            ->action('Voir mon abonnement', url('/subscription/status'))
            ->line('Merci pour votre confiance !')
            ->salutation('L\'équipe CuniApp Élevage');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'success',
            'title' => '✅ Paiement Réussi',
            'message' => 'Votre paiement de ' . number_format($this->transaction->amount, 0, ',', ' ') . ' FCFA a été traité avec succès.',
            'action_url' => route('subscription.status'),
            'transaction_id' => $this->transaction->transaction_id,
        ];
    }
}
