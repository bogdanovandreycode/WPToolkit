<?php

namespace WpToolKit\Manager\TableNav\Strategy;

use WpToolKit\Interface\TableNav\TableNavStrategyInterface;

class UserTableNavStrategy implements TableNavStrategyInterface
{
    public function getRenderHook(string $screenId): string
    {
        return 'restrict_manage_users';
    }

    public function getApplyHook(string $screenId): string
    {
        return 'pre_get_users';
    }

    public function isApplicable(string $screenId, ?string $postType): bool
    {
        return get_current_screen()->id === $screenId;
    }
}
