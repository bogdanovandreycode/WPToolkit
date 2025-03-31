<?php

namespace WpToolKit\Controller;

use WP_Post;

/**
 * Контроллер для действий (ссылок) внутри строки записи
 */
class AdminActionController
{
    private string $postType;
    private array $actions = [];

    public function __construct(string $postType)
    {
        $this->postType = $postType;
        add_filter('post_row_actions', [$this, 'render'], 10, 2);
    }

    /**
     * Добавляет действие к строке записи
     *
     * @param string $key Ключ действия
     * @param callable $callback Функция, возвращающая URL
     */
    public function addAction(string $key, callable $callback): void
    {
        $this->actions[$key] = $callback;
    }

    public function render(array $actions, WP_Post $post): array
    {
        if ($post->post_type !== $this->postType) return $actions;

        foreach ($this->actions as $key => $callback) {
            $url = call_user_func($callback, $post);
            if ($url) {
                $actions[$key] = '<a href="' . esc_url($url) . '">' . esc_html(ucfirst($key)) . '</a>';
            }
        }

        return $actions;
    }
}
