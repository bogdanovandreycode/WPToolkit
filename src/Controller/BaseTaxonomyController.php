<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\Post;
use WpToolKit\Entity\Taxonomy;

class BaseTaxonomyController
{
    public function __construct()
    {
    }

    public function registerTaxonomy(Post $post, Taxonomy $taxonomy): void
    {
        add_action('init', function () use ($post, $taxonomy) {
            register_taxonomy(
                $taxonomy->getName(),
                $post->getName(),
                [
                    'labels' => [
                        'name' => _x($taxonomy->getLabelName(), $taxonomy->getName()),
                        'singular_name' => _x($taxonomy->getLabelSingularName(), $taxonomy->getLabelSingularName()),
                        'search_items' =>  __($taxonomy->getLabelSearchItems()),
                        'all_items' => __($taxonomy->getLabelAllItems()),
                        'parent_item' => __($taxonomy->getLabelParentItem()),
                        'parent_item_colon' => __($taxonomy->getLabelParentItemColon()),
                        'edit_item' => __($taxonomy->getLabelEditItem()),
                        'update_item' => __($taxonomy->getLabelUpdateItem()),
                        'add_new_item' => __($taxonomy->getLabelAddNewItem()),
                        'new_item_name' => __($taxonomy->getLabelNewItemName()),
                        'menu_name' => __($taxonomy->getLabelMenuName())
                    ],
                    'hierarchical' => $taxonomy->isHierarchical(),
                    'show_ui' => $taxonomy->isShowedUi(),
                    'query_var' => $taxonomy->isQueryVar(),
                    'show_in_rest' => true
                ]
            );
        });
    }

    public function registerSebMenu(Post $post, Taxonomy $taxonomy): void
    {
        add_action('admin_menu', function () use ($post, $taxonomy) {
            add_submenu_page(
                $post->getUrl(),
                $taxonomy->getLabelName(),
                $taxonomy->getLabelName(),
                'manage_options',
                "{$taxonomy->getUrl()}&post_type={$post->getName()}"
            );
        });
    }
}
