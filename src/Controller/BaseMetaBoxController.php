<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\MetaBoxContext;
use WpToolKit\Entity\MetaBoxPriority;
use WpToolKit\Entity\ScriptType;
use WpToolKit\Interface\MetaBoxInterface;

class BaseMetaBoxController implements MetaBoxInterface
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

        ScriptController::addStyle('understrap-estate-metabox', 'MetaBox.css', ScriptType::ADMIN);
    }

    public function render($post): void
    {
        // Override this method in your childs    
    }

    public function callback($postId): void
    {
        // Override this method in your childs
    }
}
