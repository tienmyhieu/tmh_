<?php

namespace lib\core;

readonly class TmhEntityController
{
    public function __construct(private TmhJson $json, private TmhRouteController $routeController)
    {
    }

    public function find(): array
    {
        $route = $this->routeController->find();
        $entity = $this->getEntityByRouteCode($route['code']);
        $entity['template'] = $this->getTemplate($route['type']);
        return array_merge($route, $entity);
    }

    private function getEntityByRouteCode(string $routeCode): array
    {
        $pathParts = explode('.', $routeCode);
        $entityFile = $pathParts[count($pathParts) - 1];
        unset($pathParts[count($pathParts) - 1]);
        $entityDirectory = implode('/', $pathParts);
        return $this->json->entity($entityDirectory, $entityFile);
    }

    private function getTemplate(string $routeType): string
    {
        return match($routeType) {
            'metal_emperor_coin_specimen' => 'specimen',
            default => 'lists'
        };
    }
}
