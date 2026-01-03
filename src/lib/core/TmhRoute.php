<?php

namespace lib\core;

readonly class TmhRoute
{
    public const string DEFAULT_ROUTE = 'umd0xr1h';
    public const string DEFAULT_TITLE = 'nn3zskng';

    private string $route;
    private array $routes;

    public function __construct(private TmhJson $json)
    {
        $this->routes = $this->hydrateAll($this->json->routes());
        $this->initializeRoute();
    }

    public function defaultRoute(): array
    {
        return $this->routes[self::DEFAULT_ROUTE];
    }

    public function get(string $uuid): array
    {
        return in_array($uuid, array_keys($this->routes)) ? $this->hydrate($this->routes[$uuid]) : [];
    }

    public function hydrate(array $route): array
    {
        $href = $route['href'];
        $partsCount = count($href);
        switch ($partsCount) {
            case 0:
                $route['innerHtml'] = self::DEFAULT_TITLE;
                $route['title'] = [self::DEFAULT_TITLE];
                break;
            case 1:
            case 2:
                $last = $route['href'][count($href) - 1];
                $route['innerHtml'] = $last;
                $route['title'] = [$last];
                break;
            case 3:
            case 4:
                $last = $route['href'][count($href) - 1];
                $secondLast = $route['href'][count($href) - 2];
                $route['innerHtml'] = $last;
                $route['title'] = [$secondLast, $last];
                break;
        }
        return $route;
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

    private function hydrateAll(array $routes): array
    {
        $hydrated = [];
        foreach ($routes as $uuid => $route) {
            $hydrated[$uuid] = $this->hydrate($route);
        }
        return $hydrated;
    }
}
