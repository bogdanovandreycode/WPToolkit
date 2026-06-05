<?php

namespace WpToolKit\Manager;

class NoticeManager
{
    /**
     * @var array<int, array{message: string, type: string, dismissible: bool}>
     */
    private array $notices = [];

    public function __construct()
    {
        add_action('admin_notices', [$this, 'render']);
    }

    public function success(string $message, bool $dismissible = true): void
    {
        $this->add($message, 'success', $dismissible);
    }

    public function error(string $message, bool $dismissible = true): void
    {
        $this->add($message, 'error', $dismissible);
    }

    public function warning(string $message, bool $dismissible = true): void
    {
        $this->add($message, 'warning', $dismissible);
    }

    public function info(string $message, bool $dismissible = true): void
    {
        $this->add($message, 'info', $dismissible);
    }

    public function add(string $message, string $type = 'info', bool $dismissible = true): void
    {
        $this->notices[] = [
            'message' => $message,
            'type' => $type,
            'dismissible' => $dismissible,
        ];
    }

    public function render(): void
    {
        foreach ($this->notices as $notice) {
            $classes = ['notice', 'notice-' . sanitize_html_class($notice['type'])];

            if ($notice['dismissible']) {
                $classes[] = 'is-dismissible';
            }

            printf(
                '<div class="%s"><p>%s</p></div>',
                esc_attr(implode(' ', $classes)),
                esc_html($notice['message'])
            );
        }
    }
}
