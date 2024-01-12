<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\MetaBoxContext;
use WpToolKit\Entity\MetaBoxPriority;
use WpToolKit\Interface\MetaBoxInterface;

abstract class MetaBoxController implements MetaBoxInterface
{
    public function __construct(
        private string $id,
        private string $title,
        private string $postName,
        private MetaBoxContext $context = MetaBoxContext::ADVANCED,
        private MetaBoxPriority $priority = MetaBoxPriority::DEFAULT
    ) {
        add_action('add_meta_boxes', function () use ($id, $title, $postName, $context, $priority) {
            add_meta_box(
                $id,
                $title,
                [$this, 'render'],
                $postName,
                $context->value,
                $priority->value
            );
        });

        add_action('save_post', [$this, 'callback']);
    }

    abstract function render($post): void;

    abstract function callback($postId): void;
}
