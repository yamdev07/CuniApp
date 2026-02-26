<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProfileUpdatedNotification extends Notification
{
    public function via($notifiable) { return ['mail']; }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(' Sécurité : Votre profil CuniApp a été mis à jour')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous vous informons que les informations de votre compte ont été modifiées.')
            ->line('Si vous êtes à l\'origine de cette modification, aucune action n\'est requise.')
            ->action('Voir mon profil', url('/profile'))
            ->line('Si vous n\'avez pas demandé ce changement, veuillez réinitialiser votre mot de passe immédiatement.')
            ->salutation('L\'équipe CuniApp');
    }
}



