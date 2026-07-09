<?php

namespace WpToolKit\Factory;

use InvalidArgumentException;
use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionNamedType;
use WpToolKit\Controller\MenuController;
use WpToolKit\Controller\ScriptController;
use WpToolKit\Manager\LocaleManager;
use WpToolKit\Manager\WpBakeryDesignManager;

final class ServiceFactory
{
    /**
     * @var array<string, array{concrete: Closure|string, shared: bool}>
     */
    private array $bindings = [];

    /**
     * @var array<string, object>
     */
    private array $instances = [];

    /**
     * @var array<string, string>
     */
    private array $aliases = [];

    public function __construct()
    {
        $this->registerDefaults();
    }

    public function bind(string $abstract, Closure|string|null $concrete = null): self
    {
        return $this->register($abstract, $concrete ?? $abstract, false);
    }

    public function singleton(string $abstract, Closure|string|null $concrete = null): self
    {
        return $this->register($abstract, $concrete ?? $abstract, true);
    }

    public function instance(string $abstract, object $instance): object
    {
        $abstract = $this->normalize($abstract);

        $this->instances[$abstract] = $instance;
        $this->alias($abstract, $this->shortName($abstract));

        return $instance;
    }

    public function alias(string $abstract, string $alias): self
    {
        $this->aliases[$alias] = $this->normalize($abstract);

        return $this;
    }

    public function has(string $abstract): bool
    {
        $abstract = $this->normalize($abstract);

        return isset($this->instances[$abstract])
            || isset($this->bindings[$abstract])
            || class_exists($abstract)
            || isset($this->aliases[$abstract]);
    }

    public function make(string $abstract, array $parameters = []): mixed
    {
        $abstract = $this->normalize($abstract);

        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $binding = $this->bindings[$abstract] ?? null;
        $concrete = $binding['concrete'] ?? $abstract;
        $shared = $binding['shared'] ?? false;

        $instance = $this->build($concrete, $parameters);

        if ($shared && is_object($instance)) {
            $this->instances[$abstract] = $instance;
        }

        return $instance;
    }

    public function get(string $abstract, array $parameters = []): mixed
    {
        return $this->make($abstract, $parameters);
    }

    public function call(callable|array|string $callback, array $parameters = []): mixed
    {
        if (is_string($callback) && str_contains($callback, '@')) {
            [$class, $method] = explode('@', $callback, 2);

            return $this->call([$this->make($class), $method], $parameters);
        }

        $reflection = match (true) {
            is_array($callback) => new ReflectionMethod($callback[0], $callback[1]),
            is_string($callback) && class_exists($callback) => new ReflectionMethod($callback, '__invoke'),
            default => new ReflectionFunction($callback),
        };

        $resolvedParameters = $this->resolveParameters($reflection, $parameters);

        if (is_string($callback) && class_exists($callback)) {
            return $this->make($callback)(...$resolvedParameters);
        }

        return $callback(...$resolvedParameters);
    }

    private function register(string $abstract, Closure|string $concrete, bool $shared): self
    {
        $abstract = $this->normalize($abstract);

        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => $shared,
        ];

        $this->alias($abstract, $this->shortName($abstract));

        if (is_string($concrete) && class_exists($concrete)) {
            $this->alias($abstract, $this->shortName($concrete));
        }

        return $this;
    }

    private function build(Closure|string $concrete, array $parameters = []): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this, $parameters);
        }

        $className = $this->normalize($concrete);

        if (!class_exists($className)) {
            throw new InvalidArgumentException("No service found for [$className].");
        }

        try {
            $reflection = new ReflectionClass($className);
        } catch (ReflectionException $exception) {
            throw new InvalidArgumentException("Unable to reflect service [$className].", 0, $exception);
        }

        if (!$reflection->isInstantiable()) {
            throw new InvalidArgumentException("Service [$className] is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $className();
        }

        $resolvedParameters = $this->resolveParameters($constructor, $parameters);

        return $reflection->newInstanceArgs($resolvedParameters);
    }

    private function resolveParameters(ReflectionFunctionAbstract $reflection, array $parameters): array
    {
        $resolved = [];

        foreach ($reflection->getParameters() as $parameter) {
            $name = $parameter->getName();

            if (array_key_exists($name, $parameters)) {
                $resolved[] = $parameters[$name];
                continue;
            }

            if (array_key_exists($parameter->getPosition(), $parameters)) {
                $resolved[] = $parameters[$parameter->getPosition()];
                continue;
            }

            $type = $parameter->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $resolved[] = $this->make($type->getName());
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $resolved[] = $parameter->getDefaultValue();
                continue;
            }

            if ($parameter->allowsNull()) {
                $resolved[] = null;
                continue;
            }

            throw new InvalidArgumentException(
                sprintf(
                    'Unable to resolve parameter [%s] for [%s].',
                    $name,
                    $reflection->getName()
                )
            );
        }

        return $resolved;
    }

    private function normalize(string $abstract): string
    {
        return $this->aliases[$abstract] ?? $abstract;
    }

    private function shortName(string $className): string
    {
        $parts = explode('\\', $className);

        return (string) end($parts);
    }

    private function registerDefaults(): void
    {
        $this->singleton(LocaleManager::class);
        $this->singleton(MenuController::class);
        $this->singleton(ScriptController::class);
        $this->singleton(WpBakeryDesignManager::class);
    }
}
