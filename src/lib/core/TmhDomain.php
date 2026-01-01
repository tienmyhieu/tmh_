<?php

namespace lib\core;

readonly class TmhDomain
{
    private const string DEFAULT_DOMAIN = 'vi';

    private array $domain;
    private array $domains;

    public function __construct(private TmhJson $json)
    {
        $this->domains = $this->json->domains();
        $this->initializeDomain();
    }

    public function getLanguage(): string
    {
        return substr($this->domain['locale'], 0, 2);
    }

    public function getLocale(): string
    {
        return $this->domain['locale'];
    }

    private function initializeDomain(): void
    {
        $domainParts = explode('.', $_SERVER['SERVER_NAME']);
        $domain = array_shift($domainParts);
        $isValidDomain = in_array($domain, array_keys($this->domains));
        $this->domain = $isValidDomain ? $this->domains[$domain] : $this->domains[self::DEFAULT_DOMAIN];
    }
}
