<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\Post;
use WpToolKit\Entity\MetaPoly;

class PostController
{
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
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function registerMenu(): void
    {
        add_action('admin_menu', function () {
            add_menu_page(
                $this->post->title,
                $this->post->title,
                $this->post->role,
                $this->post->getUrl(),
                '',
                $this->post->icon,
                $this->post->position
            );
        });
    }

    public function registerSubMenu(Post $parentPost): void
    {
        add_action('admin_menu', function () use ($parentPost) {
            add_submenu_page(
                $parentPost->getUrl(),
                $this->post->title,
                $this->post->title,
                $this->post->role,
                $this->post->getUrl(),
                '',
                $this->post->position
            );
        });
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
}
