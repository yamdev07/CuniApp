<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📄 Votre Facture ' . $this->invoice->invoice_number)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre facture est maintenant disponible.')
            ->line('Numéro: ' . $this->invoice->invoice_number)
            ->line('Montant: ' . number_format($this->invoice->total_amount, 0, ',', ' ') . ' FCFA')
            ->line('Statut: ' . ucfirst($this->invoice->status))
            ->action('Télécharger la facture', route('invoices.download', $this->invoice))
            ->line('Merci pour votre confiance !')
            ->salutation('L\'équipe CuniApp Élevage');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'info',
            'title' => '📄 Nouvelle Facture Disponible',
            'message' => 'Facture ' . $this->invoice->invoice_number . ' - ' . number_format($this->invoice->total_amount, 0, ',', ' ') . ' FCFA',
            'action_url' => route('invoices.download', $this->invoice),
            'invoice_id' => $this->invoice->id,
        ];
    }
}
