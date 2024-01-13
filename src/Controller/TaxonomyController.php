<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\Post;
use WpToolKit\Entity\Taxonomy;
use WpToolKit\Factory\ServiceFactory;

class TaxonomyController
{
    public function __construct(
        private Post $post,
        private Taxonomy $taxonomy
    ) {
        add_action('init', function () {
            register_taxonomy(
                $this->taxonomy->name,
                $this->post->name,
                [
                    'labels' => [
                        'name' => _x($this->taxonomy->labelName, $this->taxonomy->name),
                        'singular_name' => _x($this->taxonomy->labelSingularName, $this->taxonomy->labelSingularName),
                        'search_items' =>  __($this->taxonomy->labelSearchItems),
                        'all_items' => __($this->taxonomy->labelAllItems),
                        'parent_item' => __($this->taxonomy->labelParentItem),
                        'parent_item_colon' => __($this->taxonomy->labelParentItemColon),
                        'edit_item' => __($this->taxonomy->labelEditItem),
                        'update_item' => __($this->taxonomy->labelUpdateItem),
                        'add_new_item' => __($this->taxonomy->labelAddNewItem),
                        'new_item_name' => __($this->taxonomy->labelNewItemName),
                        'menu_name' => __($this->taxonomy->labelMenuName)
                    ],
                    'hierarchical' => $this->taxonomy->hierarchical,
                    'show_ui' => $this->taxonomy->showedUi,
                    'query_var' => $this->taxonomy->queryVar,
                    'show_in_rest' => $this->post->rest
                ]
            );
        });
    }

    public function addToSubMenu(): void
    {
        $menu = ServiceFactory::getService('MenuController');

        $menu->addSubItem(
            $this->post->getUrl(),
            $this->taxonomy->labelName,
            $this->taxonomy->labelName,
            'manage_options',
            "{$this->taxonomy->getUrl()}&post_type={$this->post->name}",
            '',
            3
        );
    }
}
