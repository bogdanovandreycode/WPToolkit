<?php

namespace WpToolKit\Manager\TableNav;

use WP_Screen;
use WP_Query;
use WP_User_Query;
use WpToolKit\Interface\TableNav\TableNavElementInterface;
use WpToolKit\Interface\TableNav\TableNavStrategyInterface;

class TableNavManager
{
    private array $elements = [];

    public function __construct(
        private string $screenId,
        private ?string $postType,
        private TableNavStrategyInterface $strategy
    ) {
        add_action($strategy->getRenderHook($screenId), [$this, 'render']);
        add_filter($strategy->getApplyHook($screenId), [$this, 'apply']);
    }

    public function addElement(TableNavElementInterface $element): void
    {
        $this->elements[] = $element;
    }

    public function render(): void
    {
        if (!$this->strategy->isApplicable($this->screenId, $this->postType)) {
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

        if (!$this->strategy->isApplicable($this->screenId, $this->postType)) {
            return;
        }

        foreach ($this->elements as $element) {
            if (method_exists($element, 'apply')) {
                $element->apply($query);
            }
        }
    }
}
