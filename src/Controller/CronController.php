<?php

namespace WpToolKit\Controller;

abstract class CronController
{
    public function __construct(
        public string $hookName,
        public string $recurrence,
        public ?int $startTimestamp = null
    ) {
        add_action($this->hookName, [$this, 'handle']);

        add_action('init', function (): void {
            $this->schedule();
        });
    }

    public function schedule(): void
    {
        if (wp_next_scheduled($this->hookName)) {
            return;
        }

        wp_schedule_event(
            $this->startTimestamp ?? time(),
            $this->recurrence,
            $this->hookName
        );
    }

    public function unschedule(): void
    {
        $timestamp = wp_next_scheduled($this->hookName);

        if ($timestamp !== false) {
            wp_unschedule_event($timestamp, $this->hookName);
        }
    }

    abstract public function handle(): void;
}
