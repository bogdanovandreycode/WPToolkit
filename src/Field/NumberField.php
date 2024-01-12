<?php

namespace WpToolKit\Field;

use WpToolKit\Interface\FieldInterface;

class NumberField implements FieldInterface
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
        public ?int $min = null,
        public ?int $max = null,
        public ?int $step = null,
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
            'type' => 'number',
            'name' => $this->name,
            'require' => $this->require,
            'id' => $this->id,
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
            'value' => esc_attr($this->value),
        ];

        $attributes += $this->attributes;

        // Отфильтровать элементы, удаляя те, у которых пустое значение
        $attributes = array_filter($attributes, function ($value) {
            return !empty($value);
        });

        // Convert each key-value pair into a string of the format 'key="value"'
        $attributesString = implode(' ', array_map(function ($key, $value) {
            return sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_QUOTES));
        }, array_keys($attributes), $attributes));

        return "<input {$attributesString} >";
    }
}
