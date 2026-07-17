<?php

namespace WpToolKit\Manager;

final class WpBakeryDesignManager
{
    private const PARAM_TYPE = 'wptk_design';

    /**
     * @var array<string, array{label: string, media: string|null}>
     */
    private const SCREENS = [
        'default' => ['label' => 'Default', 'media' => null],
        'laptops' => ['label' => 'Laptops', 'media' => '@media (max-width: 1199px)'],
        'tablets' => ['label' => 'Tablets', 'media' => '@media (max-width: 991px)'],
        'mobiles' => ['label' => 'Mobiles', 'media' => '@media (max-width: 767px)'],
    ];

    /**
     * @var array<string, string[]>
     */
    private const FIELDS = [
        'Text' => [
            'color',
            'text-align',
            'font-size',
            'line-height',
            'letter-spacing',
            'font-family',
            'font-weight',
            'text-transform',
            'font-style',
        ],
        'Background' => [
            'background-color',
            'background-image',
            'background-position',
            'background-size',
        ],
        'Size' => [
            'width',
            'height',
            'max-width',
            'max-height',
            'min-width',
            'min-height',
        ],
        'Spacing' => [
            'margin-left',
            'margin-top',
            'margin-bottom',
            'margin-right',
            'padding-left',
            'padding-top',
            'padding-bottom',
            'padding-right',
        ],
        'Border' => [
            'border-radius',
            'border-style',
            'border-left-width',
            'border-top-width',
            'border-bottom-width',
            'border-right-width',
            'border-color',
        ],
        'Position' => [
            'position',
            'left',
            'top',
            'bottom',
            'right',
            'z-index',
            'overflow',
        ],
        'Text shadow' => [
            'text-shadow-h-offset',
            'text-shadow-v-offset',
            'text-shadow-blur',
            'text-shadow-color',
        ],
        'Element shadow' => [
            'box-shadow-h-offset',
            'box-shadow-v-offset',
            'box-shadow-blur',
            'box-shadow-spread',
            'box-shadow-color',
        ],
        'Animation' => [
            'animation-name',
            'animation-delay',
        ],
    ];

    /**
     * @var array<string, array<string, string>>
     */
    private const SELECT_FIELDS = [
        'background-position' => [
            '' => 'Default',
            'left top' => 'Left top',
            'center top' => 'Center top',
            'right top' => 'Right top',
            'left center' => 'Left center',
            'center center' => 'Center center',
            'right center' => 'Right center',
            'left bottom' => 'Left bottom',
            'center bottom' => 'Center bottom',
            'right bottom' => 'Right bottom',
        ],
        'background-size' => [
            '' => 'Default',
            'auto' => 'Auto',
            'cover' => 'Cover',
            'contain' => 'Contain',
        ],
        'font-weight' => [
            '' => 'Default',
            '100' => '100 Thin',
            '200' => '200 Extra Light',
            '300' => '300 Light',
            '400' => '400 Normal',
            '500' => '500 Medium',
            '600' => '600 Semi Bold',
            '700' => '700 Bold',
            '800' => '800 Extra Bold',
            '900' => '900 Black',
        ],
        'text-transform' => [
            '' => 'Default',
            'none' => 'None',
            'uppercase' => 'UPPERCASE',
            'lowercase' => 'Lowercase',
            'capitalize' => 'Capitalize',
        ],
        'font-style' => [
            '' => 'Default',
            'normal' => 'Normal',
            'italic' => 'Italic',
        ],
        'border-style' => [
            '' => 'None',
            'none' => 'None',
            'solid' => 'Solid',
            'dashed' => 'Dashed',
            'dotted' => 'Dotted',
            'double' => 'Double',
        ],
        'position' => [
            '' => 'Static',
            'relative' => 'Relative',
            'absolute' => 'Absolute',
            'fixed' => 'Fixed',
            'sticky' => 'Sticky',
        ],
        'overflow' => [
            '' => 'Default',
            'visible' => 'Visible',
            'hidden' => 'Hidden',
            'auto' => 'Auto',
            'scroll' => 'Scroll',
        ],
        'animation-name' => [
            '' => 'None',
            'fadeIn' => 'Fade in',
            'fadeInUp' => 'Fade in up',
            'fadeInDown' => 'Fade in down',
            'fadeInLeft' => 'Fade in left',
            'fadeInRight' => 'Fade in right',
            'zoomIn' => 'Zoom in',
        ],
    ];

    /**
     * @var array<string, array<string, string>>
     */
    private const FONT_FAMILIES = [
        'System fonts (no download required)' => [
            'Georgia, serif' => 'Georgia, serif',
            '"Palatino Linotype", "Book Antiqua", Palatino, serif' => 'Palatino Linotype, Book Antiqua, Palatino, serif',
            '"Times New Roman", Times, serif' => 'Times New Roman, Times, serif',
            'Arial, Helvetica, sans-serif' => 'Arial, Helvetica, sans-serif',
            'Impact, Charcoal, sans-serif' => 'Impact, Charcoal, sans-serif',
            '"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans Unicode, Lucida Grande, sans-serif',
            'Tahoma, Geneva, sans-serif' => 'Tahoma, Geneva, sans-serif',
            '"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS, Helvetica, sans-serif',
            'Verdana, Geneva, sans-serif' => 'Verdana, Geneva, sans-serif',
            '"Courier New", Courier, monospace' => 'Courier New, Courier, monospace',
            '"Lucida Console", Monaco, monospace' => 'Lucida Console, Monaco, monospace',
        ],
    ];

    /**
     * @var array<string, string>
     */
    private const FIELD_LABELS = [
        'color' => 'Color',
        'text-align' => 'Alignment',
        'font-size' => 'Font size',
        'line-height' => 'Line height',
        'letter-spacing' => 'Letter spacing',
        'font-family' => 'Font family',
        'font-weight' => 'Font weight',
        'text-transform' => 'Text transform',
        'font-style' => 'Font style',
        'background-color' => 'Background color',
        'background-image' => 'Background image',
        'background-position' => 'Background position',
        'background-size' => 'Background size',
        'width' => 'Width',
        'height' => 'Height',
        'max-width' => 'Max. width',
        'max-height' => 'Max. height',
        'min-width' => 'Min. width',
        'min-height' => 'Min. height',
        'margin-left' => 'Left',
        'margin-top' => 'Top',
        'margin-bottom' => 'Bottom',
        'margin-right' => 'Right',
        'padding-left' => 'Left',
        'padding-top' => 'Top',
        'padding-bottom' => 'Bottom',
        'padding-right' => 'Right',
        'border-radius' => 'Border radius',
        'border-style' => 'Border style',
        'border-left-width' => 'Left',
        'border-top-width' => 'Top',
        'border-bottom-width' => 'Bottom',
        'border-right-width' => 'Right',
        'border-color' => 'Border color',
        'position' => 'Position',
        'left' => 'Left',
        'top' => 'Top',
        'bottom' => 'Bottom',
        'right' => 'Right',
        'z-index' => 'Z-index',
        'overflow' => 'Overflow',
        'text-shadow-h-offset' => 'Horizontal offset',
        'text-shadow-v-offset' => 'Vertical offset',
        'text-shadow-blur' => 'Blur',
        'text-shadow-color' => 'Color',
        'box-shadow-h-offset' => 'Horizontal offset',
        'box-shadow-v-offset' => 'Vertical offset',
        'box-shadow-blur' => 'Blur',
        'box-shadow-spread' => 'Spread',
        'box-shadow-color' => 'Color',
        'animation-name' => 'Animation',
        'animation-delay' => 'Animation delay',
    ];

