<?php

namespace WpToolKit\Manager\AdminAction;

use WpToolKit\Interface\AdminAction\AdminActionStrategyInterface;

class AdminActionManager
{
    private array $actions = [];

    public function __construct(
        private string $screenId,
        private ?string $postType,
        private AdminActionStrategyInterface $strategy
    ) {
        add_filter($strategy->getHookName(), [$this, 'render'], 10, 2);
    }

    public function addAction(string $key, callable $callback): void
    {
        $this->actions[$key] = $callback;
    }

    public function render(array $actions, mixed $item): array
    {
        if (!$this->strategy->isApplicable($this->screenId, $this->postType, $item)) {
            return $actions;
        }

        foreach ($this->actions as $key => $callback) {
            $url = call_user_func($callback, $item);
            if ($url) {
                $actions[$key] = '<a href="' . esc_url($url) . '">' . esc_html(ucfirst($key)) . '</a>';
            }
        }

        return $actions;
    }
}
