<?php

namespace WpToolKit\Manager\AdminAction\Strategy;

use WpToolKit\Interface\AdminAction\AdminActionStrategyInterface;
use WP_Post;

class PostActionStrategy implements AdminActionStrategyInterface
{
    public function getHookName(): string
    {
        return 'post_row_actions';
    }

    public function isApplicable(string $screenId, ?string $postType, mixed $item): bool
    {
        return $item instanceof WP_Post && get_current_screen()->id === $screenId && (!$postType || $item->post_type === $postType);
    }

    public function getId(mixed $item): int|string|null
    {
        return $item->ID ?? null;
    }
}
