<?php

namespace WpToolKit\Manager\Bulk\Strategy;

use WpToolKit\Interface\Bulk\BulkStrategyInterface;

class UploadBulkActionStrategy implements BulkStrategyInterface
{
    public function getRegisterHook(string $screenId): string
    {
        return 'bulk_actions-upload';
    }

    public function getHandleHook(string $screenId): string
    {
        return 'handle_bulk_actions-upload';
    }

    public function isApplicable(string $screenId, ?string $postType): bool
    {
        return $screenId === 'upload';
    }
}
