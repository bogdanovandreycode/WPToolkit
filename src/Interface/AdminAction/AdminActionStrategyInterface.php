<?php

namespace WpToolKit\Interface\AdminAction;

interface AdminActionStrategyInterface
{
    public function getHookName(): string;
    public function isApplicable(string $screenId, ?string $postType, mixed $item): bool;
    public function getId(mixed $item): int|string|null;
}
