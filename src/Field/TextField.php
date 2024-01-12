<?php

namespace WpToolKit\Field;

use WpToolKit\Interface\FieldInterface;

class TextField implements FieldInterface
{
    /**
     * @param array<string, string> $attributes
     */
    public function __construct(
        public string $name,
        public string $title,
        public string $value = '',
        public bool $require = false,
        public string $class = '',
        public string $id = '',
        public array $attributes = []
    ) {
        $id = empty($id) ? $name : $id;
        $class = empty($class) ? 'regular-text' : $class;
    }

    public function renderLabel(): string
    {
        return "<label for=\"{$this->name}\">{$this->title}</label>";
    }

    public function renderField(): string
    {
        $attributes = [
            'type' => 'text',
            'require' => $this->require,
            'name' => $this->name,
            'id' => $this->id,
            'value' => esc_attr($this->value),
        ];

        $attributes += $this->attributes;

        $attributes = array_filter($attributes, function ($value) {
            return !empty($value);
        });

        $attributesString = implode(' ', array_map(function ($key, $value) {
            return sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_QUOTES));
        }, array_keys($attributes), $attributes));

        return "<input {$attributesString} >";
    }
}
