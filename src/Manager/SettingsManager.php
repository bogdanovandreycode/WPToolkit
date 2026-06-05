<?php

namespace WpToolKit\Manager;

use WpToolKit\Interface\FieldInterface;

class SettingsManager
{
    /**
     * @var array<string, callable|null>
     */
    private array $registeredSettings = [];

    /**
     * @var array<string, array{title: string, callback: callable|null, page: string}>
     */
    private array $sections = [];

    /**
     * @var array<int, array{page: string, section: string, field: FieldInterface}>
     */
    private array $fields = [];

    public function __construct()
    {
        add_action('admin_init', [$this, 'register']);
    }

    public function addSetting(string $optionGroup, string $optionName, ?callable $sanitizeCallback = null): void
    {
        $this->registeredSettings[$optionGroup . ':' . $optionName] = $sanitizeCallback;
    }

    public function addSection(string $id, string $title, string $page, ?callable $callback = null): void
    {
        $this->sections[$id] = [
            'title' => $title,
            'callback' => $callback,
            'page' => $page,
        ];
    }

    public function addField(string $page, string $section, FieldInterface $field): void
    {
        $this->fields[] = [
            'page' => $page,
            'section' => $section,
            'field' => $field,
        ];
    }

    public function register(): void
    {
        foreach ($this->registeredSettings as $compoundKey => $sanitizeCallback) {
            [$optionGroup, $optionName] = explode(':', $compoundKey, 2);

            register_setting($optionGroup, $optionName, $sanitizeCallback);
        }

        foreach ($this->sections as $id => $section) {
            add_settings_section(
                $id,
                $section['title'],
                $section['callback'] ?? '__return_null',
                $section['page']
            );
        }

        foreach ($this->fields as $fieldData) {
            $field = $fieldData['field'];

            add_settings_field(
                $field->renderLabel(),
                '',
                function () use ($field): void {
                    echo $field->renderField();
                },
                $fieldData['page'],
                $fieldData['section']
            );
        }
    }

    public function renderForm(string $optionGroup, string $page, string $method = 'post'): void
    {
        echo '<form method="' . esc_attr($method) . '">';
        settings_fields($optionGroup);
        do_settings_sections($page);
        submit_button();
        echo '</form>';
    }
}
