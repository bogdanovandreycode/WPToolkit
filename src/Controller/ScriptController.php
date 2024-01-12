<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\ScriptType;

class ScriptController
{
    private static string $folderCss = 'understrap-estate/assets/style';
    private static string $folderJs = 'understrap-estate/assets/script';

    public function setFolders(string $folderCss, string $folderJs)
    {
        self::$folderCss = $folderCss;
        self::$folderJs = $folderJs;
    }

    public static function addStyle(string $handle, string $fileName, ScriptType $type)
    {
        add_action($type->value, function () use ($handle, $fileName) {
            wp_enqueue_style(
                $handle,
                plugins_url(self::$folderCss . '/' . $fileName)
            );
        });
    }

    public static function addScript(string $handle, string $fileName, ScriptType $type)
    {
        add_action($type->value, function () use ($handle, $fileName) {
            wp_enqueue_script(
                $handle,
                plugins_url(self::$folderJs . '/' . $fileName)
            );
        });
    }

    public static function addGutenbergScript(string $handle, string $fileName)
    {
        add_action('enqueue_block_editor_assets', function () use ($handle, $fileName) {
            wp_enqueue_script(
                $handle,
                plugins_url(self::$folderJs . '/' . $fileName),
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
