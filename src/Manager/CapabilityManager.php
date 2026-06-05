<?php

namespace WpToolKit\Manager;

use WP_User;

class CapabilityManager
{
    public function currentUserCan(string $capability, mixed ...$args): bool
    {
        return current_user_can($capability, ...$args);
    }

    public function userCan(WP_User|int $user, string $capability, mixed ...$args): bool
    {
        return user_can($user, $capability, ...$args);
    }

    public function getCurrentUserId(): int
    {
        return get_current_user_id();
    }

    public function getCurrentUser(): WP_User
    {
        return wp_get_current_user();
    }

    public function isLoggedIn(): bool
    {
        return is_user_logged_in();
    }

    public function roleHasCapability(string $role, string $capability): bool
    {
        $roleObject = get_role($role);

        if ($roleObject === null) {
            return false;
        }

        return $roleObject->has_cap($capability);
    }

    public function addCapabilityToRole(string $role, string $capability, bool $grant = true): void
    {
        $roleObject = get_role($role);

        if ($roleObject !== null) {
            $roleObject->add_cap($capability, $grant);
        }
    }

    public function removeCapabilityFromRole(string $role, string $capability): void
    {
        $roleObject = get_role($role);

        if ($roleObject !== null) {
            $roleObject->remove_cap($capability);
        }
    }
}
