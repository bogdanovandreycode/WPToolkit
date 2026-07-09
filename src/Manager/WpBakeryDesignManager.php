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
        'Typography' => [
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
        'Shadow' => [
            'text-shadow-h-offset',
            'text-shadow-v-offset',
            'text-shadow-blur',
            'text-shadow-color',
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
        'text-align' => [
            '' => '',
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
            'justify' => 'Justify',
        ],
        'text-transform' => [
            '' => '',
            'none' => 'None',
            'uppercase' => 'Uppercase',
            'lowercase' => 'Lowercase',
            'capitalize' => 'Capitalize',
        ],
        'font-style' => [
            '' => '',
            'normal' => 'Normal',
            'italic' => 'Italic',
            'oblique' => 'Oblique',
        ],
        'border-style' => [
            '' => '',
            'none' => 'None',
            'solid' => 'Solid',
            'dashed' => 'Dashed',
            'dotted' => 'Dotted',
            'double' => 'Double',
        ],
        'position' => [
            '' => '',
            'static' => 'Static',
            'relative' => 'Relative',
            'absolute' => 'Absolute',
            'fixed' => 'Fixed',
            'sticky' => 'Sticky',
        ],
        'overflow' => [
            '' => '',
            'visible' => 'Visible',
            'hidden' => 'Hidden',
            'auto' => 'Auto',
            'scroll' => 'Scroll',
        ],
        'background-size' => [
            '' => '',
            'auto' => 'Auto',
            'cover' => 'Cover',
            'contain' => 'Contain',
        ],
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
        $html = '<div class="wptk-design-tabs">';
        $first = true;

        foreach (self::SCREENS as $screen => $config) {
            $html .= sprintf(
                '<button type="button" class="button wptk-design-tab%3$s" data-screen="%1$s">%2$s</button>',
                $this->escapeAttribute($screen),
                $this->escapeHtml($config['label']),
                $first ? ' is-active' : ''
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
        $first = true;

        foreach (self::SCREENS as $screen => $config) {
            $html .= sprintf(
                '<div class="wptk-design-screen%2$s" data-screen="%1$s">',
                $this->escapeAttribute($screen),
                $first ? ' is-active' : ''
            );

            foreach (self::FIELDS as $group => $fields) {
                $html .= '<fieldset><legend>' . $this->escapeHtml($group) . '</legend>';

                foreach ($fields as $field) {
                    $html .= $this->renderField($fieldId, $screen, $field, $data[$screen][$field] ?? '');
                }

                $html .= '</fieldset>';
            }

            $html .= '</div>';
            $first = false;
        }

        return $html . '</div>';
    }

    private function renderField(string $fieldId, string $screen, string $field, string $value): string
    {
        $id = $fieldId . '-' . md5($screen . $field);
        $input = isset(self::SELECT_FIELDS[$field])
            ? $this->renderSelect($screen, $field, $id, $value)
            : $this->renderInput($screen, $field, $id, $value);

        return sprintf(
            '<label for="%1$s"><span>%2$s</span>%3$s</label>',
            $this->escapeAttribute($id),
            $this->escapeHtml($field),
            $input
        );
    }

    private function renderInput(string $screen, string $field, string $id, string $value): string
    {
        return sprintf(
            '<input id="%1$s" type="text" data-screen="%2$s" data-property="%3$s" value="%4$s">',
            $this->escapeAttribute($id),
            $this->escapeAttribute($screen),
            $this->escapeAttribute($field),
            $this->escapeAttribute($value)
        );
    }

    private function renderSelect(string $screen, string $field, string $id, string $value): string
    {
        $html = sprintf(
            '<select id="%1$s" data-screen="%2$s" data-property="%3$s">',
            $this->escapeAttribute($id),
            $this->escapeAttribute($screen),
            $this->escapeAttribute($field)
        );

        foreach (self::SELECT_FIELDS[$field] as $optionValue => $label) {
            $html .= sprintf(
                '<option value="%1$s"%3$s>%2$s</option>',
                $this->escapeAttribute($optionValue),
                $this->escapeHtml($label),
                $optionValue === $value ? ' selected' : ''
            );
        }

        return $html . '</select>';
    }

    private function renderScript(string $fieldId): string
    {
        return sprintf(
            '<style>
                #%1$s .wptk-design-tabs{margin-bottom:10px}
                #%1$s .wptk-design-tab{margin-right:4px}
                #%1$s .wptk-design-tab.is-active{background:#2271b1;border-color:#2271b1;color:#fff}
                #%1$s .wptk-design-screen{display:none}
                #%1$s .wptk-design-screen.is-active{display:block}
                #%1$s fieldset{border:1px solid #ccd0d4;margin:0 0 12px;padding:10px}
                #%1$s legend{font-weight:600;padding:0 4px}
                #%1$s label{display:grid;grid-template-columns:170px minmax(160px,1fr);gap:8px;align-items:center;margin:0 0 8px}
                #%1$s input,#%1$s select{width:100%%}
            </style>
            <script>
                (function(){
                    var root = document.getElementById("%1$s");
                    if (!root || root.dataset.ready === "1") {
                        return;
                    }
                    root.dataset.ready = "1";
                    var hidden = root.querySelector(".wptk-design-value");
                    var data = {};
                    function sync(){
                        data = {};
                        root.querySelectorAll("[data-property]").forEach(function(field){
                            var screen = field.getAttribute("data-screen");
                            var property = field.getAttribute("data-property");
                            var value = field.value.trim();
                            if (!value) {
                                return;
                            }
                            if (!data[screen]) {
                                data[screen] = {};
                            }
                            data[screen][property] = value;
                        });
                        hidden.value = Object.keys(data).length ? encodeURIComponent(JSON.stringify(data)) : "";
                        hidden.dispatchEvent(new Event("change", {bubbles:true}));
                    }
                    root.querySelectorAll(".wptk-design-tab").forEach(function(tab){
                        tab.addEventListener("click", function(){
                            var screen = tab.getAttribute("data-screen");
                            root.querySelectorAll(".wptk-design-tab,.wptk-design-screen").forEach(function(item){
                                item.classList.remove("is-active");
                            });
                            tab.classList.add("is-active");
                            root.querySelector(".wptk-design-screen[data-screen=\'" + screen + "\']").classList.add("is-active");
                        });
                    });
                    root.querySelectorAll("[data-property]").forEach(function(field){
                        field.addEventListener("input", sync);
                        field.addEventListener("change", sync);
                    });
                    sync();
                })();
            </script>',
            $this->escapeAttribute($fieldId)
        );
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
