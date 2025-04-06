<?php

namespace WpToolKit\Loader;

use ReflectionClass;
use WpToolKit\Attribute\Route;

class AttributeLoader
{
    private string $baseNamespace;
    private string $directory;

    public function __construct(string $baseNamespace, string $directory)
    {
        $this->baseNamespace = rtrim($baseNamespace, '\\');
        $this->directory = rtrim($directory, '/\\');
    }

    public function loadRoutes(): void
    {
        foreach ($this->scanDirectory($this->directory) as $file) {
            require_once $file;

            $relativePath = str_replace([$this->directory, '/', '\\', '.php'], ['', '\\', '\\', ''], $file);
            $class = $this->baseNamespace . '\\' . ltrim($relativePath, '\\');

            if (!class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);
            $attributes = $reflection->getAttributes(Route::class);

            if (!empty($attributes)) {
                new $class();
            }
        }
    }

    /**
     * @return string[]
     */
    private function scanDirectory(string $dir): array
    {
        $files = [];

        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                $files = array_merge($files, $this->scanDirectory($path));
            } elseif (str_ends_with($item, '.php')) {
                $files[] = $path;
            }
        }

        return $files;
    }
}
