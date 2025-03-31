<?php

namespace WpToolKit\Controller;

use WP_Query;

/**
 * Контроллер мета-фильтров в админке
 */
class MetaFilterController
{
    private string $postType;
    private array $filters = [];

    public function __construct(string $postType)
    {
        $this->postType = $postType;

        add_action('restrict_manage_posts', [$this, 'render']);
        add_filter('parse_query', [$this, 'apply']);
    }

    /**
     * Добавляет выпадающий фильтр
     *
     * @param string $metaKey Ключ мета-поля
     * @param array $options Массив [значение => метка]
     * @param string $label Название фильтра
     */
    public function addFilter(string $metaKey, array $options, string $label): void
    {
        $this->filters[] = compact('metaKey', 'options', 'label');
    }

    public function render(): void
    {
        $screen = get_current_screen();
        if ($screen->post_type !== $this->postType) return;

        foreach ($this->filters as $filter) {
            $current = $_GET[$filter['metaKey']] ?? '';
            echo '<select name="' . esc_attr($filter['metaKey']) . '">';
            echo '<option value="">' . esc_html($filter['label']) . '</option>';
            foreach ($filter['options'] as $value => $label) {
                $selected = $current == $value ? ' selected' : '';
                echo '<option value="' . esc_attr($value) . '"' . $selected . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
        }
    }

    public function apply(WP_Query $query): void
    {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== $this->postType) {
            return;
        }

        $metaQuery = [];

        foreach ($this->filters as $filter) {
            $value = $_GET[$filter['metaKey']] ?? null;
            if (!empty($value)) {
                $metaQuery[] = [
                    'key' => $filter['metaKey'],
                    'value' => $value,
                ];
            }
        }

        if (!empty($metaQuery)) {
            $query->set('meta_query', $metaQuery);
        }
    }
}
