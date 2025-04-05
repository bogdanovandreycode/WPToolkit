<?php

namespace WpToolKit\Manager\AdminTable\Strategy;

use WpToolKit\Interface\AdminTable\TableColumnStrategyInterface;
use WP_Comment_Query;

class CommentColumnStrategy implements TableColumnStrategyInterface
{
    public function getHookNames(string $screenId): array
    {
        return [
            'columns' => 'manage_edit-comments_columns',
            'render' => 'manage_comments_custom_column',
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
        // Пока не поддерживается, сортировка в комментариях отдельно обрабатывается
    }
}
