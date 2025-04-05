<?php

namespace WpToolKit\Manager\AdminAction\Strategy;

use WpToolKit\Interface\AdminAction\AdminActionStrategyInterface;
use WP_Comment;

class CommentActionStrategy implements AdminActionStrategyInterface
{
    public function getHookName(): string
    {
        return 'comment_row_actions';
    }

    public function isApplicable(string $screenId, ?string $postType, mixed $item): bool
    {
        return $item instanceof WP_Comment && get_current_screen()->id === $screenId;
    }

    public function getId(mixed $item): int|string|null
    {
        return $item->comment_ID ?? null;
    }
}
