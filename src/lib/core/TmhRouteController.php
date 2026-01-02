<?php

namespace lib\core;

class TmhRouteController
{
    private array $locales;
    private string $requestedRoute;
    private array $routeKeys;
    private array $routeParts;
    private array $routes;
    private string $uuid;

    public function __construct(
        private readonly TmhDomain $domain,
        private readonly TmhLocale $locale,
        private readonly TmhRoute $route
    ) {
        $this->locales = $this->locale->locales();
        $this->requestedRoute = $this->route->route();
        $this->routes = $this->route->routes();
        $this->initializeRouteKeys();
    }

    public function find(): array
    {
        if (in_array($this->requestedRoute, array_keys($this->routeKeys))) {
            return $this->currentRoute();
        }

        $this->routeParts = explode('/', $this->requestedRoute);
        if (1 < count($this->routeParts)) {
            return $this->childRoute();
        }

        return $this->defaultRoute();
    }

    public function parent(): array
    {
        $this->routeParts = explode('/', $this->requestedRoute);
        return $this->ancestorRoute();
    }

    public function siblings(): array
    {
        $siblings = [];
        $currentDomain = $this->domain->domain();
        $domains = $this->domain->domains();
        return $siblings;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    private function ancestorRoute(): array
    {
        if (1 < count($this->routeParts)) {
            unset($this->routeParts[count($this->routeParts) - 1]);
            $ancestorRoute = implode('/', $this->routeParts);
            if (in_array($ancestorRoute, array_keys($this->routeKeys))) {
                return $this->routes[$this->routeKeys[$ancestorRoute]];
            } else {
                return $this->ancestorRoute();
            }
        }
        return $this->route->defaultRoute();
    }

    private function childRoute(): array
    {
        $requestedEntity = strtolower($this->routeParts[count($this->routeParts) - 1]);
        $ancestorRoute = $this->ancestorRoute();
        $childRoute = $ancestorRoute;
        if ($ancestorRoute['type'] === 'metal_emperor_coin') {
            $childRoute['code'] = $ancestorRoute['code'] . '.' . $requestedEntity;
            $childRoute['type'] = $ancestorRoute['type'] . '_specimen';
            $this->uuid = '';
            $childRoute['uuid'] = $this->uuid;
        }
        return $childRoute;
    }

    private function currentRoute(): array
    {
        $this->uuid = $this->routeKeys[$this->requestedRoute];
        $currentRoute = $this->routes[$this->uuid];
        $currentRoute['uuid'] = $this->uuid;
        return $currentRoute;
    }

    private function defaultRoute(): array
    {
        $defaultRoute = $this->route->defaultRoute();
        $this->uuid = TmhRoute::DEFAULT_ROUTE;
        $defaultRoute['uuid'] = $this->uuid;
        return $defaultRoute;
    }

    private function initializeRouteKeys(): void
    {
        $transformed = [];
        $patterns = ["'", ' ', '、', '-', '.', "'"];
        $replacements = ['', '_', '', '_', '_', ''];
        foreach ($this->routes as $routeKey => $route) {
            $key = '';
            foreach ($route['href'] as $href) {
                if (in_array($href, array_keys($this->locales))) {
                    $key .= str_replace($patterns, $replacements, $this->locales[$href]) . '/';
                }
            }
            $key = substr($key, 0, -1);
            $transformed[$key] = $routeKey;
        }
        $this->routeKeys = $transformed;
    }
}
