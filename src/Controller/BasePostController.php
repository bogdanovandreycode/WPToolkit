<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\Post;
use WpToolKit\Controller\ScriptController;
use WpToolKit\Entity\MetaPoly;

class BasePostController
{

    public function __construct()
    {
    }

    public function registerPublicType(Post $post): void
    {
        add_action('init', function () use ($post) {
            register_post_type(
                $post->getName(),
                [
                    'public' => true,
                    'label'  => $post->getTitle(),
                    'menu_icon' => $post->getIcon(),
                    'supports' => $post->getSupports(),
                    'show_in_menu' => $post->getUrl(),
                    'menu_position' => $post->getPosition(),
                    'show_in_rest' => true
                ]
            );
        });
    }

    public function registerMenu(Post $post): void
    {
        add_action('admin_menu', function () use ($post) {
            add_menu_page(
                $post->getTitle(),
                $post->getTitle(),
                $post->getRole(),
                $post->getUrl(),
                '',
                $post->getIcon(),
                $post->getPosition()
            );
        });
    }

    public function registerSubMenu(Post $parentPost, Post $post): void
    {
        add_action('admin_menu', function () use ($parentPost, $post) {
            add_submenu_page(
                $parentPost->getUrl(),
                $post->getTitle(),
                $post->getTitle(),
                $post->getRole(),
                $post->getUrl(),
                '',
                $post->getPosition()
            );
        });
    }

    public function addMetaPolyGutenberg(
        Post $post,
        MetaPoly $metaPoly,
        string $handleScript,
        string $fileScript,
    ) {
        register_post_meta(
            $post->getName(),
            $metaPoly->getName(),
            [
                'single' => $metaPoly->isSingle(),
                'show_in_rest' => $metaPoly->isShowInRest(),
                'type' => $metaPoly->getType()->value,
            ]
        );

        ScriptController::addGutenbergScript($handleScript, $fileScript);
    }

    public function addMetaPoly(
        Post $post,
        MetaPoly $metaPoly,
    ) {
        register_post_meta(
            $post->getName(),
            $metaPoly->getName(),
            [
                'single' => $metaPoly->isSingle(),
                'show_in_rest' => $metaPoly->isShowInRest(),
                'type' => $metaPoly->getType()->value,
            ]
        );
    }
}
