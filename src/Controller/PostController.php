<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\MetaPoly;
use WpToolKit\Entity\Post;

class PostController
{
    private readonly MenuController $menu;

    public function __construct(
        private readonly Post $post,
        ?MenuController $menu = null
    ) {
        $this->menu = $menu ?? new MenuController();

        add_action('init', function (): void {
            register_post_type(
                $this->post->name,
                [
                    'public' => $this->post->public,
                    'label' => $this->post->title,
                    'menu_icon' => $this->post->icon,
                    'supports' => $this->post->supports,
                    'show_in_menu' => $this->post->getUrl(),
                    'menu_position' => $this->post->position,
                    'show_in_rest' => $this->post->rest,
                ]
            );
        });

        add_filter('the_content', [$this, 'renderContent']);
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function addToMenu(): void
    {
        $this->menu->addItem(
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
        $this->menu->addSubItem(
            $parentPost->getUrl(),
            $this->post->title,
            $this->post->title,
            $this->post->role,
            $this->post->getUrl(),
            '',
            $this->post->position
        );
    }

    public function addMetaPoly(MetaPoly $metaPoly): void
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

    public function renderContent(mixed $content): mixed
    {
        return $content;
    }
}
