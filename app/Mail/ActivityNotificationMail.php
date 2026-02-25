<?php
namespace App\Mail;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivityNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Notification $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function build(): Mailable
    {
        $colors = [
            'success' => '#10b981',
            'warning' => '#f59e0b',
            'error' => '#ef4444',
            'info' => '#3b82f6'
        ];
        
        $color = $colors[$this->notification->type] ?? $colors['info'];
        
        return $this->subject("🔔 Notification CuniApp: {$this->notification->title}")
            ->view('emails.notification', [
                'notification' => $this->notification,
                'color' => $color
            ]);
    }
}