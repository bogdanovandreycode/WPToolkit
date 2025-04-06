<?php

namespace WpToolKit\Controller;

use WpToolKit\Entity\View;
use Symfony\Component\Yaml\Yaml;

class ViewLoader
{
    /**
     * @var View[]
     */
    private array $views = [];

    public function load(string $name): void
    {
        if (array_key_exists($name, $this->views)) {
            $variables = $this->views[$name]->getVariables();
            extract($variables);
            ob_start();
            require $this->views[$name]->path;
            echo ob_get_clean();
        }
    }

    public function fetchViewContent(string $name): string|false
    {
        if (array_key_exists($name, $this->views)) {
            $variables = $this->views[$name]->getVariables();
            extract($variables);
            ob_start();
            require $this->views[$name]->path;
            return ob_get_clean();
        }

        return false;
    }

    public function add(View $view): void
    {
        if (!in_array($view, $this->views, true)) {
            $this->views[$view->name] = $view;
        }
    }

    public function delete(View $view): void
    {
        $key = array_search($view, $this->views, true);

        if ($key !== false) {
            unset($this->views[$key]);
        }
    }

    public function getView(string $name): ?View
    {
        return $this->views[$name] ?? null;
    }

    public function includeView(string $name, array $data = []): void
    {
        if (array_key_exists($name, $this->views)) {
            $variables = array_merge(
                $this->views[$name]->getVariables(),
                $data
            );

            extract($variables);
            require $this->views[$name]->path;
        }
    }


    public function loadFromYaml(string $yamlPath, string $pluginDir): void
    {
        if (!file_exists($yamlPath)) {
            throw new \InvalidArgumentException("YAML file not found: $yamlPath");
        }

        $data = Yaml::parseFile($yamlPath);

        foreach ($data as $name => $relativePath) {
            $this->add(new View(
                $name,
                $pluginDir . $relativePath,
                []
            ));
        }
    }
}
