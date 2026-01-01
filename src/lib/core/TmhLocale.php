<?php

namespace lib\core;

readonly class TmhLocale
{
    private string $language;
    private string $locale;
    private array $locales;

    public function __construct(private TmhDomain $domain, private TmhJson $json)
    {
        $this->language = $this->domain->getLanguage();
        $this->locale = $this->domain->getLocale();
        $this->locales = $this->json->locale($this->locale);
    }

    public function get(string $uuid): string
    {
        return in_array($uuid, array_keys($this->locales)) ? $this->locales[$uuid] : $uuid;
    }

    public function getMany(array $uuids): array
    {
        return array_map(function ($uuid) { return $this->get($uuid); }, $uuids);
    }

    public function language(): string
    {
        return $this->language;
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function locales(): array
    {
        return $this->locales;
    }
}
