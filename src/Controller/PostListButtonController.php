<?php

namespace WpToolKit\Controller;

/**
 * Контроллер пользовательских кнопок в списке постов
 */
class PostListButtonController
{
    private string $postType;
    private array $buttons = [];

    public function __construct(string $postType)
    {
        $this->postType = $postType;
        add_action('restrict_manage_posts', [$this, 'render']);
    }

    /**
     * Добавляет кнопку
     *
     * @param string $label Название кнопки
     * @param string $url URL для перехода
     * @param array $attrs HTML-атрибуты (например, класс)
     */
    public function addButton(string $label, string $url, array $attrs = []): void
    {
        $this->buttons[] = compact('label', 'url', 'attrs');
    }

    public function render(): void
    {
        $screen = get_current_screen();
        if ($screen->post_type !== $this->postType) return;

        foreach ($this->buttons as $btn) {
            $attrHtml = '';
            foreach ($btn['attrs'] as $attr => $val) {
                $attrHtml .= ' ' . esc_attr($attr) . '="' . esc_attr($val) . '"';
            }

            echo '<a href="' . esc_url($btn['url']) . '" class="button"' . $attrHtml . '>' . esc_html($btn['label']) . '</a> ';
        }
    }
}
