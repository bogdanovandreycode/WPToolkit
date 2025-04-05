<?php

namespace WpToolKit\Manager;

use WP_Post;

class AdminActionManager
{
    private string $screenId;
    private ?string $postType;
    private array $actions = [];

    public function __construct(string $screenId, ?string $postType = null)
    {
        $this->screenId = $screenId;
        $this->postType = $postType;

        add_filter('post_row_actions', [$this, 'render'], 10, 2);
    }


    public function addAction(string $key, callable $callback): void
    {
        $this->actions[$key] = $callback;
    }

    public function render(array $actions, WP_Post $post): array
    {
        $screen = get_current_screen();

        if (
            !is_admin() ||
            $screen->id !== $this->screenId ||
            ($this->postType && $post->post_type !== $this->postType)
        ) {
            return $actions;
        }

        foreach ($this->actions as $key => $callback) {
            $url = call_user_func($callback, $post);
            if ($url) {
                $actions[$key] = '<a href="' . esc_url($url) . '">' . esc_html(ucfirst($key)) . '</a>';
            }
        }

        return $actions;
    }
}
