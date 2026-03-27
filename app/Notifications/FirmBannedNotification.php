<?php
// app/Notifications/FirmBannedNotification.php
namespace App\Notifications;

use App\Models\Firm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FirmBannedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Firm $firm;

    public function __construct(Firm $firm)
    {
        $this->firm = $firm;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Votre Entreprise a été Suspendue - CuniApp Élevage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre entreprise ' . $this->firm->name . ' a été suspendue.')
            ->line('Raison: Veuillez contacter notre support pour plus d\'informations.')
            ->action('Contacter le Support', url('/contact'))
            ->line('Nous espérons résoudre cette situation rapidement.')
            ->salutation('L\'équipe CuniApp Élevage');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'error',
            'title' => '⚠️ Entreprise Suspendue',
            'message' => 'Votre entreprise ' . $this->firm->name . ' a été suspendue. Contactez le support.',
            'action_url' => route('contact'),
            'firm_id' => $this->firm->id,
        ];
    }
}
