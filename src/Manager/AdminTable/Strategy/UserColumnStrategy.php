<?php

namespace WpToolKit\Manager\AdminTable\Strategy;

use WpToolKit\Interface\AdminTable\TableColumnStrategyInterface;
use WP_User_Query;

class UserColumnStrategy implements TableColumnStrategyInterface
{
    public function getHookNames(string $screenId): array
    {
        return [
            'columns' => 'manage_users_columns',
            'render' => 'manage_users_custom_column',
            'render_args' => 3,
            'sortable' => 'manage_users_sortable_columns',
            'sort_action' => 'pre_get_users',
        ];
    }

    public function extractId(string $column, array $args): int|string|null
    {
        return $args[1] ?? null;
    }

    public function handleSortableQuery($query, array $sortableColumns): void
    {
        if (!$query instanceof WP_User_Query) {
            return;
        }

        foreach ($sortableColumns as $column => $metaKey) {
            if (isset($_GET['orderby']) && $_GET['orderby'] === $column) {
                $query->query_vars['meta_key'] = $metaKey;
                $query->query_vars['orderby'] = 'meta_value';
            }
        }
    }
}
