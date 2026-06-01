<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class OrganizationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('organization-view');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->can('organization-view');
    }

    public function create(User $user): bool
    {
        return $user->can('organization-create');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->can('organization-edit');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can('organization-delete') && $role->name !== 'Super Admin';
    }
}