    /**
     * @var array<string, string>
     */
    private const FIELD_HINTS = [
        'font-size' => 'Examples: 16px, 1.2rem, max(1rem, 1vw)',
        'line-height' => 'Examples: 28px, 1.7',
        'letter-spacing' => 'Examples: 1px, -0.04em',
        'width' => 'Examples: 200px, 100%, 14rem, 10vw',
        'max-width' => 'Examples: 200px, 100%, 14rem, 10vw',
        'min-width' => 'Examples: 200px, 100%, 14rem, 10vw',
        'height' => 'Examples: 200px, 15rem, 10vh',
        'max-height' => 'Examples: 200px, 15rem, 10vh',
        'min-height' => 'Examples: 200px, 15rem, 10vh',
        'border-radius' => 'Examples: 5px, 50%, 0.3em, 12px 0',
        'text-shadow-h-offset' => 'Examples: 0, 3px, 0.05em, 2rem',
        'text-shadow-v-offset' => 'Examples: 0, 3px, 0.05em, 2rem',
        'text-shadow-blur' => 'Examples: 0, 3px, 0.05em, 2rem',
        'box-shadow-h-offset' => 'Examples: 0, 3px, 0.05em, 2rem',
        'box-shadow-v-offset' => 'Examples: 0, 3px, 0.05em, 2rem',
        'box-shadow-blur' => 'Examples: 0, 3px, 0.05em, 2rem',
        'box-shadow-spread' => 'Examples: 0, 3px, 0.05em, 2rem',
        'animation-delay' => 'Examples: 250ms, 0.5s, 1s, 1.5s',
    ];

    /**
     * @var array<string, bool>
     */
    private const CSS_PROPERTIES = [
        'color' => true,
        'text-align' => true,
        'font-size' => true,
        'line-height' => true,
        'letter-spacing' => true,
        'font-family' => true,
        'font-weight' => true,
        'text-transform' => true,
        'font-style' => true,
        'background-color' => true,
        'background-image' => true,
        'background-position' => true,
        'background-size' => true,
        'width' => true,
        'height' => true,
        'max-width' => true,
        'max-height' => true,
        'min-width' => true,
        'min-height' => true,
        'margin-left' => true,
        'margin-top' => true,
        'margin-bottom' => true,
        'margin-right' => true,
        'padding-left' => true,
        'padding-top' => true,
        'padding-bottom' => true,
        'padding-right' => true,
        'border-radius' => true,
        'border-style' => true,
        'border-left-width' => true,
        'border-top-width' => true,
        'border-bottom-width' => true,
        'border-right-width' => true,
        'border-color' => true,
        'position' => true,
        'left' => true,
        'top' => true,
        'bottom' => true,
        'right' => true,
        'z-index' => true,
        'overflow' => true,
        'animation-name' => true,
        'animation-delay' => true,
    ];

    private bool $paramTypeRegistered = false;

    /**
     * @return array<string, mixed>
     */
    public function param(string $paramName = 'wptk_design'): array
    {
        return [
            'type' => self::PARAM_TYPE,
            'heading' => $this->translate('Design'),
            'param_name' => $paramName,
            'group' => $this->translate('Design'),
            'std' => '',
            'value' => '',
        ];
    }

