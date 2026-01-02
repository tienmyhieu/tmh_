<?php

namespace lib\core;

readonly class TmhRoute
{
    public const string DEFAULT_ROUTE = 'umd0xr1h';

    private string $route;
    private array $routes;

    public function __construct(private TmhJson $json)
    {
        $this->routes = $this->json->routes();
        $this->initializeRoute();
    }

    public function defaultRoute(): array
    {
        return $this->routes[self::DEFAULT_ROUTE];
    }

    public function get(string $uuid): array
    {
        return in_array($uuid, array_keys($this->routes)) ? $this->routes[$uuid] : [];
    }

    public function route(): string
    {
        return $this->route;
    }

    public function routes(): array
    {
        return $this->routes;
    }

    private function initializeRoute(): void
    {
        parse_str($_SERVER['REDIRECT_QUERY_STRING'], $fields);
        $this->route = $fields['title'];
    }
}
