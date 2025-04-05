<?php

namespace WpToolKit\Manager\Bulk\Strategy;

use WpToolKit\Interface\Bulk\BulkStrategyInterface;

class UserBulkActionStrategy implements BulkStrategyInterface
{
    public function getRegisterHook(string $screenId): string
    {
        return "bulk_actions-{$screenId}";
    }

    public function getHandleHook(string $screenId): string
    {
        return "handle_bulk_actions-{$screenId}";
    }

    public function isApplicable(string $screenId, ?string $postType): bool
    {
        return $screenId === 'users';
    }
}
