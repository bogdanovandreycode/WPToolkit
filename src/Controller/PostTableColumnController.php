<?php

namespace WpToolKit\Controller;

use WP_Query;
use WP_User;
use WP_Post;

class PostTableColumnController
{
    private string $screenId;
    private string $type;
    private array $customColumns = [];
    private array $sortableColumns = [];
    private array $columnOrder = [];

    public function __construct(string $screenId, string $type = 'edit-post')
    {
        $this->screenId = $screenId;
        $this->type = $type;

        $this->registerHooks();
    }

    public function addColumn(string $key, string $label, callable $callback): void
    {
        $this->customColumns[$key] = compact('label', 'callback');
    }

    public function addSortableColumn(string $key, string $metaKey): void
    {
        $this->sortableColumns[$key] = $metaKey;
    }

    public function setColumnOrder(array $order): void
    {
        $this->columnOrder = $order;
    }

    private function registerHooks(): void
    {
        $hooks = $this->getHookNames();

        add_filter($hooks['columns'], [$this, 'addColumns']);
        add_action($hooks['render'], [$this, 'renderColumn'], 10, $hooks['render_args']);

        if ($hooks['sortable']) {
            add_filter($hooks['sortable'], [$this, 'addSortableColumns']);
            add_action($hooks['sort_action'], [$this, 'handleSortableQuery']);
        }
    }

    private function getHookNames(): array
    {
        switch ($this->type) {
            case 'edit-post':
                return [
                    'columns' => "manage_{$this->screenId}_posts_columns",
                    'render' => "manage_{$this->screenId}_posts_custom_column",
                    'render_args' => 2,
                    'sortable' => "manage_edit-{$this->screenId}_sortable_columns",
                    'sort_action' => 'pre_get_posts'
                ];

            case 'users':
                return [
                    'columns' => 'manage_users_columns',
                    'render' => 'manage_users_custom_column',
                    'render_args' => 3,
                    'sortable' => 'manage_users_sortable_columns',
                    'sort_action' => 'pre_get_users'
                ];

            case 'edit-comments':
                return [
                    'columns' => 'manage_edit-comments_columns',
                    'render' => 'manage_comments_custom_column',
                    'render_args' => 2,
                    'sortable' => null,
                    'sort_action' => null
                ];

            case 'upload':
                return [
                    'columns' => 'manage_media_columns',
                    'render' => 'manage_media_custom_column',
                    'render_args' => 2,
                    'sortable' => null,
                    'sort_action' => null
                ];

            default:
                throw new \InvalidArgumentException("Unknown type: {$this->type}");
        }
    }

    public function addColumns(array $columns): array
    {
        foreach ($this->customColumns as $key => $data) {
            $columns[$key] = $data['label'];
        }

        if (empty($this->columnOrder)) {
            return $columns;
        }

        $ordered = [];
        foreach ($this->columnOrder as $key) {
            if (isset($columns[$key])) {
                $ordered[$key] = $columns[$key];
                unset($columns[$key]);
            }
        }

        return array_merge($ordered, $columns);
    }

    public function renderColumn(string $column, ...$args): void
    {
        $id = $args[0] ?? null;

        if (!$id || !isset($this->customColumns[$column])) {
            return;
        }

        $value = call_user_func($this->customColumns[$column]['callback'], $id);
        echo esc_html($value);
    }

    public function addSortableColumns(array $columns): array
    {
        return array_merge($columns, $this->sortableColumns);
    }

    public function handleSortableQuery($query): void
    {
        if (!is_admin() || !$query->is_main_query()) return;

        foreach ($this->sortableColumns as $column => $metaKey) {
            if ($query->get('orderby') === $column) {
                $query->set('meta_key', $metaKey);
                $query->set('orderby', 'meta_value');
            }
        }
    }
}
