<?php

namespace WpToolKit\Manager\TableNav;

use WP_Screen;
use WP_Query;

class TableNavManager
{
    private string $screenId;
    private ?string $postType;
    private array $elements = [];

    /**
     * @param string $screenId Screen ID, например "edit-post", "edit-tutor_profiles", "users"
     * @param string|null $postType Опциональный post_type (например 'tutor_profiles')
     */
    public function __construct(string $screenId, ?string $postType = null)
    {
        $this->screenId = $screenId;
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

        if (
            !($screen instanceof WP_Screen) ||
            $screen->id !== $this->screenId ||
            ($this->postType && $screen->post_type !== $this->postType)
        ) {
            return;
        }

        foreach ($this->elements as $element) {
            echo $element->render();
        }
    }

    public function apply(WP_Query $query): void
    {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $screen = get_current_screen();
        if (
            !($screen instanceof WP_Screen) ||
            $screen->id !== $this->screenId ||
            ($this->postType && $screen->post_type !== $this->postType)
        ) {
            return;
        }

        foreach ($this->elements as $element) {
            if (method_exists($element, 'apply')) {
                $element->apply($query);
            }
        }
    }
}
