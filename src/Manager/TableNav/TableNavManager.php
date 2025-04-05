<?php

namespace WpToolKit\Manager\TableNav;

use WP_Query;
use WP_Screen;
use WpToolKit\Interface\TableNav\TableNavElementInterface;

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

        $hooks = $this->getHookNames();

        add_action($hooks['render'], [$this, 'render']);
        add_filter($hooks['apply'], [$this, 'apply']);
    }

    private function getHookNames(): array
    {
        switch ($this->screenId) {
            case 'users':
                return [
                    'render' => 'restrict_manage_users',
                    'apply'  => 'pre_get_users',
                ];

            case 'upload':
                return [
                    'render' => 'restrict_manage_posts',
                    'apply'  => 'parse_query',
                ];

            default:
                return [
                    'render' => 'restrict_manage_posts',
                    'apply'  => 'parse_query',
                ];
        }
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

    /**
     * @param WP_Query|WP_User_Query $query
     */
    public function apply($query): void
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
