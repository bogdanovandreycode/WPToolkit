<?php

namespace WpToolKit\Manager\TableNav\Element;

use WpToolKit\Interface\TableNav\TableNavElementInterface;

class TableNavButton implements TableNavElementInterface
{
    public function __construct(
        private string $label,
        private string $name,

        /** @var callable|null */
        private $callback = null,

        private array $attrs = []
    ) {
        add_action('admin_init', [$this, 'handleCallback']);
    }

    public function handleCallback(): void
    {
        if (isset($_GET[$this->name]) || isset($_POST[$this->name])) {
            if (is_callable($this->callback)) {
                call_user_func($this->callback);
                exit;
            }
        }
    }

    public function render(): string
    {
        $attrs = '';
        foreach ($this->attrs as $key => $value) {
            $attrs .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
        }

        return '<input type="submit" name="' . esc_attr($this->name) . '" value="' . esc_attr($this->label) . '" class="button"' . $attrs . '>';
    }
}
