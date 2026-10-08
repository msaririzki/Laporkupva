<?php

namespace App\Policies;

use App\Models\ReportProgressRequest;
use App\Models\User;

class ReportProgressRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->isSuperAdmin();
    }

    public function view(User $user, ReportProgressRequest $request): bool
    {
        return $this->viewAny($user);
    }

    public function review(User $user, ReportProgressRequest $request): bool
    {
        return $this->viewAny($user) && $request->status === 'pending';
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ReportProgressRequest $request): bool
    {
        return false;
    }

    public function delete(User $user, ReportProgressRequest $request): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
