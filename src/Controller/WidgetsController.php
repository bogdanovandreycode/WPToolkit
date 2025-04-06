<?php

namespace WpToolKit\Controller;

use WP_Widget;
use WpToolKit\Interface\WidgetInterface;

abstract class WidgetsController extends WP_Widget implements WidgetInterface
{
    public function __construct(
        string $idBase,
        string $name,
        string $description = ''
    ) {
        parent::__construct(
            $idBase,
            $name,
            ['description' => $description]
        );

        add_action('widgets_init', function () {
            register_widget(static::class);
        });
    }

    public function widget($args, $instance): void
    {
        throw new \RuntimeException("The widget() method must be overridden in the subclass.");
    }

    public function form($instance): void
    {
        throw new \RuntimeException("The form() method must be overridden in the subclass.");
    }

    public function update($new_instance, $old_instance): array
    {
        throw new \RuntimeException("The update() method must be overridden in the subclass.");
    }
}
