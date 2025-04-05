<?php

namespace WpToolKit\Manager\AdminTable\Strategy;

use WpToolKit\Interface\AdminTable\TableColumnStrategyInterface;

class PostColumnStrategy implements TableColumnStrategyInterface
{
    public function getHookNames(string $screenId): array
    {
        return [
            'columns' => "manage_{$screenId}_posts_columns",
            'render' => "manage_{$screenId}_posts_custom_column",
            'render_args' => 2,
            'sortable' => "manage_edit-{$screenId}_sortable_columns",
            'sort_action' => 'pre_get_posts',
        ];
    }

    public function extractId(string $column, array $args): int|string|null
    {
        return $args[0] ?? null;
    }

    public function handleSortableQuery($query, array $sortableColumns): void
    {
        foreach ($sortableColumns as $column => $metaKey) {
            if ($query->get('orderby') === $column) {
                $query->set('meta_key', $metaKey);
                $query->set('orderby', 'meta_value');
            }
        }
    }
}
