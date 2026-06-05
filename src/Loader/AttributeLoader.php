<?php

namespace WpToolKit\Loader;

use InvalidArgumentException;
use ReflectionClass;
use WpToolKit\Attribute\Action;
use WpToolKit\Attribute\Ajax;
use WpToolKit\Attribute\ControllerAttributeInterface;
use WpToolKit\Attribute\Cron;
use WpToolKit\Attribute\Filter;
use WpToolKit\Attribute\MetaBox;
use WpToolKit\Attribute\Page;
use WpToolKit\Attribute\Route;
use WpToolKit\Attribute\Shortcode;
use WpToolKit\Attribute\Widget;
use WpToolKit\Controller\ActionController;
use WpToolKit\Controller\AjaxController;
use WpToolKit\Controller\AdminPage;
use WpToolKit\Controller\CronController;
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
        Ajax::class => AjaxController::class,
        Cron::class => CronController::class,
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

            $parameters = $this->resolveParameters($reflection);

            if ($parameters !== null) {
                $this->serviceFactory->make($class, $parameters);
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

    /**
     * @return array<string, mixed>|null
     */
    private function resolveParameters(ReflectionClass $reflection): ?array
    {
        $matchedAttribute = null;

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

            if ($matchedAttribute !== null) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Class [%s] cannot use multiple controller attributes at the same time.',
                        $reflection->getName()
                    )
                );
            }

            $attributeInstance = $attributes[0]->newInstance();

            if (!$attributeInstance instanceof ControllerAttributeInterface) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Attribute [%s] must implement [%s].',
                        $attributeClass,
                        ControllerAttributeInterface::class
                    )
                );
            }

            $matchedAttribute = $attributeInstance->toParameters($this->serviceFactory);
        }

        return $matchedAttribute;
    }
}
