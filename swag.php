<?php

// https://gist.github.com/connerbw/2c83cfa4a093fc1c1f9291189737cf25
// -------------------------------------------------------------------------------------
// Add your JSON in the $input to generate Swagger-PHP annotation
// Inspired by: https://github.com/Roger13/SwagDefGen
// HOWTO:
//   php -S localhost:8888 -t .
//   http://localhost:8888/swagit.php
// -------------------------------------------------------------------------------------

$tabSize = (int) filter_var($_REQUEST['tabSize'] ?? 4, FILTER_VALIDATE_INT, ['options' => ['default' => 4, 'min_range' => 1, 'max_range' => 255]]);
$tabInit = (int) filter_var($_REQUEST['tabInit'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0, 'max_range' => 255]]);
$input = ($_REQUEST['json'] ?? '');
$output = '';

// -------------------------------------------------------------------------------------
// Class
// -------------------------------------------------------------------------------------

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

// -------------------------------------------------------------------------------------
// Main
// -------------------------------------------------------------------------------------

if ($input) {
    $json = json_decode($input, true);
    if ($json) {
        $swagIt = new SwagIt($tabSize, $tabInit);
        $output = $swagIt->convert($json);
    } else {
        $output = 'Error: Invalid JSON';
    }
}

?>

<div class="container">
    <div class="row">
        <h1>Swag It</h1>
        <p>A tool to help reverse engineer <a href="https://github.com/zircote/swagger-php">Swagger-PHP</a> annotations from an existing JSON payload.
            See <a
                    href="https://gist.github.com/connerbw/2c83cfa4a093fc1c1f9291189737cf25">code</a> for more
            info.</p>
        <form name="form1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
            <div class="row">
                <div class="six columns">
                    <label for="tabSize">Tab Width</label>
                    <input type="number" min=1 max=255 id="tabSize" name="tabSize" value="<?php echo $tabSize; ?>">
                </div>
                <div class="six columns">
                    <label for="tabInit">Initial Tab</label>
                    <input type="number" min=0 max=255 id="tabInit" name="tabInit" value="<?php echo $tabInit; ?>">
                </div>
            </div>
            <label for="json">JSON</label>
            <textarea class="u-full-width heigh" style="height:240px;" id="json" name="json"
                      placeholder="Put your JSON here..."><?php echo htmlentities($input); ?></textarea><br>
            <input class="button-primary" type="submit" value="Submit">
        </form>
        <?php
        if ($output) echo '<pre style="cursor:copy;"><code id="swaggerOutput" ondblclick="copySwagger();">' . htmlentities(trim($output)) . '</code></pre>';
        ?>
    </div>
    <script>
        function copySwagger() {
            const copyText = document.getElementById("swaggerOutput").textContent;
            const textArea = document.createElement("textarea");
            textArea.textContent = copyText;
            document.body.append(textArea);
            textArea.select();
            document.execCommand("copy");
            textArea.remove();
        }
    </script>
