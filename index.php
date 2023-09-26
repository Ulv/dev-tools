<?php
/**
 * Dev tools
 */

const TOOLS_DIR = __DIR__ . '/tools/';
const SRC_DIR   = __DIR__ . '/src';

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

spl_autoload_register(function ($class) {
    $classFile = str_replace('\\', '/', $class) . '.php';
    $filePath  = SRC_DIR . '/' . $classFile;
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>DEV Tools</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css">
    <style>
        .button {
            font-size: 10pt;
            line-height: 18pt;
            height: 18pt;
        }

        code {
            overflow-x: scroll;
        }
    </style>
    <script type="text/javascript" src="/js/func.js"></script>
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
            <div class="twelve columns">click any button for tool ^</div>
            <br><br>
            <h2>Readme:</h2>
            <pre><code><?php echo include 'README.md'; ?></code></pre>
            <?php
        }
        ?>
    </div>
</div>
</body>
</html>
