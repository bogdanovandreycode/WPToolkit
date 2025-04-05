<?php

namespace WpToolKit\Manager\TableNav\Element;

use WP_Query;
use WpToolKit\Interface\TableNav\TableNavElementInterface;

class TableNavFilter implements TableNavElementInterface
{
    public function __construct(
        private string $metaKey,
        private array $options,
        private string $label
    ) {}

    public function render(): string
    {
        $current = $_GET[$this->metaKey] ?? '';
        $output = '<select name="' . esc_attr($this->metaKey) . '">';
        $output .= '<option value="">' . esc_html($this->label) . '</option>';

        foreach ($this->options as $value => $label) {
            $selected = selected($current, $value, false);
            $output .= '<option value="' . esc_attr($value) . '"' . $selected . '>' . esc_html($label) . '</option>';
        }

        $output .= '</select> ';
        return $output;
    }

    public function apply(WP_Query $query): void
    {
        $value = $_GET[$this->metaKey] ?? null;

        if (!empty($value)) {
            $metaQuery = $query->get('meta_query') ?: [];
            $metaQuery[] = [
                'key' => $this->metaKey,
                'value' => $value
            ];
            $query->set('meta_query', $metaQuery);
        }
    }
}
