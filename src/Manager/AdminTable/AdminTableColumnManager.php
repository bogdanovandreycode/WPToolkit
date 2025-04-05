<?php

namespace WpToolKit\Manager\AdminTable;

use WpToolKit\Interface\AdminTable\TableColumnStrategyInterface;

class AdminTableColumnManager
{
    private array $customColumns = [];
    private array $sortableColumns = [];
    private array $columnOrder = [];

    public function __construct(
        private string $screenId,
        private TableColumnStrategyInterface $strategy
    ) {
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
        $hooks = $this->strategy->getHookNames($this->screenId);

        add_filter($hooks['columns'], [$this, 'addColumns']);
        add_action($hooks['render'], [$this, 'renderColumn'], 10, $hooks['render_args']);

        if (!empty($hooks['sortable'])) {
            add_filter($hooks['sortable'], [$this, 'addSortableColumns']);
            add_action($hooks['sort_action'], [$this, 'handleSortableQuery']);
        }
    }

    public function addColumns(array $columns): array
    {
        foreach ($this->customColumns as $key => $data) {
            $columns[$key] = $data['label'];
        }

        if (empty($this->columnOrder)) return $columns;

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
        if (!isset($this->customColumns[$column])) {
            return;
        }

        $id = $this->strategy->extractId($column, $args);

        if (!$id) return;

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

        $this->strategy->handleSortableQuery($query, $this->sortableColumns);
    }
}
