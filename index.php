<?php
/**
 * Dev tools
 */
require_once __DIR__.'/src/JsonTools.php';

restore_exception_handler();
restore_error_handler();

const TOOLS_DIR = __DIR__ . '/tools/';

function listTools(): array
{
    $result = [];
    if ($handle = opendir(TOOLS_DIR)) {
        while (false !== ($file = readdir($handle))) {
            if (substr(strrchr($file, '.'), 1) === 'php' && $file !== 'index.php') {
                $result[] = str_replace('.php', '', $file);
            }
        }
        closedir($handle);
    }
    return $result;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Tools</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"
          integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w=="
          crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css"
          integrity="sha512-EZLkOqwILORob+p0BXZc+Vm3RgJBOe1Iq/0fiI7r/wJgzOFZMlsqTa29UEl6v6U6gsV4uIpsNZoV32YZqrCRCQ=="
          crossorigin="anonymous"/>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="twelve columns">
            <strong>DEV TOOLS</strong>&nbsp;
            <a class="button" href="/">Home</a>&nbsp;
            <?php
            $tools = listTools();
            foreach ($tools as $tool) {
                echo "<a class='button button-primary' href='/?tool=$tool'>$tool</a> &nbsp;";
            }
            ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <?php
        $route = (string)filter_input(INPUT_GET, 'tool', FILTER_SANITIZE_ENCODED);
        if ($route && in_array($route, listTools())) {
            require_once TOOLS_DIR . '/' . $route . '.php';
        } else {
            ?>
            <div class="twelve columns">^ click any button for tool</div>
            <?php
        }
        ?>
    </div>
</div>
</body>
</html>