    public function registerParamType(): void
    {
        if ($this->paramTypeRegistered || !function_exists('vc_add_shortcode_param')) {
            return;
        }

        vc_add_shortcode_param(self::PARAM_TYPE, [$this, 'renderParam']);

        $this->paramTypeRegistered = true;
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function renderParam(array $settings, mixed $value): string
    {
        $paramName = (string) ($settings['param_name'] ?? 'wptk_design');
        $fieldId = str_replace('.', '', uniqid('wptk-design-', true));
        $data = $this->decode((string) $value);
        $encodedData = $this->escapeAttribute($this->encode($data));

        return sprintf(
            '<div id="%1$s" class="wptk-design-param" data-value="%2$s">%3$s%4$s%5$s</div>%6$s',
            $this->escapeAttribute($fieldId),
            $encodedData,
            $this->renderHiddenInput($paramName, $encodedData),
            $this->renderScreens(),
            $this->renderFields($data, $fieldId),
            $this->renderScript($fieldId)
        );
    }

    public function className(string $base, string $value): string
    {
        $base = preg_replace('/[^a-zA-Z0-9_-]/', '-', strtolower($base)) ?: 'element';
        $hash = substr(md5($value), 0, 10);

        return 'wptk-vc-' . trim($base, '-') . '-' . $hash;
    }

    public function buildCss(string $value, string $selector): string
    {
        $data = $this->decode($value);
        $selector = $this->sanitizeSelector($selector);

        if ($selector === '') {
            return '';
        }

        $css = '';

        foreach (self::SCREENS as $screen => $screenConfig) {
            $rules = $this->buildRules($data[$screen] ?? []);

            if ($rules === '') {
                continue;
            }

            $block = $selector . '{' . $rules . '}';
            $media = $screenConfig['media'];

            $css .= $media === null ? $block : $media . '{' . $block . '}';
        }

        return $css;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function decode(string $value): array
    {
        if ($value === '') {
            return [];
        }

        $decoded = rawurldecode($value);
        $data = json_decode($decoded, true);

        if (!is_array($data)) {
            $data = json_decode($value, true);
        }

        if (!is_array($data)) {
            return [];
        }

        $normalized = [];

        foreach (array_keys(self::SCREENS) as $screen) {
            if (!isset($data[$screen]) || !is_array($data[$screen])) {
                continue;
            }

            foreach ($data[$screen] as $property => $propertyValue) {
                if (!is_scalar($propertyValue)) {
                    continue;
                }

                $normalized[$screen][(string) $property] = (string) $propertyValue;
            }
        }

        return $normalized;
    }

    /**
     * @param array<string, array<string, string>> $data
     */
    public function encode(array $data): string
    {
        if ($data === []) {
            return '';
        }

        return rawurlencode(json_encode($data, JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param array<string, string> $rules
     */
    private function buildRules(array $rules): string
    {
        $css = '';
        $backgroundImage = $this->buildBackgroundImage($rules);
        $textShadow = $this->buildShadow($rules, 'text-shadow');
        $boxShadow = $this->buildShadow($rules, 'box-shadow');

        foreach ($rules as $property => $value) {
            $value = trim($value);

            if ($value === '' || $this->isShadowPart($property)) {
                continue;
            }

            if ($property === 'background-image'
                || ($property === 'background-color' && preg_match('/gradient\(/i', $value) === 1)) {
                continue;
            }

            if (!isset(self::CSS_PROPERTIES[$property])) {
                continue;
            }

            $cssValue = $this->sanitizeCssValue($property, $value);

            if ($cssValue === '') {
                continue;
            }

            $cssProperty = $this->normalizeCssProperty($property, $cssValue);
            $css .= $cssProperty . ':' . $cssValue . ';';
        }

        if ($backgroundImage !== '') {
            $css .= 'background-image:' . $backgroundImage . ';';
        }

        if ($textShadow !== '') {
            $css .= 'text-shadow:' . $textShadow . ';';
        }

        if ($boxShadow !== '') {
            $css .= 'box-shadow:' . $boxShadow . ';';
        }

        return $css;
    }

    /**
     * @param array<string, string> $rules
     */
    private function buildBackgroundImage(array $rules): string
    {
        $images = [];
        $backgroundImage = trim($rules['background-image'] ?? '');
        $backgroundColor = trim($rules['background-color'] ?? '');

        if ($backgroundImage !== '') {
            $normalized = $this->sanitizeCssValue('background-image', $backgroundImage);

            if ($normalized !== '') {
                $images[] = $normalized;
            }
        }

        if ($backgroundColor !== '' && preg_match('/gradient\(/i', $backgroundColor) === 1) {
            $normalized = $this->sanitizeCssValue('background-color', $backgroundColor);

            if ($normalized !== '') {
                $images[] = $normalized;
            }
        }

        return implode(',', $images);
    }

    private function isShadowPart(string $property): bool
    {
        return in_array($property, [
            'text-shadow-h-offset',
            'text-shadow-v-offset',
            'text-shadow-blur',
            'text-shadow-color',
            'box-shadow-h-offset',
            'box-shadow-v-offset',
            'box-shadow-blur',
            'box-shadow-spread',
            'box-shadow-color',
        ], true);
    }

    /**
     * @param array<string, string> $rules
     */
    private function buildShadow(array $rules, string $prefix): string
    {
        $hOffset = trim($rules[$prefix . '-h-offset'] ?? '');
        $vOffset = trim($rules[$prefix . '-v-offset'] ?? '');
        $blur = trim($rules[$prefix . '-blur'] ?? '');
        $color = trim($rules[$prefix . '-color'] ?? '');

        if ($prefix === 'box-shadow') {
            $spread = trim($rules[$prefix . '-spread'] ?? '');
            $parts = [$hOffset, $vOffset, $blur, $spread, $color];
        } else {
            $parts = [$hOffset, $vOffset, $blur, $color];
        }

        $parts = array_values(array_filter($parts, static fn (string $part): bool => $part !== ''));

        if ($parts === []) {
            return '';
        }

        return $this->sanitizeCssValue($prefix, implode(' ', $parts));
    }

    private function normalizeCssProperty(string $property, string $value): string
    {
        if ($property === 'background-color' && preg_match('/gradient\(/i', $value) === 1) {
            return 'background-image';
        }

        return $property;
    }

    private function sanitizeCssValue(string $property, string $value): string
    {
        if (stripos($value, 'javascript:') !== false || preg_match('/[{};<>]/', $value) === 1) {
            return '';
        }

        if ($property === 'background-image') {
            return $this->normalizeBackgroundImage($value);
        }

        return trim($value);
    }

    private function normalizeBackgroundImage(string $value): string
    {
        if ($value === '') {
            return '';
        }

        if (preg_match('/^\d+$/', $value) === 1 && function_exists('wp_get_attachment_image_url')) {
            $url = wp_get_attachment_image_url((int) $value, 'full');

            return is_string($url) && $url !== '' ? 'url("' . esc_url_raw($url) . '")' : '';
        }

        if (preg_match('/^(url|linear-gradient|radial-gradient|repeating-linear-gradient|repeating-radial-gradient)\(/i', $value) === 1) {
            return $value;
        }

        return 'url("' . str_replace('"', '\"', $value) . '")';
    }

    private function sanitizeSelector(string $selector): string
    {
        return preg_replace('/[^a-zA-Z0-9_#.,: >+~*=-]/', '', $selector) ?: '';
    }

    private function renderHiddenInput(string $paramName, string $value): string
    {
        return sprintf(
            '<input type="hidden" name="%1$s" class="wpb_vc_param_value wptk-design-value %1$s" value="%2$s">',
            $this->escapeAttribute($paramName),
            $value
        );
    }

    private function renderScreens(): string
    {
        $html = sprintf(
            '<div class="wptk-design-toolbar">
                <button type="button" class="wptk-design-action" data-action="copy">%1$s</button>
                <button type="button" class="wptk-design-action" data-action="paste">%2$s</button>
                <span class="wptk-design-status" aria-live="polite"></span>
            </div>
            <div class="wptk-design-tabs" role="tablist" aria-label="%3$s">',
            $this->escapeHtml($this->translate('Copy')),
            $this->escapeHtml($this->translate('Paste')),
            $this->escapeAttribute($this->translate('Responsive preview'))
        );
        $first = true;

        foreach (self::SCREENS as $screen => $config) {
            $html .= sprintf(
                '<button type="button" class="wptk-design-tab%3$s" data-screen="%1$s" role="tab" aria-selected="%4$s">%2$s</button>',
                $this->escapeAttribute($screen),
                $this->escapeHtml($this->translate($config['label'])),
                $first ? ' is-active' : '',
                $first ? 'true' : 'false'
            );
            $first = false;
        }

        return $html . '</div>';
    }

    /**
     * @param array<string, array<string, string>> $data
     */
    private function renderFields(array $data, string $fieldId): string
    {
        $html = '<div class="wptk-design-fields">';
        $firstScreen = true;

        foreach (self::SCREENS as $screen => $config) {
            $html .= sprintf(
                '<div class="wptk-design-screen%2$s" data-screen="%1$s" role="tabpanel">',
                $this->escapeAttribute($screen),
                $firstScreen ? ' is-active' : ''
            );

            $firstGroup = true;

            foreach (self::FIELDS as $group => $fields) {
                $html .= $this->renderGroup(
                    $fieldId,
                    $screen,
                    $group,
                    $fields,
                    $data[$screen] ?? [],
                    $firstGroup
                );
                $firstGroup = false;
            }

            $html .= '</div>';
            $firstScreen = false;
        }

        return $html . '</div>';
    }

    /**
     * @param string[] $fields
     * @param array<string, string> $values
     */
    private function renderGroup(
        string $fieldId,
        string $screen,
        string $group,
        array $fields,
        array $values,
        bool $open
    ): string {
        $panelId = $fieldId . '-' . md5($screen . $group);
        $content = $group === 'Spacing'
            ? $this->renderSpacingFields($fieldId, $screen, $values)
            : $this->renderRegularFields($fieldId, $screen, $group, $fields, $values);

        return sprintf(
            '<section class="wptk-design-section%6$s" data-group="%1$s">
                <div class="wptk-design-section-header">
                    <button type="button" class="wptk-design-section-toggle" aria-expanded="%5$s" aria-controls="%2$s">
                        <span class="wptk-design-section-title">%3$s</span>
                        <span class="wptk-design-responsive-icon" aria-hidden="true"></span>
                        <span class="wptk-design-chevron" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="wptk-design-reset">%4$s</button>
                </div>
                <div id="%2$s" class="wptk-design-section-panel"%7$s>%8$s</div>
            </section>',
            $this->escapeAttribute($group),
            $this->escapeAttribute($panelId),
            $this->escapeHtml($this->translate($group)),
            $this->escapeHtml($this->translate('Reset')),
            $open ? 'true' : 'false',
            $open ? ' is-open' : '',
            $open ? '' : ' hidden',
            $content
        );
    }

    /**
     * @param string[] $fields
     * @param array<string, string> $values
     */
    private function renderRegularFields(
        string $fieldId,
        string $screen,
        string $group,
        array $fields,
        array $values
    ): string {
        $class = 'wptk-design-grid';

        if (in_array($group, ['Size', 'Text shadow', 'Element shadow'], true)) {
            $class .= ' is-two-column';
        }

        if ($group === 'Position') {
            $class .= ' is-position-grid';
        }

        $html = '<div class="' . $class . '">';

        foreach ($fields as $field) {
            $html .= $this->renderField($fieldId, $screen, $field, $values[$field] ?? '');
        }

        return $html . '</div>';
    }

    /**
     * @param array<string, string> $values
     */
    private function renderSpacingFields(string $fieldId, string $screen, array $values): string
    {
        $html = '<div class="wptk-design-spacing">';

        foreach (['margin' => 'Margin', 'padding' => 'Padding'] as $prefix => $label) {
            $html .= sprintf(
                '<div class="wptk-design-spacing-row" data-spacing="%1$s">
                    <div class="wptk-design-spacing-heading">%2$s</div>
                    <button type="button" class="wptk-design-link-values" aria-pressed="false" title="%3$s">
                        <span aria-hidden="true">↗</span>
                    </button>
                    <div class="wptk-design-spacing-grid">',
                $this->escapeAttribute($prefix),
                $this->escapeHtml($label),
                $this->escapeAttribute($this->translate('Link values'))
            );

            foreach (['left', 'top', 'bottom', 'right'] as $side) {
                $field = $prefix . '-' . $side;
                $html .= $this->renderField($fieldId, $screen, $field, $values[$field] ?? '');
            }

            $html .= '</div></div>';
        }

        return $html . '</div>';
    }

    private function renderField(string $fieldId, string $screen, string $field, string $value): string
    {
        $id = $fieldId . '-' . md5($screen . $field);
        $input = match (true) {
            $field === 'text-align' => $this->renderAlignment($screen, $field, $id, $value),
            $field === 'font-family' => $this->renderFontSelect($screen, $field, $id, $value),
            $field === 'background-image' => $this->renderMediaField($screen, $field, $id, $value),
            $this->isColorField($field) => $this->renderColorField($screen, $field, $id, $value),
            isset(self::SELECT_FIELDS[$field]) => $this->renderSelect($screen, $field, $id, $value),
            default => $this->renderInput($screen, $field, $id, $value),
        };
        $class = 'wptk-design-field wptk-design-field-' . str_replace('-', '_', $field);
        $hint = self::FIELD_HINTS[$field] ?? '';

        if (in_array($field, ['font-family', 'position', 'animation-name', 'animation-delay'], true)) {
            $class .= ' is-full-width';
        }

        return sprintf(
            '<div class="%4$s">
                <label for="%1$s">%2$s</label>
                %3$s
                %5$s
            </div>',
            $this->escapeAttribute($id),
            $this->escapeHtml($this->translate(self::FIELD_LABELS[$field] ?? $field)),
            $input,
            $this->escapeAttribute($class),
            $hint === ''
                ? ''
                : '<div class="wptk-design-hint">' . $this->escapeHtml($this->translate($hint)) . '</div>'
        );
    }

    private function renderInput(string $screen, string $field, string $id, string $value): string
    {
        return sprintf(
            '<input id="%1$s" class="wptk-design-input" type="text" data-screen="%2$s" data-property="%3$s" value="%4$s" autocomplete="off">',
            $this->escapeAttribute($id),
            $this->escapeAttribute($screen),
            $this->escapeAttribute($field),
            $this->escapeAttribute($value)
        );
    }

    private function renderSelect(string $screen, string $field, string $id, string $value): string
    {
        $html = sprintf(
            '<select id="%1$s" class="wptk-design-select" data-screen="%2$s" data-property="%3$s">',
            $this->escapeAttribute($id),
            $this->escapeAttribute($screen),
            $this->escapeAttribute($field)
        );

        $hasCurrentValue = $value === '';

        foreach (self::SELECT_FIELDS[$field] as $optionValue => $label) {
            $hasCurrentValue = $hasCurrentValue || $optionValue === $value;
            $html .= sprintf(
                '<option value="%1$s"%3$s>%2$s</option>',
                $this->escapeAttribute($optionValue),
                $this->escapeHtml($this->translate($label)),
                $optionValue === $value ? ' selected' : ''
            );
        }

        if (!$hasCurrentValue) {
            $html .= sprintf(
                '<option value="%1$s" selected>%2$s</option>',
                $this->escapeAttribute($value),
                $this->escapeHtml($value)
            );
        }

        return $html . '</select>';
    }

    private function renderFontSelect(string $screen, string $field, string $id, string $value): string
    {
        $html = sprintf(
            '<select id="%1$s" class="wptk-design-select" data-screen="%2$s" data-property="%3$s">
                <option value="">%4$s</option>',
            $this->escapeAttribute($id),
            $this->escapeAttribute($screen),
            $this->escapeAttribute($field),
            $this->escapeHtml($this->translate('Default'))
        );
        $hasCurrentValue = $value === '';

        foreach (self::FONT_FAMILIES as $group => $options) {
            $html .= '<optgroup label="' . $this->escapeAttribute($this->translate($group)) . '">';

            foreach ($options as $optionValue => $label) {
                $hasCurrentValue = $hasCurrentValue || $optionValue === $value;
                $html .= sprintf(
                    '<option value="%1$s"%3$s>%2$s</option>',
                    $this->escapeAttribute($optionValue),
                    $this->escapeHtml($label),
                    $optionValue === $value ? ' selected' : ''
                );
            }

            $html .= '</optgroup>';
        }

        if (!$hasCurrentValue) {
            $html .= sprintf(
                '<optgroup label="%1$s"><option value="%2$s" selected>%3$s</option></optgroup>',
                $this->escapeAttribute($this->translate('Current value')),
                $this->escapeAttribute($value),
                $this->escapeHtml($value)
            );
        }

        return $html . '</select>';
    }

    private function renderAlignment(string $screen, string $field, string $id, string $value): string
    {
        $html = sprintf(
            '<div class="wptk-design-alignment">
                <input id="%1$s" type="hidden" data-screen="%2$s" data-property="%3$s" value="%4$s">',
            $this->escapeAttribute($id),
            $this->escapeAttribute($screen),
            $this->escapeAttribute($field),
            $this->escapeAttribute($value)
        );

        foreach ([
            '' => 'Default',
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
            'justify' => 'Justify',
        ] as $alignment => $label) {
            $html .= sprintf(
                '<button type="button" class="wptk-design-align%4$s" data-align="%1$s" aria-label="%2$s" title="%2$s">
                    <span class="wptk-design-align-icon is-%3$s" aria-hidden="true"></span>
                </button>',
                $this->escapeAttribute($alignment),
                $this->escapeAttribute($this->translate($label)),
                $alignment === '' ? 'default' : $this->escapeAttribute($alignment),
                $alignment === $value ? ' is-selected' : ''
            );
        }

        return $html . '</div>';
    }

    private function renderColorField(string $screen, string $field, string $id, string $value): string
    {
        $pickerValue = preg_match('/^#[0-9a-f]{6}$/i', $value) === 1 ? $value : '#000000';

        return sprintf(
            '<div class="wptk-design-color">
                <input id="%1$s" class="wptk-design-input" type="text" data-screen="%2$s" data-property="%3$s" value="%4$s" autocomplete="off" placeholder="#000000">
                <input class="wptk-design-color-picker" type="color" value="%5$s" aria-label="%6$s">
                <span class="wptk-design-color-preview" aria-hidden="true"></span>
            </div>',
            $this->escapeAttribute($id),
            $this->escapeAttribute($screen),
            $this->escapeAttribute($field),
            $this->escapeAttribute($value),
            $this->escapeAttribute($pickerValue),
            $this->escapeAttribute($this->translate('Choose color'))
        );
    }

    private function renderMediaField(string $screen, string $field, string $id, string $value): string
    {
        return sprintf(
            '<div class="wptk-design-media">
                <button type="button" class="wptk-design-media-button" aria-label="%5$s">
                    <span class="wptk-design-media-plus" aria-hidden="true">+</span>
                    <span class="wptk-design-media-preview" aria-hidden="true"></span>
                </button>
                <div class="wptk-design-media-value">
                    <input id="%1$s" class="wptk-design-input" type="text" data-screen="%2$s" data-property="%3$s" value="%4$s" autocomplete="off" placeholder="%6$s">
                    <button type="button" class="wptk-design-media-remove">%7$s</button>
                </div>
            </div>',
            $this->escapeAttribute($id),
            $this->escapeAttribute($screen),
            $this->escapeAttribute($field),
            $this->escapeAttribute($value),
            $this->escapeAttribute($this->translate('Choose image')),
            $this->escapeAttribute($this->translate('Image URL or attachment ID')),
            $this->escapeHtml($this->translate('Remove'))
        );
    }

    private function isColorField(string $field): bool
    {
        return in_array($field, [
            'color',
            'background-color',
            'border-color',
            'text-shadow-color',
            'box-shadow-color',
        ], true);
    }

    private function renderScript(string $fieldId): string
    {
        $style = str_replace('__ROOT__', '#' . $fieldId, <<<'CSS'
__ROOT__ {
    --wptk-blue: #0878b9;
    --wptk-border: #dcdcde;
    --wptk-field: #f0f0f1;
    --wptk-text: #3c434a;
    color: var(--wptk-text);
    font-size: 14px;
}
__ROOT__ *,
__ROOT__ *::before,
__ROOT__ *::after {
    box-sizing: border-box;
}
__ROOT__ .wptk-design-toolbar {
    align-items: center;
    border-bottom: 1px solid var(--wptk-border);
    display: flex;
    gap: 8px;
    min-height: 58px;
}
__ROOT__ .wptk-design-action {
    background: #f0f0f1;
    border: 0;
    border-radius: 24px;
    box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .12);
    color: #4b535b;
    cursor: pointer;
    font-weight: 600;
    min-width: 112px;
    padding: 11px 20px;
}
__ROOT__ .wptk-design-action:hover,
__ROOT__ .wptk-design-action:focus-visible {
    background: #e5e5e7;
    outline: 2px solid transparent;
}
__ROOT__ .wptk-design-status {
    color: #6c7781;
    margin-left: 4px;
}
__ROOT__ .wptk-design-tabs {
    align-items: stretch;
    background: #f6f7f7;
    border-bottom: 1px solid var(--wptk-border);
    display: flex;
    gap: 0;
    margin: 0;
    overflow-x: auto;
}
__ROOT__ .wptk-design-tab {
    background: transparent;
    border: 0;
    border-bottom: 3px solid transparent;
    color: #50575e;
    cursor: pointer;
    font-weight: 600;
    padding: 13px 18px 11px;
    white-space: nowrap;
}
__ROOT__ .wptk-design-tab:hover {
    color: var(--wptk-blue);
}
__ROOT__ .wptk-design-tab.is-active {
    background: #fff;
    border-bottom-color: var(--wptk-blue);
    color: var(--wptk-blue);
}
__ROOT__ .wptk-design-screen {
    display: none;
}
__ROOT__ .wptk-design-screen.is-active {
    display: block;
}
__ROOT__ .wptk-design-section {
    border-bottom: 1px solid var(--wptk-border);
}
__ROOT__ .wptk-design-section-header {
    align-items: center;
    display: flex;
    min-height: 56px;
}
__ROOT__ .wptk-design-section-toggle {
    align-items: center;
    background: none;
    border: 0;
    color: #424a52;
    cursor: pointer;
    display: flex;
    flex: 1 1 auto;
    font-size: 16px;
    font-weight: 600;
    gap: 12px;
    min-height: 56px;
    padding: 0;
    text-align: left;
}
__ROOT__ .wptk-design-section.is-open .wptk-design-section-toggle {
    color: var(--wptk-blue);
}
__ROOT__ .wptk-design-section-title {
    flex: 0 0 auto;
}
__ROOT__ .wptk-design-responsive-icon {
    border: 2px solid currentColor;
    border-radius: 1px;
    display: inline-block;
    height: 13px;
    opacity: .9;
    position: relative;
    width: 18px;
}
__ROOT__ .wptk-design-responsive-icon::after {
    background: #fff;
    border: 2px solid currentColor;
    border-radius: 1px;
    bottom: -6px;
    content: "";
    height: 11px;
    position: absolute;
    right: -6px;
    width: 8px;
}
__ROOT__ .wptk-design-chevron {
    border-bottom: 2px solid currentColor;
    border-right: 2px solid currentColor;
    height: 8px;
    margin-left: auto;
    margin-right: 4px;
    transform: rotate(45deg);
    transition: transform .16s ease;
    width: 8px;
}
__ROOT__ .wptk-design-section.is-open .wptk-design-chevron {
    transform: rotate(225deg);
}
__ROOT__ .wptk-design-reset,
__ROOT__ .wptk-design-media-remove {
    background: none;
    border: 0;
    color: var(--wptk-blue);
    cursor: pointer;
    padding: 8px 12px;
}
__ROOT__ .wptk-design-reset:hover,
__ROOT__ .wptk-design-media-remove:hover {
    text-decoration: underline;
}
__ROOT__ .wptk-design-section-panel {
    padding: 14px 4px 26px;
}
__ROOT__ .wptk-design-grid {
    display: grid;
    gap: 20px 30px;
    grid-template-columns: repeat(6, minmax(0, 1fr));
}
__ROOT__ .wptk-design-field {
    grid-column: span 2;
    min-width: 0;
}
__ROOT__ .wptk-design-grid.is-two-column .wptk-design-field {
    grid-column: span 3;
}
__ROOT__ .wptk-design-field.is-full-width,
__ROOT__ .wptk-design-field-font_family,
__ROOT__ .wptk-design-field-position,
__ROOT__ .wptk-design-field-animation_name,
__ROOT__ .wptk-design-field-animation_delay {
    grid-column: 1 / -1;
}
__ROOT__ .wptk-design-field-color,
__ROOT__ .wptk-design-field-text_align,
__ROOT__ .wptk-design-field-background_color,
__ROOT__ .wptk-design-field-background_image,
__ROOT__ .wptk-design-field-border_style,
__ROOT__ .wptk-design-field-border_radius,
__ROOT__ .wptk-design-field-border_color {
    grid-column: span 3;
}
__ROOT__ .wptk-design-field label,
__ROOT__ .wptk-design-spacing-heading {
    color: #343b42;
    display: block;
    font-weight: 600;
    margin: 0 0 8px;
}
__ROOT__ .wptk-design-input,
__ROOT__ .wptk-design-select {
    background-color: var(--wptk-field);
    border: 1px solid transparent;
    border-radius: 2px;
    box-shadow: none;
    color: #27323c;
    font-size: 14px;
    height: 44px;
    line-height: 1.4;
    margin: 0;
    max-width: none;
    padding: 0 12px;
    width: 100%;
}
__ROOT__ .wptk-design-select {
    background-position: right 12px center;
    padding-right: 34px;
}
__ROOT__ .wptk-design-input:focus,
__ROOT__ .wptk-design-select:focus {
    background: #fff;
    border-color: #3858e9;
    box-shadow: 0 0 0 1px #3858e9;
    outline: none;
}
__ROOT__ .wptk-design-hint {
    color: #8a9299;
    font-size: 12px;
    line-height: 1.4;
    margin-top: 6px;
}
__ROOT__ .wptk-design-color {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 44px;
    position: relative;
}
__ROOT__ .wptk-design-color-picker {
    border: 0;
    cursor: pointer;
    height: 44px;
    opacity: 0;
    padding: 0;
    position: relative;
    width: 44px;
    z-index: 2;
}
__ROOT__ .wptk-design-color-preview {
    background-color: #fff;
    background-image: linear-gradient(45deg, #c8c8c8 25%, transparent 25%), linear-gradient(-45deg, #c8c8c8 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #c8c8c8 75%), linear-gradient(-45deg, transparent 75%, #c8c8c8 75%);
    background-position: 0 0, 0 6px, 6px -6px, -6px 0;
    background-size: 12px 12px;
    border: 1px solid #c3c4c7;
    bottom: 0;
    pointer-events: none;
    position: absolute;
    right: 0;
    top: 0;
    width: 44px;
}
__ROOT__ .wptk-design-color-preview::after {
    background: var(--wptk-preview-color, transparent);
    bottom: 0;
    content: "";
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}
__ROOT__ .wptk-design-alignment {
    background: var(--wptk-field);
    border-radius: 3px;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    min-height: 44px;
    overflow: hidden;
}
__ROOT__ .wptk-design-align {
    align-items: center;
    background: transparent;
    border: 0;
    color: #3c434a;
    cursor: pointer;
    display: flex;
    justify-content: center;
    min-width: 38px;
}
__ROOT__ .wptk-design-align.is-selected {
    background: #fff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .14);
    color: var(--wptk-blue);
}
__ROOT__ .wptk-design-align-icon {
    background: repeating-linear-gradient(to bottom, currentColor 0 2px, transparent 2px 4px);
    display: block;
    height: 14px;
    position: relative;
    width: 16px;
}
__ROOT__ .wptk-design-align-icon.is-default {
    background: none;
    border: 2px solid currentColor;
    border-radius: 50%;
    height: 15px;
    width: 15px;
}
__ROOT__ .wptk-design-align-icon.is-default::after {
    background: currentColor;
    content: "";
    height: 2px;
    left: -2px;
    position: absolute;
    top: 5px;
    transform: rotate(45deg);
    width: 15px;
}
__ROOT__ .wptk-design-align-icon.is-left {
    clip-path: polygon(0 0, 100% 0, 100% 14%, 0 14%, 0 29%, 70% 29%, 70% 43%, 0 43%, 0 57%, 100% 57%, 100% 71%, 0 71%, 0 86%, 70% 86%, 70% 100%, 0 100%);
}
__ROOT__ .wptk-design-align-icon.is-center {
    clip-path: polygon(0 0, 100% 0, 100% 14%, 0 14%, 15% 29%, 85% 29%, 85% 43%, 15% 43%, 0 57%, 100% 57%, 100% 71%, 0 71%, 15% 86%, 85% 86%, 85% 100%, 15% 100%);
}
__ROOT__ .wptk-design-align-icon.is-right {
    clip-path: polygon(0 0, 100% 0, 100% 100%, 30% 100%, 30% 86%, 100% 86%, 100% 71%, 0 71%, 0 57%, 100% 57%, 100% 43%, 30% 43%, 30% 29%, 100% 29%, 100% 14%, 0 14%);
}
__ROOT__ .wptk-design-media {
    display: grid;
    gap: 10px;
    grid-template-columns: 82px minmax(0, 1fr);
}
__ROOT__ .wptk-design-media-button {
    align-items: center;
    background: var(--wptk-field);
    border: 1px solid transparent;
    cursor: pointer;
    display: flex;
    height: 82px;
    justify-content: center;
    overflow: hidden;
    padding: 0;
    position: relative;
    width: 82px;
}
__ROOT__ .wptk-design-media-button:hover {
    border-color: var(--wptk-blue);
}
__ROOT__ .wptk-design-media-plus {
    font-size: 28px;
    line-height: 1;
    position: relative;
    z-index: 1;
}
__ROOT__ .wptk-design-media-preview {
    background-position: center;
    background-size: cover;
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}
__ROOT__ .wptk-design-media-button.has-image .wptk-design-media-plus {
    display: none;
}
__ROOT__ .wptk-design-media-value {
    align-self: center;
}
__ROOT__ .wptk-design-media-remove {
    padding-left: 0;
}
__ROOT__ .wptk-design-spacing-row {
    margin-bottom: 24px;
    position: relative;
}
__ROOT__ .wptk-design-spacing-row:last-child {
    margin-bottom: 0;
}
__ROOT__ .wptk-design-spacing-grid {
    display: grid;
    gap: 28px;
    grid-template-columns: repeat(4, minmax(0, 1fr));
}
__ROOT__ .wptk-design-spacing-grid .wptk-design-field {
    display: flex;
    flex-direction: column-reverse;
}
__ROOT__ .wptk-design-spacing-grid .wptk-design-field label {
    color: #8a9299;
    font-size: 12px;
    font-weight: 400;
    margin: 6px 0 0;
}
__ROOT__ .wptk-design-link-values {
    background: #fff;
    border: 1px solid var(--wptk-border);
    border-radius: 50%;
    color: #4b535b;
    cursor: pointer;
    height: 28px;
    position: absolute;
    right: 0;
    top: -5px;
    width: 28px;
}
__ROOT__ .wptk-design-link-values[aria-pressed="true"] {
    background: var(--wptk-blue);
    border-color: var(--wptk-blue);
    color: #fff;
}
@media (max-width: 900px) {
    __ROOT__ .wptk-design-grid {
        gap: 18px;
    }
    __ROOT__ .wptk-design-field,
    __ROOT__ .wptk-design-grid.is-two-column .wptk-design-field,
    __ROOT__ .wptk-design-field-color,
    __ROOT__ .wptk-design-field-text_align,
    __ROOT__ .wptk-design-field-background_color,
    __ROOT__ .wptk-design-field-background_image,
    __ROOT__ .wptk-design-field-border_style,
    __ROOT__ .wptk-design-field-border_radius,
    __ROOT__ .wptk-design-field-border_color {
        grid-column: span 3;
    }
}
@media (max-width: 600px) {
    __ROOT__ .wptk-design-toolbar {
        flex-wrap: wrap;
        padding: 10px 0;
    }
    __ROOT__ .wptk-design-action {
        min-width: 0;
    }
    __ROOT__ .wptk-design-field,
    __ROOT__ .wptk-design-grid.is-two-column .wptk-design-field {
        grid-column: 1 / -1;
    }
    __ROOT__ .wptk-design-spacing-grid {
        gap: 12px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
CSS);

        $messages = json_encode([
            'copied' => $this->translate('Design copied'),
            'pasted' => $this->translate('Design pasted'),
            'empty' => $this->translate('Nothing to paste'),
            'invalid' => $this->translate('Clipboard does not contain valid design settings'),
            'reset' => $this->translate('Section reset'),
            'chooseImage' => $this->translate('Choose image'),
            'useImage' => $this->translate('Use image'),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $script = str_replace(
            ['__FIELD_ID__', '__MESSAGES__'],
            [
                json_encode($fieldId, JSON_UNESCAPED_SLASHES),
                is_string($messages) ? $messages : '{}',
            ],
            <<<'JS'
(function () {
    var root = document.getElementById(__FIELD_ID__);
    if (!root || root.dataset.ready === "1") {
        return;
    }

    root.dataset.ready = "1";

    var hidden = root.querySelector(".wptk-design-value");
    var messages = __MESSAGES__;
    var storageKey = "wptkDesignClipboard";
    var statusTimer = null;

    function emitChange(element) {
        var event;
        if (typeof Event === "function") {
            event = new Event("change", {bubbles: true});
        } else {
            event = document.createEvent("Event");
            event.initEvent("change", true, false);
        }
        element.dispatchEvent(event);
    }

    function showStatus(message) {
        var status = root.querySelector(".wptk-design-status");
        if (!status) {
            return;
        }
        window.clearTimeout(statusTimer);
        status.textContent = message || "";
        statusTimer = window.setTimeout(function () {
            status.textContent = "";
        }, 2600);
    }

    function parseValue(raw) {
        if (!raw || typeof raw !== "string") {
            return null;
        }

        var candidates = [raw];
        try {
            candidates.unshift(decodeURIComponent(raw));
        } catch (error) {
            // Keep the original candidate.
        }

        for (var index = 0; index < candidates.length; index += 1) {
            try {
                var parsed = JSON.parse(candidates[index]);
                if (parsed && typeof parsed === "object" && !Array.isArray(parsed)) {
                    return parsed;
                }
            } catch (error) {
                // Try the next format.
            }
        }

        return null;
    }

    function sync() {
        var data = {};

        root.querySelectorAll("[data-property]").forEach(function (field) {
            var screen = field.getAttribute("data-screen");
            var property = field.getAttribute("data-property");
            var value = String(field.value || "").trim();

            if (!value) {
                return;
            }
            if (!data[screen]) {
                data[screen] = {};
            }
            data[screen][property] = value;
        });

        hidden.value = Object.keys(data).length ? encodeURIComponent(JSON.stringify(data)) : "";
        root.dataset.value = hidden.value;
        emitChange(hidden);
    }

    function updateColor(field) {
        var control = field.closest(".wptk-design-color");
        if (!control) {
            return;
        }

        var preview = control.querySelector(".wptk-design-color-preview");
        var picker = control.querySelector(".wptk-design-color-picker");
        var value = String(field.value || "").trim();
        var isHex = /^#[0-9a-f]{6}$/i.test(value);

        preview.style.setProperty("--wptk-preview-color", value || "transparent");
        if (isHex && picker.value.toLowerCase() !== value.toLowerCase()) {
            picker.value = value;
        }
    }

    function mediaUrl(field) {
        var value = String(field.value || "").trim();
        if (field.dataset.previewValue === value && field.dataset.previewUrl) {
            return field.dataset.previewUrl;
        }

        var match = value.match(/^url\((["']?)(.*?)\1\)$/i);
        if (match) {
            value = match[2];
        }

        return /^(https?:)?\/\//i.test(value) || value.charAt(0) === "/" ? value : "";
    }

    function updateMedia(field) {
        var control = field.closest(".wptk-design-media");
        if (!control) {
            return;
        }

        var button = control.querySelector(".wptk-design-media-button");
        var preview = control.querySelector(".wptk-design-media-preview");
        var url = mediaUrl(field);

        button.classList.toggle("has-image", Boolean(url));
        preview.style.backgroundImage = url ? "url(" + JSON.stringify(url) + ")" : "";
    }

    function updateAlignment(field) {
        var control = field.closest(".wptk-design-alignment");
        if (!control) {
            return;
        }

        control.querySelectorAll(".wptk-design-align").forEach(function (button) {
            button.classList.toggle("is-selected", button.getAttribute("data-align") === field.value);
        });
    }

    function updateField(field) {
        updateColor(field);
        updateMedia(field);
        updateAlignment(field);
    }

    function updateLinkedValues(source) {
        var spacing = source.closest(".wptk-design-spacing-row");
        if (!spacing) {
            return;
        }

        var link = spacing.querySelector(".wptk-design-link-values");
        if (!link || link.getAttribute("aria-pressed") !== "true") {
            return;
        }

        spacing.querySelectorAll("[data-property]").forEach(function (field) {
            if (field !== source) {
                field.value = source.value;
                updateField(field);
            }
        });
    }

    function applyData(data) {
        root.querySelectorAll("[data-property]").forEach(function (field) {
            var screen = field.getAttribute("data-screen");
            var property = field.getAttribute("data-property");
            var value = data[screen] && data[screen][property];

            field.value = typeof value === "string" || typeof value === "number" ? String(value) : "";
            delete field.dataset.previewUrl;
            delete field.dataset.previewValue;
            updateField(field);
        });
        sync();
    }

    root.querySelectorAll(".wptk-design-tab").forEach(function (tab) {
        tab.addEventListener("click", function () {
            var screen = tab.getAttribute("data-screen");

            root.querySelectorAll(".wptk-design-tab").forEach(function (item) {
                var active = item === tab;
                item.classList.toggle("is-active", active);
                item.setAttribute("aria-selected", active ? "true" : "false");
            });
            root.querySelectorAll(".wptk-design-screen").forEach(function (item) {
                item.classList.toggle("is-active", item.getAttribute("data-screen") === screen);
            });
        });
    });

    root.querySelectorAll(".wptk-design-section-toggle").forEach(function (toggle) {
        toggle.addEventListener("click", function () {
            var section = toggle.closest(".wptk-design-section");
            var panel = section.querySelector(".wptk-design-section-panel");
            var open = !section.classList.contains("is-open");

            section.classList.toggle("is-open", open);
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
            panel.hidden = !open;
        });
    });

    root.querySelectorAll(".wptk-design-reset").forEach(function (reset) {
        reset.addEventListener("click", function () {
            var section = reset.closest(".wptk-design-section");
            section.querySelectorAll("[data-property]").forEach(function (field) {
                field.value = "";
                delete field.dataset.previewUrl;
                delete field.dataset.previewValue;
                updateField(field);
            });
            sync();
            showStatus(messages.reset);
        });
    });

    root.querySelectorAll("[data-property]").forEach(function (field) {
        field.addEventListener("input", function () {
            updateLinkedValues(field);
            updateField(field);
            sync();
        });
        field.addEventListener("change", function () {
            updateLinkedValues(field);
            updateField(field);
            sync();
        });
        updateField(field);
    });

    root.querySelectorAll(".wptk-design-align").forEach(function (button) {
        button.addEventListener("click", function () {
            var field = button.closest(".wptk-design-alignment").querySelector("[data-property]");
            field.value = button.getAttribute("data-align");
            updateField(field);
            sync();
        });
    });

    root.querySelectorAll(".wptk-design-color-picker").forEach(function (picker) {
        picker.addEventListener("input", function () {
            var field = picker.closest(".wptk-design-color").querySelector("[data-property]");
            field.value = picker.value;
            updateField(field);
            sync();
        });
    });

    root.querySelectorAll(".wptk-design-link-values").forEach(function (link) {
        link.addEventListener("click", function () {
            var pressed = link.getAttribute("aria-pressed") !== "true";
            link.setAttribute("aria-pressed", pressed ? "true" : "false");

            if (pressed) {
                var fields = link.closest(".wptk-design-spacing-row").querySelectorAll("[data-property]");
                var source = Array.prototype.find.call(fields, function (field) {
                    return String(field.value || "").trim() !== "";
                });
                if (source) {
                    updateLinkedValues(source);
                    sync();
                }
            }
        });
    });

    root.querySelectorAll(".wptk-design-media-button").forEach(function (button) {
        button.addEventListener("click", function () {
            var field = button.closest(".wptk-design-media").querySelector("[data-property]");

            if (!window.wp || !window.wp.media) {
                field.focus();
                return;
            }

            var frame = window.wp.media({
                title: messages.chooseImage,
                button: {text: messages.useImage},
                library: {type: "image"},
                multiple: false
            });

            frame.on("select", function () {
                var attachment = frame.state().get("selection").first().toJSON();
                field.value = attachment.id ? String(attachment.id) : attachment.url;
                field.dataset.previewValue = field.value;
                field.dataset.previewUrl = attachment.url || "";
                updateField(field);
                sync();
            });
            frame.open();
        });
    });

    root.querySelectorAll(".wptk-design-media-remove").forEach(function (button) {
        button.addEventListener("click", function () {
            var field = button.closest(".wptk-design-media").querySelector("[data-property]");
            field.value = "";
            delete field.dataset.previewUrl;
            delete field.dataset.previewValue;
            updateField(field);
            sync();
        });
    });

    var copyButton = root.querySelector('[data-action="copy"]');
    var pasteButton = root.querySelector('[data-action="paste"]');

    if (copyButton) {
        copyButton.addEventListener("click", function () {
            var value = hidden.value || "";
            try {
                window.localStorage.setItem(storageKey, value);
            } catch (error) {
                // Clipboard remains available when local storage is blocked.
            }
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(value).catch(function () {});
            }
            showStatus(messages.copied);
        });
    }

    if (pasteButton) {
        pasteButton.addEventListener("click", async function () {
            var values = [];

            if (navigator.clipboard && navigator.clipboard.readText) {
                try {
                    values.push(await navigator.clipboard.readText());
                } catch (error) {
                    // Fall back to the internal design clipboard.
                }
            }
            try {
                values.push(window.localStorage.getItem(storageKey) || "");
            } catch (error) {
                // No internal clipboard is available.
            }

            for (var index = 0; index < values.length; index += 1) {
                var parsed = parseValue(values[index]);
                if (parsed) {
                    applyData(parsed);
                    showStatus(messages.pasted);
                    return;
                }
            }

            showStatus(values.some(Boolean) ? messages.invalid : messages.empty);
        });
    }
})();
JS
        );

        return '<style>' . $style . '</style><script>' . $script . '</script>';
    }

    private function translate(string $text): string
    {
        return function_exists('__') ? __($text, 'js_composer') : $text;
    }

    private function escapeHtml(string $value): string
    {
        if (function_exists('esc_html')) {
            return esc_html($value);
        }

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function escapeAttribute(string $value): string
    {
        if (function_exists('esc_attr')) {
            return esc_attr($value);
        }

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
