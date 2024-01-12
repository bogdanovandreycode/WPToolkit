<?php

namespace WpToolKit\Field;

use WpToolKit\Interface\FieldInterface;

class SelectField implements FieldInterface
{
    /**
     * @param array<string, string> $attributes
     * @param array<string, string> $options
     */
    public function __construct(
        public string $name,
        public string $title,
        public array $options,
        public string $selected,
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
        ];

        $attributes += $this->attributes;

        $attributes = array_filter($attributes, function ($value) {
            return !empty($value);
        });

        $attributesString = implode(' ', array_map(function ($key, $value) {
            return sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_QUOTES));
        }, array_keys($attributes), $attributes));

        $output = ["<select {$attributesString} >"];
        $output[] = "<option>" . esc_html__('No', 'text-domain') . "</option>";

        foreach ($this->options as $name => $title) {
            $selected = $this->selected == $name ? 'selected' : '';
            $output[] = "<option value=\"" . esc_attr($name) . "\" {$selected}>" . esc_html($title) . "</option>";
        }

        $output[] = "</select>";
        return implode(' ', $output);
    }
}
