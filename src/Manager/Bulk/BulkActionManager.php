<?php

namespace WpToolKit\Manager\Bulk;

use WpToolKit\Interface\Bulk\BulkStrategyInterface;
use WP_Screen;

class BulkActionManager
{
    private array $actions = [];

    /** @var callable|null */
    private $noticeCallback = null;

    public function __construct(
        private string $screenId,
        private ?string $postType,
        private BulkStrategyInterface $strategy
    ) {
        add_filter($strategy->getRegisterHook($screenId), [$this, 'registerActions']);
        add_filter($strategy->getHandleHook($screenId), [$this, 'handleAction'], 10, 3);
        add_action("admin_notices", [$this, 'showNotice']);
    }

    public function addAction(string $key, string $label, callable $callback): void
    {
        $this->actions[$key] = compact('label', 'callback');
    }

    public function registerActions(array $actions): array
    {
        foreach ($this->actions as $key => $data) {
            $actions[$key] = $data['label'];
        }
        return $actions;
    }

    public function handleAction(string $redirectUrl, string $action, array $objectIds): string
    {
        if (!isset($this->actions[$action])) {
            return $redirectUrl;
        }

        $processed = 0;
        foreach ($objectIds as $id) {
            if (call_user_func($this->actions[$action]['callback'], $id)) {
                $processed++;
            }
        }

        return add_query_arg([
            'bulk_action_done' => $action,
            'processed' => $processed
        ], $redirectUrl);
    }

    public function setNoticeCallback(callable $callback): void
    {
        $this->noticeCallback = $callback;
    }

    public function showNotice(): void
    {
        if (
            !isset($_GET['bulk_action_done'], $_GET['processed'])
        ) {
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

        if (is_callable($this->noticeCallback)) {
            call_user_func($this->noticeCallback, $_GET['bulk_action_done'], (int) $_GET['processed']);
        }
    }
}
