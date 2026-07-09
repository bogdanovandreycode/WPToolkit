<?php

namespace WpToolKit\Controller;

use WpToolKit\Manager\WpBakeryDesignManager;

abstract class WpBakeryElementController
{
    /**
     * @param array<int, array<string, mixed>> $params
     */
    public function __construct(
        protected string $base,
        protected string $name,
        protected string $category = '',
        protected string $description = '',
        protected string $icon = '',
        protected array $params = [],
        protected bool $designOptions = true,
        protected string $designParamName = 'wptk_design',
        protected WpBakeryDesignManager $designManager = new WpBakeryDesignManager()
    ) {
        if (function_exists('add_shortcode')) {
            add_shortcode($this->base, [$this, 'render']);
        }

        if (function_exists('add_action')) {
            add_action('vc_before_init', [$this, 'registerElement']);
        }
    }

    public function registerElement(): void
    {
        if (!function_exists('vc_map')) {
            return;
        }

        if ($this->designOptions) {
            $this->designManager->registerParamType();
        }

        vc_map($this->getMap());
    }

    final public function render($atts, $content = null): string
    {
        $atts = $this->getAtts(is_array($atts) ? $atts : []);
        $html = $this->renderElement($atts, $content);

        if (!$this->designOptions) {
            return $html;
        }

        $style = $this->renderDesignStyle($atts);

        if (!$this->wrapDesignElement()) {
            return $style . $html;
        }

        return $style . $this->wrapWithDesign($atts, $html);
    }

    /**
     * @param array<string, mixed> $atts
     */
    abstract protected function renderElement(array $atts, ?string $content = null): string;

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function params(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function map(): array
    {
        return [];
    }

    protected function wrapDesignElement(): bool
    {
        return true;
    }

    protected function designWrapperTag(): string
    {
        return 'div';
    }

    /**
     * @param array<string, mixed> $atts
     * @return array<string, string>
     */
    protected function designWrapperAttributes(array $atts): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $atts
     */
    protected function getDesignClass(array $atts): string
    {
        return $this->designManager->className(
            $this->base,
            (string) ($atts[$this->designParamName] ?? '')
        );
    }

    /**
     * @param array<string, mixed> $atts
     */
    protected function renderDesignStyle(array $atts): string
    {
        $css = $this->designManager->buildCss(
            (string) ($atts[$this->designParamName] ?? ''),
            '.' . $this->getDesignClass($atts)
        );

        if ($css === '') {
            return '';
        }

        return '<style>' . $css . '</style>';
    }

    /**
     * @param array<string, mixed> $atts
     */
    protected function wrapWithDesign(array $atts, string $html): string
    {
        $designClass = $this->getDesignClass($atts);
        $attributes = $this->designWrapperAttributes($atts);
        $attributes['class'] = trim($designClass . ' ' . ($attributes['class'] ?? ''));

        return sprintf(
            '<%1$s%2$s>%3$s</%1$s>',
            $this->escapeTag($this->designWrapperTag()),
            $this->renderHtmlAttributes($attributes),
            $html
        );
    }

    /**
     * @param array<string, mixed> $atts
     * @return array<string, mixed>
     */
    public function getAtts(array $atts): array
    {
        if (function_exists('shortcode_atts')) {
            return shortcode_atts($this->getDefaultAtts(), $atts, $this->base);
        }

        return array_merge($this->getDefaultAtts(), $atts);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMap(): array
    {
        $map = array_merge([
            'name' => $this->name,
            'base' => $this->base,
            'category' => $this->category,
            'description' => $this->description,
            'icon' => $this->icon,
        ], $this->map());

        $map['params'] = $this->getParams();

        return array_filter(
            $map,
            static fn ($value): bool => $value !== '' && $value !== null && $value !== []
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function getParams(): array
    {
        $params = array_merge($this->params, $this->params());

        if ($this->designOptions) {
            $params[] = $this->designManager->param($this->designParamName);
        }

        return $params;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultAtts(): array
    {
        $atts = [];

        foreach ($this->getParams() as $param) {
            if (!isset($param['param_name'])) {
                continue;
            }

            $atts[(string) $param['param_name']] = $param['std'] ?? $param['value'] ?? '';
        }

        return $atts;
    }

    /**
     * @param array<string, string> $attributes
     */
    protected function renderHtmlAttributes(array $attributes): string
    {
        $html = '';

        foreach ($attributes as $name => $value) {
            if ($value === '') {
                continue;
            }

            $html .= sprintf(
                ' %s="%s"',
                $this->escapeAttributeName($name),
                $this->escapeAttribute((string) $value)
            );
        }

        return $html;
    }

    protected function escapeAttribute(string $value): string
    {
        if (function_exists('esc_attr')) {
            return esc_attr($value);
        }

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    protected function escapeAttributeName(string $value): string
    {
        return preg_replace('/[^a-zA-Z0-9_:-]/', '', $value) ?: '';
    }

    protected function escapeTag(string $value): string
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $value) ?: 'div';
    }
}
