<?php

class JsonTools
{
    protected string $json;

    public function __construct(string $json)
    {
        $this->json = $json;
    }

    public function getOriginalJson(): string
    {
        return $this->json;
    }

    public function getBeautifiedJson(): string
    {
        $decoded = json_decode($this->json, true);
        return json_encode($decoded, JSON_PRETTY_PRINT) ?? '';
    }
}