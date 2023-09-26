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

    public function getPrintablePhpArray(): string
    {
        $result  = '';
        $decoded = json_decode($this->json, true);

        if ($decoded && is_array($decoded)) {
            $result = var_export($decoded, true);
            $result = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $result);
            $array  = preg_split("/\r\n|\n|\r/", $result);
            $array  = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [null, ']$1', ' => ['], $array);
            $result = join(PHP_EOL, array_filter(["["] + $array));
        }

        return $result;
    }

    public function getBeautifiedJson(): string
    {
        $decoded = json_decode($this->json, true);
        return json_encode($decoded, JSON_PRETTY_PRINT) ?? '';
    }
}