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

        <h1>Swag It</h1>
        <p>A tool to help reverse engineer <a href="https://github.com/zircote/swagger-php">Swagger-PHP</a> annotations from an existing JSON payload.
            See <a
                    href="https://gist.github.com/connerbw/2c83cfa4a093fc1c1f9291189737cf25">code</a> for more
            info.</p>
        <form name="form1" method="post" action="">
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
