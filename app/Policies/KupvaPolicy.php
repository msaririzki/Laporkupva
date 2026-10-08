<?php

namespace App\Policies;

use App\Models\Kupva;
use App\Models\User;
use Filament\Facades\Filament;

class KupvaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessPanel(Filament::getPanel('admin'));
    }

    public function view(User $user, Kupva $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->canManageApplication();
    }

    public function update(User $user, Kupva $record): bool
    {
        return $user->canManageApplication();
    }

    public function delete(User $user, Kupva $record): bool
    {
        return $user->canManageApplication();
    }

    public function deleteAny(User $user): bool
    {
        return $user->canManageApplication();
    }

    public function restore(User $user, Kupva $record): bool
    {
        return $user->canManageApplication();
    }

    public function forceDelete(User $user, Kupva $record): bool
    {
        return $user->canManageApplication();
    }
}
