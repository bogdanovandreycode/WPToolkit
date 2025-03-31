<?php

namespace WpToolKit\Controller;

use WP_Post;
use WP_Query;
use WpToolKit\Entity\Post;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Factory\ServiceFactory;

class PostController
{
    private array $customColumns = [];
    private array $sortableColumns = [];
    private array $columnOrder = [];

    public function __construct(private Post $post)
    {
        add_action('init', function () {
            register_post_type(
                $this->post->name,
                [
                    'public' => $this->post->public,
                    'label'  => $this->post->title,
                    'menu_icon' => $this->post->icon,
                    'supports' => $this->post->supports,
                    'show_in_menu' => $this->post->getUrl(),
                    'menu_position' => $this->post->position,
                    'show_in_rest' => $this->post->rest
                ]
            );
        });

        add_filter('the_content', [$this, 'renderContent']);
        add_filter("manage_{$this->post->name}_posts_columns", [$this, 'addCustomColumns']);
        add_action("manage_{$this->post->name}_posts_custom_column", [$this, 'renderCustomColumn'], 10, 2);
        add_filter("manage_edit-{$this->post->name}_sortable_columns", [$this, 'addSortableColumns']);
        add_action('pre_get_posts', [$this, 'handleSortableQuery']);
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function addToMenu(): void
    {
        $menu = ServiceFactory::getService('MenuController');

        $menu->addItem(
            $this->post->title,
            $this->post->title,
            $this->post->role,
            $this->post->getUrl(),
            '',
            $this->post->icon,
            $this->post->position
        );
    }

    public function addToSubMenu(Post $parentPost): void
    {
        $menu = ServiceFactory::getService('MenuController');

        $menu->addSubItem(
            $parentPost->getUrl(),
            $this->post->title,
            $this->post->title,
            $this->post->role,
            $this->post->getUrl(),
            '',
            $this->post->position
        );
    }

    public function addMetaPoly(MetaPoly $metaPoly)
    {
        register_post_meta(
            $this->post->name,
            $metaPoly->name,
            [
                'single' => $metaPoly->single,
                'show_in_rest' => $metaPoly->showInRest,
                'type' => $metaPoly->type->value,
            ]
        );
    }

    /**
     * Добавление новой колонки в админке
     *
     * @param string $key — ключ колонки
     * @param string $label — заголовок
     * @param callable $callback — функция отображения
     */
    public function addAdminColumn(string $key, string $label, callable $callback): void
    {
        $this->customColumns[$key] = [
            'label' => $label,
            'callback' => $callback
        ];
    }

    /**
     * Добавление колонок в список
     */
    public function addCustomColumns(array $columns): array
    {
        // Вставим кастомные колонки в исходный массив
        foreach ($this->customColumns as $key => $data) {
            $columns[$key] = $data['label'];
        }

        // Если порядок не задан — вернём как есть
        if (empty($this->columnOrder)) {
            return $columns;
        }

        // Сортируем колонки по заданному порядку
        $ordered = [];

        foreach ($this->columnOrder as $key) {
            if (isset($columns[$key])) {
                $ordered[$key] = $columns[$key];
                unset($columns[$key]);
            }
        }

        // Добавляем оставшиеся (не включённые в порядок)
        return array_merge($ordered, $columns);
    }

    /**
     * Отрисовка значений в колонках
     */
    public function renderCustomColumn(string $column, int $postId): void
    {
        if (isset($this->customColumns[$column])) {
            $value = call_user_func($this->customColumns[$column]['callback'], $postId);
            echo esc_html($value);
        }
    }

    public function renderContent($content)
    {
        return $content;
    }

    /**
     * Добавляет колонку, по которой можно сортировать
     */
    public function addSortableAdminColumn(string $key, string $metaKey): void
    {
        $this->sortableColumns[$key] = $metaKey;
    }

    public function addSortableColumns(array $columns): array
    {
        return array_merge($columns, $this->sortableColumns);
    }

    public function handleSortableQuery(WP_Query $query): void
    {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== $this->post->name) {
            return;
        }

        foreach ($this->sortableColumns as $column => $metaKey) {
            if ($query->get('orderby') === $column) {
                $query->set('meta_key', $metaKey);
                $query->set('orderby', 'meta_value');
            }
        }
    }

    public function setColumnOrder(array $order): void
    {
        $this->columnOrder = $order;
    }
}
