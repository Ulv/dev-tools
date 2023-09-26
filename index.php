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
            <a class="button button-primary" href="/">Home</a>
            <a class="button button-primary" href="/?tool=swag">Swag It</a>
        </div>
    </div>
</div>

<hr>
<?php
$route = (string)filter_input(INPUT_GET, 'tool', FILTER_SANITIZE_ENCODED);

if ($route && file_exists(__DIR__ . '/' . $route . '.php')) {
    require_once __DIR__ . '/' . $route . '.php';
} else {
?>
    <div class="container">
        <div class="row">
            <div class="twelve columns">^ click button for tool</div>
        </div>
    </div>
<?php
}
?>
</body>
</html>
