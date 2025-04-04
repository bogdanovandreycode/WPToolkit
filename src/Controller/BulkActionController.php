<?php

namespace WpToolKit\Controller;

use WP_Screen;

class BulkActionController
{
    private string $screenId;
    private string $postType;
    private array $actions = [];

    /** @var callable|null */
    private $noticeCallback = null;


    public function __construct(string $screenId, ?string $postType = null)
    {
        $this->screenId = $screenId;
        $this->postType = $postType ?? '';

        add_filter("bulk_actions-{$this->screenId}", [$this, 'registerActions']);
        add_filter("handle_bulk_actions-{$this->screenId}", [$this, 'handleAction'], 10, 3);
        add_action("admin_notices", [$this, 'showNotice']);
    }

    public function addAction(string $key, string $label, callable $callback): void
    {
        $this->actions[$key] = [
            'label' => $label,
            'callback' => $callback
        ];
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
            !isset($_GET['bulk_action_done'], $_GET['processed']) ||
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
