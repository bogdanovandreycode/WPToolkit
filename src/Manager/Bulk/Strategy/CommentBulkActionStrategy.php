<?php

namespace WpToolKit\Manager\Bulk\Strategy;

use WpToolKit\Interface\Bulk\BulkStrategyInterface;

class CommentBulkActionStrategy implements BulkStrategyInterface
{
    public function getRegisterHook(string $screenId): string
    {
        return 'bulk_actions-edit-comments';
    }

    public function getHandleHook(string $screenId): string
    {
        return 'handle_bulk_actions-edit-comments';
    }

    public function isApplicable(string $screenId, ?string $postType): bool
    {
        return $screenId === 'edit-comments';
    }
}
