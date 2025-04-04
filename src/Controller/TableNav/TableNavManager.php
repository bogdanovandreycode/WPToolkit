<?php

namespace WpToolKit\Controller\TableNav;

use WP_Query;

class TableNavManager
{
    private string $postType;
    private array $elements = [];

    public function __construct(string $postType)
    {
        $this->postType = $postType;

        add_action('restrict_manage_posts', [$this, 'render']);
        add_filter('parse_query', [$this, 'apply']);
    }

    public function addElement(TableNavElementInterface $element): void
    {
        $this->elements[] = $element;
    }

    public function render(): void
    {
        $screen = get_current_screen();

        if ($screen->post_type !== $this->postType) return;

        foreach ($this->elements as $element) {
            echo $element->render();
        }
    }

    public function apply(WP_Query $query): void
    {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== $this->postType) {
            return;
        }

        foreach ($this->elements as $element) {
            if (method_exists($element, 'apply')) {
                $element->apply($query);
            }
        }
    }
}
