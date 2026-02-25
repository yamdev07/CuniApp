<?php
namespace App\Traits;

use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivityNotificationMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

trait Notifiable
{
    public function notifyUser(array $data): Notification
    {
        $notification = Notification::create([
            'user_id' => $data['user_id'] ?? auth()->id(),
            'type' => $data['type'] ?? 'info',
            'title' => $data['title'],
            'message' => $data['message'],
            'action_url' => $data['action_url'] ?? null,
            'icon' => $this->getIconForType($data['type'] ?? 'info'),
        ]);

        // Send email if enabled
        $user = $notification->user;
        $emailEnabled = Setting::get('notifications_email', '0') === '1';
        
        if ($emailEnabled && !$notification->emailed) {
            try {
                Mail::to($user->email)->send(new ActivityNotificationMail($notification));
                $notification->update(['emailed' => true]);
            } catch (\Exception $e) {
                Log::error('Failed to send notification email: ' . $e->getMessage());
            }
        }

        return $notification;
    }

    private function getIconForType(string $type): string
    {
        return match($type) {
            'success' => 'bi-check-circle-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            'error' => 'bi-x-circle-fill',
            'info' => 'bi-info-circle-fill',
            default => 'bi-bell-fill'
        };
    }
}