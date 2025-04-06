<?php

namespace WpToolKit\Interface;

interface WidgetInterface
{
    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance): void;

    /**
     * @param array $instance
     */
    public function form($instance): void;

    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance): array;
}
