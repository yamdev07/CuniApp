<?php
// app/Console/Commands/CheckBirthVerification.php

namespace App\Console\Commands;

use App\Models\Naissance;
use App\Models\Notification;
use App\Traits\Notifiable;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Setting;

class CheckBirthVerification extends Command
{
    use Notifiable;

    protected $signature = 'births:check-verification';
    protected $description = 'Check for births needing sex/DOB verification and send notifications';

    public function handle(): int
    {
        $initialDelay = Setting::get('verification_initial_days', 10);
        $reminderDelay = Setting::get('verification_reminder_days', 15);
        $reminderInterval = Setting::get('verification_interval_days', 5);

        $now = Carbon::now();
        $initialThreshold = $now->copy()->subDays($initialDelay);
        $reminderThreshold = $now->copy()->subDays($reminderDelay);

        $count = 0;

        // Check births that need initial verification (10 days old, not verified)
        $pendingBirths = Naissance::where('sex_verified', false)
            ->where('date_naissance', '<=', $initialThreshold)
            ->where('is_archived', false)
            ->get();

        foreach ($pendingBirths as $naissance) {
            // Check if first reminder should be sent (15 days)
            if ($naissance->date_naissance <= $reminderThreshold && !$naissance->first_reminder_sent_at) {
                $this->sendVerificationNotification($naissance, 'initial');
                $naissance->first_reminder_sent_at = $now;
                $naissance->last_reminder_sent_at = $now;
                $naissance->reminder_count = 1;
                $naissance->save();
                $count++;
                $this->info("Initial reminder sent for birth #{$naissance->id}");
            }
            // Check for subsequent reminders (every 5 days after 15 days)
            elseif ($naissance->first_reminder_sent_at && $naissance->last_reminder_sent_at) {
                $daysSinceLastReminder = $naissance->last_reminder_sent_at->diffInDays($now);

                if ($daysSinceLastReminder >= $reminderInterval) {
                    $this->sendVerificationNotification($naissance, 'reminder');
                    $naissance->last_reminder_sent_at = $now;
                    $naissance->reminder_count++;
                    $naissance->save();
                    $count++;
                    $this->info("Reminder #{$naissance->reminder_count} sent for birth #{$naissance->id}");
                }
            }
        }

        $this->info("Verification check complete. {$count} notifications sent.");
        return Command::SUCCESS;
    }

    private function sendVerificationNotification(Naissance $naissance, string $type): void
    {
        $daysOld = $naissance->date_naissance->diffInDays(Carbon::now());

        $title = $type === 'initial'
            ? '⚠️ Vérification de Portée Requise'
            : '🔔 Rappel: Vérification de Portée en Attente';

        $message = $type === 'initial'
            ? "La portée de {$naissance->femelle->nom} ({$daysOld} jours) nécessite une vérification du sexe et de la date de naissance. Veuillez mettre à jour les informations."
            : "Rappel #{$naissance->reminder_count}: La portée de {$naissance->femelle->nom} attend toujours la vérification du sexe ({$daysOld} jours depuis la naissance).";

        $this->notifyUser([
            'user_id' => $naissance->user_id ?? 1,
            'type' => $type === 'initial' ? 'warning' : 'info',
            'title' => $title,
            'message' => $message,
            'action_url' => route('naissances.edit', $naissance),
        ]);
    }
}
