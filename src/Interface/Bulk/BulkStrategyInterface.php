<?php

namespace WpToolKit\Interface\Bulk;

interface BulkStrategyInterface
{
    public function getRegisterHook(string $screenId): string;
    public function getHandleHook(string $screenId): string;
    public function isApplicable(string $screenId, ?string $postType): bool;
}
