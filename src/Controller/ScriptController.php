<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\ScriptType;

class ScriptController
{
    public function addStyle(string $handle, string $filePath, ScriptType $type)
    {
        add_action($type->value, function () use ($handle, $filePath) {
            wp_enqueue_style(
                $handle,
                plugins_url($filePath)
            );
        });
    }

    public function addScript(string $handle, string $filePath, ScriptType $type)
    {
        add_action($type->value, function () use ($handle, $filePath) {
            wp_enqueue_script(
                $handle,
                plugins_url($filePath)
            );
        });
    }

    public function addGutenbergScript(string $handle, string $filePath)
    {
        add_action('enqueue_block_editor_assets', function () use ($handle, $filePath) {
            wp_enqueue_script(
                $handle,
                plugins_url($filePath),
                [
                    'wp-blocks',
                    'wp-i18n',
                    'wp-element',
                    'wp-editor',
                    'wp-components',
                    'wp-data',
                    'wp-plugins',
                    'wp-edit-post',
                    'wp-core-data'
                ],
                true
            );
        });
    }
}
