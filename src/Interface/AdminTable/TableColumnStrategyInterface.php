<?php

namespace WpToolKit\Interface\AdminTable;

interface TableColumnStrategyInterface
{
    public function getHookNames(string $screenId): array;
    public function extractId(string $column, array $args): int|string|null;
    public function handleSortableQuery($query, array $sortableColumns): void;
}
