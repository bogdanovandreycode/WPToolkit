<?php

namespace WpToolKit\Manager;

use InvalidArgumentException;
use Stringable;

class LocaleManager
{
    /**
     * @var array<string, array<string, mixed>|null>
     */
    private array $dictionaryCache = [];

    public function __construct(
        private string $basePath = '',
        private string $defaultLocale = 'en',
        private ?string $fallbackLocale = null,
        private bool $cacheEnabled = true
    ) {
        $this->basePath = $this->normalizeBasePath($basePath);
        $this->defaultLocale = $this->normalizeLocale($defaultLocale);
        $this->fallbackLocale = $fallbackLocale !== null ? $this->normalizeLocale($fallbackLocale) : null;
    }

    public function setBasePath(string $basePath): self
    {
        $this->basePath = $this->normalizeBasePath($basePath);
        $this->flushCache();

        return $this;
    }

    public function setDefaultLocale(string $locale): self
    {
        $this->defaultLocale = $this->normalizeLocale($locale);

        return $this;
    }

    public function setFallbackLocale(?string $locale): self
    {
        $this->fallbackLocale = $locale !== null ? $this->normalizeLocale($locale) : null;

        return $this;
    }

    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    public function getFallbackLocale(): ?string
    {
        return $this->fallbackLocale;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @param array<string, scalar|Stringable|null> $replace
     */
    public function get(string $key, ?string $locale = null, array $replace = []): string
    {
        $resolved = $this->resolveMessage($key, $this->resolveLocale($locale));

        if (!$resolved['found'] && $this->shouldUseFallback($locale)) {
            $resolved = $this->resolveMessage($key, (string) $this->fallbackLocale);
        }

        if (!$resolved['found'] || !$this->isStringable($resolved['value'])) {
            return $key;
        }

        return $this->replace((string) $resolved['value'], $replace);
    }

    public function has(string $key, ?string $locale = null, bool $useFallback = true): bool
    {
        $resolved = $this->resolveMessage($key, $this->resolveLocale($locale));

        if ($resolved['found'] || !$useFallback || !$this->shouldUseFallback($locale)) {
            return $resolved['found'];
        }

        return $this->resolveMessage($key, (string) $this->fallbackLocale)['found'];
    }

    /**
     * Return translation dictionary by dotted file path.
     *
     * @return array<string, mixed>
     */
    public function getDictionary(string $path, ?string $locale = null, bool $useFallback = true): array
    {
        $segments = $this->normalizePathSegments($path);
        $dictionary = $this->loadDictionary($segments, $this->resolveLocale($locale));

        if ($dictionary === null && $useFallback && $this->shouldUseFallback($locale)) {
            $dictionary = $this->loadDictionary($segments, (string) $this->fallbackLocale);
        }

        return $dictionary ?? [];
    }

    public function flushCache(): void
    {
        $this->dictionaryCache = [];
    }

    /**
     * @return array{found: bool, value: mixed}
     */
    private function resolveMessage(string $key, string $locale): array
    {
        $segments = $this->normalizePathSegments($key);
        $count = count($segments);

        if ($count < 2) {
            return ['found' => false, 'value' => null];
        }

        for ($pathLength = $count - 1; $pathLength >= 1; $pathLength--) {
            $dictionary = $this->loadDictionary(array_slice($segments, 0, $pathLength), $locale);

            if ($dictionary === null) {
                continue;
            }

            $found = false;
            $value = $this->arrayGet($dictionary, array_slice($segments, $pathLength), $found);

            if ($found) {
                return ['found' => true, 'value' => $value];
            }
        }

        return ['found' => false, 'value' => null];
    }

    /**
     * @param string[] $segments
     * @return array<string, mixed>|null
     */
    private function loadDictionary(array $segments, string $locale): ?array
    {
        if ($this->basePath === '') {
            return null;
        }

        $cacheKey = $locale . ':' . implode('.', $segments);

        if ($this->cacheEnabled && array_key_exists($cacheKey, $this->dictionaryCache)) {
            return $this->dictionaryCache[$cacheKey];
        }

        $path = $this->basePath
            . DIRECTORY_SEPARATOR
            . $locale
            . DIRECTORY_SEPARATOR
            . implode(DIRECTORY_SEPARATOR, $segments)
            . '.php';

        if (!is_file($path)) {
            return $this->rememberDictionary($cacheKey, null);
        }

        $dictionary = (static function (string $file): mixed {
            return require $file;
        })($path);

        return $this->rememberDictionary($cacheKey, is_array($dictionary) ? $dictionary : null);
    }

    /**
     * @param array<string, mixed>|null $dictionary
     * @return array<string, mixed>|null
     */
    private function rememberDictionary(string $cacheKey, ?array $dictionary): ?array
    {
        if ($this->cacheEnabled) {
            $this->dictionaryCache[$cacheKey] = $dictionary;
        }

        return $dictionary;
    }

    /**
     * @param array<string, mixed> $data
     * @param string[] $segments
     */
    private function arrayGet(array $data, array $segments, bool &$found): mixed
    {
        $found = false;
        $value = $data;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return null;
            }

            $value = $value[$segment];
        }

        $found = true;

        return $value;
    }

    /**
     * @param array<string, scalar|Stringable|null> $replace
     */
    private function replace(string $message, array $replace): string
    {
        foreach ($replace as $search => $value) {
            $message = str_replace(
                [':' . $search, '{' . $search . '}'],
                $this->stringifyReplacement($value),
                $message
            );
        }

        return $message;
    }

    private function stringifyReplacement(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (!$this->isStringable($value)) {
            return '';
        }

        return (string) $value;
    }

    /**
     * @return string[]
     */
    private function normalizePathSegments(string $path): array
    {
        $path = trim($path);

        if ($path === '' || str_contains($path, '..')) {
            throw new InvalidArgumentException('Translation path must not be empty or contain parent directory segments.');
        }

        $segments = preg_split('/[.\/\\\\]+/', trim($path, '.\/\\')) ?: [];
        $segments = array_values(array_filter($segments, static fn (string $segment): bool => $segment !== ''));

        if ($segments === []) {
            throw new InvalidArgumentException('Translation path must contain at least one segment.');
        }

        foreach ($segments as $segment) {
            if ($segment === '.' || $segment === '..') {
                throw new InvalidArgumentException('Translation path contains an invalid segment.');
            }
        }

        return $segments;
    }

    private function normalizeLocale(string $locale): string
    {
        $locale = trim($locale);

        if ($locale === '' || !preg_match('/^[A-Za-z0-9_-]+$/', $locale)) {
            throw new InvalidArgumentException('Locale must contain only letters, numbers, dashes, and underscores.');
        }

        return $locale;
    }

    private function normalizeBasePath(string $basePath): string
    {
        return rtrim($basePath, '/\\');
    }

    private function resolveLocale(?string $locale): string
    {
        return $locale !== null ? $this->normalizeLocale($locale) : $this->defaultLocale;
    }

    private function shouldUseFallback(?string $locale): bool
    {
        $resolvedLocale = $this->resolveLocale($locale);

        return $this->fallbackLocale !== null && $resolvedLocale !== $this->fallbackLocale;
    }

    private function isStringable(mixed $value): bool
    {
        return is_scalar($value) || $value instanceof Stringable;
    }
}
