<?php

namespace WpToolKit\Controller;

abstract class ShortcodeController
{
    /**
     * @param array<string, mixed> $atts
     */
    public function __construct(
        public string $name,
        public array $atts
    ) {
        add_shortcode($name, [$this, 'render']);
    }

    abstract function render($atts, $content): string;

    /**
     * @return array<string, mixed>
     */
    public function getAtts($atts): array
    {
        return shortcode_atts($this->atts, $atts);
    }
}
