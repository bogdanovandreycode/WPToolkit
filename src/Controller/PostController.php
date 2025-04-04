<?php

namespace WpToolKit\Controller;

use WP_Post;
use WP_Query;
use WpToolKit\Entity\Post;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Factory\ServiceFactory;

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

        add_filter('the_content', [$this, 'renderContent']);
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

    public function renderContent($content)
    {
        return $content;
    }
}
