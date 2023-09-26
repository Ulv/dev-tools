<?php

// https://gist.github.com/connerbw/2c83cfa4a093fc1c1f9291189737cf25
class SwagIt
{
    private string $output;
    private int $indent;
    private int $tabSize;
    private int $tabInit;

    public function __construct(int $tabSize = 4, int $tabInit = 0)
    {
        $this->tabSize = $tabSize;
        $this->tabInit = $tabInit;
        $this->indent = 1;
        $this->output = '';
    }

    public function convert(array $jsonNodes): string
    {
        $this->convertObj($jsonNodes);
        return $this->output;
    }

    private function tabs(): string
    {
        return str_repeat(' ', $this->indent * $this->tabSize + $this->tabInit);
    }

    private function isAssoc(array $arr): bool
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private function convertObj(array $jsonNodes): void
    {
        // Array walk a tree of arrays with subarrays of subarrays...
        foreach ($jsonNodes as $key => $value) {
            $this->output .= "\n*{$this->tabs()}@OA\Property(";
            $this->indent++;
            $this->output .= "\n*{$this->tabs()}property=\"{$key}\", ";
            $this->convertItem($value);
            $this->indent--;
            $this->output .= "\n*{$this->tabs()}), ";
        }
    }

    /**
     * @param mixed $value
     */
    private function convertItem($value): void
    {
        if (is_int($value) || is_float($value)) {
            // Number
            $this->output .= "\n*{$this->tabs()}type=\"number\", ";
        } elseif (is_bool($value)) {
            // Boolean
            $this->output .= "\n*{$this->tabs()}type=\"boolean\", ";
        } elseif (is_array($value)) {
            if ($this->isAssoc($value)) {
                // Object
                $this->output .= "\n*{$this->tabs()}type=\"object\", ";
                $this->convertObj($value);
            } else {
                // Array
                $this->output .= "\n*{$this->tabs()}type=\"array\", ";
                $this->convertArray($value);
            }
        } else {
            // String
            $this->convertString($value);
        }
    }

    private function convertString(?string $value): void
    {
        if (is_null($value)) {
            // Nullable
            $this->output .= "\n*{$this->tabs()}format=\"nullable\", ";
        } elseif (preg_match('/^(19|20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/', $value)) {
            // Date
            $this->output .= "\n*{$this->tabs()}format=\"date\", ";
        } elseif (preg_match('/^(19|20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01]).([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9](\.[0-9]{1,2})?(Z|([+\-])([0-1][0-9]|2[0-3]):[0-5][0-9])$/', $value)) {
            // Date-time
            $this->output .= "\n*{$this->tabs()}format=\"date-time\", ";
        }
        $this->output .= "\n*{$this->tabs()}type=\"string\", ";
        if ($value) {
            $value = addslashes($value);
            $this->output .= "\n*{$this->tabs()}example=\"$value\", ";
        }
    }

    private function convertArray(array $value): void
    {
        $this->output .= "\n*{$this->tabs()}@OA\Items(";
        foreach ($value as $v) {
            $this->indent++;
            if (is_array($v)) {
                if ($this->isAssoc($v)) {
                    $this->output .= "\n*{$this->tabs()}type=\"object\", ";
                    $this->convertObj($v);
                } else {
                    $this->output .= "\n*{$this->tabs()}type=\"array\", ";
                    $this->convertArray($v);
                }
            } else {
                $this->convertItem($v);
            }
            $this->indent--;
            // TODO
            //  Handle more than one type in list of @OA\Items
            //  Keywords: oneOf, anyOf, ...
            break;
        }
        $this->output .= "\n*{$this->tabs()}), ";
    }

}
