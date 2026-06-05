<?php

namespace WpToolKit\Loader;

use InvalidArgumentException;
use ReflectionClass;
use WpToolKit\Attribute\Action;
use WpToolKit\Attribute\Filter;
use WpToolKit\Attribute\MetaBox;
use WpToolKit\Attribute\Page;
use WpToolKit\Attribute\Route;
use WpToolKit\Attribute\Shortcode;
use WpToolKit\Attribute\Widget;
use WpToolKit\Controller\ActionController;
use WpToolKit\Controller\AdminPage;
use WpToolKit\Controller\FilterController;
use WpToolKit\Controller\MetaBoxController;
use WpToolKit\Controller\RouteController;
use WpToolKit\Controller\ShortcodeController;
use WpToolKit\Controller\WidgetsController;
use WpToolKit\Factory\ServiceFactory;

class AttributeLoader
{
    /**
     * @var array<class-string, class-string>
     */
    private const ATTRIBUTE_CLASS_MAP = [
        Route::class => RouteController::class,
        Action::class => ActionController::class,
        Filter::class => FilterController::class,
        MetaBox::class => MetaBoxController::class,
        Page::class => AdminPage::class,
        Shortcode::class => ShortcodeController::class,
        Widget::class => WidgetsController::class,
    ];

    private string $baseNamespace;
    private string $directory;
    private ServiceFactory $serviceFactory;

    public function __construct(
        string $baseNamespace,
        string $directory,
        ?ServiceFactory $serviceFactory = null
    ) {
        $this->baseNamespace = rtrim($baseNamespace, '\\');
        $this->directory = rtrim($directory, '/\\');
        $this->serviceFactory = $serviceFactory ?? new ServiceFactory();
    }

    public function loadRoutes(): void
    {
        $this->loadControllers();
    }

    public function loadControllers(): void
    {
        foreach ($this->scanDirectory($this->directory) as $file) {
            require_once $file;

            $class = $this->getClassFromFile($file);

            if (!class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            if ($reflection->isAbstract()) {
                continue;
            }

            if ($this->shouldInstantiate($reflection)) {
                $this->serviceFactory->make($class);
            }
        }
    }

    private function getClassFromFile(string $file): string
    {
        $relativePath = str_replace(
            [$this->directory, '/', '\\', '.php'],
            ['', '\\', '\\', ''],
            $file
        );

        return $this->baseNamespace . '\\' . ltrim($relativePath, '\\');
    }

    private function isSkippable(string $file): bool
    {
        $content = file_get_contents($file);
        $basename = basename($file);

        return !str_contains($content, 'class ')
            || str_starts_with($basename, '_');
    }

    /**
     * @return string[]
     */
    private function scanDirectory(string $dir): array
    {
        $files = [];

        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                $files = array_merge($files, $this->scanDirectory($path));
                continue;
            }

            if (str_ends_with($item, '.php') && !$this->isSkippable($path)) {
                $files[] = $path;
            }
        }

        return $files;
    }

    private function shouldInstantiate(ReflectionClass $reflection): bool
    {
        $shouldInstantiate = false;

        foreach (self::ATTRIBUTE_CLASS_MAP as $attributeClass => $parentClass) {
            $attributes = $reflection->getAttributes($attributeClass);

            if ($attributes === []) {
                continue;
            }

            if (!$reflection->isSubclassOf($parentClass)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Class [%s] uses attribute [%s] but does not extend [%s].',
                        $reflection->getName(),
                        $attributeClass,
                        $parentClass
                    )
                );
            }

            $shouldInstantiate = true;
        }

        return $shouldInstantiate;
    }
}
