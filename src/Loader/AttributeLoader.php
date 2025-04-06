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

    private function getClassFromFile(string $file): string
    {
        $relativePath = str_replace([$this->directory, '/', '\\', '.php'], ['', '\\', '\\', ''], $file);
        return $this->baseNamespace . '\\' . ltrim($relativePath, '\\');
    }

    public function loadRoutes(): void
    {
        foreach ($this->scanDirectory($this->directory) as $file) {
            require_once $file;

            $class = $this->getClassFromFile($file);

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

    private function isSkippable(string $file): bool
    {
        $content = file_get_contents($file);
        $basename = basename($file);

        return
            !str_contains($content, 'class ') ||
            str_starts_with($basename, '_');
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
            } elseif (str_ends_with($item, '.php') && !$this->isSkippable($path)) {
                $files[] = $path;
            }
        }

        return $files;
    }
}
