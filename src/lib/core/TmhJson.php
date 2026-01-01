<?php

namespace lib\core;

readonly class TmhJson
{
    public function database(string $file): array
    {
        return $this->loadFile(__DIR__ . '/../../database/', $file);
    }

    public function domains(): array
    {
        return $this->loadFile(__DIR__ . '/../../', 'domains');
    }

    public function entity(string $path, string $file): array
    {
        $path = 0 < strlen($path) ? $path . '/' : $path;
        return $this->loadFile(__DIR__ . '/../../entities/' . $path, $file);
    }

    public function locale(string $locale): array
    {
        return $this->loadFile(__DIR__ . '/../../locales/', $locale);
    }

    public function routes(): array
    {
        return $this->loadFile(__DIR__ . '/../../', 'routes');
    }

    private function loadFile(string $path, string $file): array
    {
        $contents = '[]';
        if ($this->exists($path . $file . '.json')) {
            // echo "<pre>" . 'reading ' . $path . $file . PHP_EOL . "</pre>";
            $contents = file_get_contents($path . $file . '.json');
        } else {
            // echo "<pre>" . 'not reading ' . $path . $file . PHP_EOL . "</pre>";
        }
        return json_decode($contents, true);
    }

    private function exists(string $url): bool
    {
        return (false !== @file_get_contents($url, 0, null, 0, 1));
    }
}
