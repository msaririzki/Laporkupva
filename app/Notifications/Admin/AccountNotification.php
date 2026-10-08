<?php

namespace App\Notifications\Admin;

use App\Enums\UserRole;
use App\Models\User;
use App\Notifications\MailNotification;

abstract class AccountNotification extends MailNotification
{
    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        if (! $notifiable instanceof User || ! $notifiable->canManageApplication()) {
            return [];
        }

        return $this->shouldSend($notifiable, 'mail')
            ? ['database', 'broadcast', 'mail']
            : ['database', 'broadcast'];
    }

    /** @return array<string, string> */
    public function viaConnections(): array
    {
        return ['database' => 'sync', 'broadcast' => 'sync'];
    }

    public function shouldSend(object $notifiable, string $channel): bool
    {
        return $notifiable instanceof User
            && $notifiable->canManageApplication()
            && ($channel !== 'mail' || (
                $notifiable->role === $this->mailRecipientRole()
                && filter_var($notifiable->email, FILTER_VALIDATE_EMAIL) !== false
            ));
    }

    abstract protected function mailRecipientRole(): UserRole;
}
