<?php

namespace WpToolKit\Manager\TableNav\Strategy;

use WpToolKit\Interface\TableNav\TableNavStrategyInterface;

class UploadTableNavStrategy implements TableNavStrategyInterface
{
    public function getRenderHook(string $screenId): string
    {
        return 'restrict_manage_posts';
    }

    public function getApplyHook(string $screenId): string
    {
        return 'parse_query';
    }

    public function isApplicable(string $screenId, ?string $postType): bool
    {
        $screen = get_current_screen();
        return $screen?->id === $screenId && $screen?->post_type === 'attachment';
    }
}
