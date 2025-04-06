<?php

namespace WpToolKit\Controller;

use WP_Widget;

abstract class WidgetsController extends WP_Widget
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

    abstract public function widget($args, $instance): void;

    abstract public function form($instance): void;

    abstract public function update($new_instance, $old_instance): array;
}
