<?php

namespace WpToolKit\Interface\TableNav;

interface TableNavStrategyInterface
{
    public function getRenderHook(string $screenId): string;
    public function getApplyHook(string $screenId): string;
    public function isApplicable(string $screenId, ?string $postType): bool;
}
