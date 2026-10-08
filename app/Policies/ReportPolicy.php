<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Filament\Facades\Filament;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessPanel(Filament::getPanel('admin'));
    }

    public function view(User $user, Report $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->canManageApplication();
    }

    public function update(User $user, Report $record): bool
    {
        return $user->canManageApplication();
    }

    public function delete(User $user, Report $record): bool
    {
        return $user->canManageApplication();
    }

    public function deleteAny(User $user): bool
    {
        return $user->canManageApplication();
    }

    public function restore(User $user, Report $record): bool
    {
        return $user->canManageApplication();
    }

    public function forceDelete(User $user, Report $record): bool
    {
        return $user->canManageApplication();
    }
}
