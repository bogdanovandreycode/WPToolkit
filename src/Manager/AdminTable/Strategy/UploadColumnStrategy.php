<?php

namespace WpToolKit\Manager\AdminTable\Strategy;

use WpToolKit\Interface\AdminTable\TableColumnStrategyInterface;
use WP_Query;

class UploadColumnStrategy implements TableColumnStrategyInterface
{
    public function getHookNames(string $screenId): array
    {
        return [
            'columns' => 'manage_media_columns',
            'render' => 'manage_media_custom_column',
            'render_args' => 2,
            'sortable' => null,
            'sort_action' => null,
        ];
    }

    public function extractId(string $column, array $args): int|string|null
    {
        return $args[0] ?? null;
    }

    public function handleSortableQuery($query, array $sortableColumns): void
    {
        // Аналогично — не поддерживается или обрабатывается через media custom logic
    }
}
