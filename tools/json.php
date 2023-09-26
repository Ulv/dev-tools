<?php
// works in php 5.4 only (JSON_PRETTY_PRINT for json_encode is a 5.4 feature)
$json = '';

if (isset($_POST['json_text']) && !empty($_POST['json_text'])) {
    $struct = json_decode($_POST['json_text'], true);
    $json = json_encode($struct, JSON_PRETTY_PRINT);
    header('Content-Type: text/plain');
    echo $json;
    exit;
}
?>
<!DOCTYPE html>

<html>

<head>
    <title>JSON Formatter</title>
</head>

<body>

<div>
    <strong>Enter your shit json in here and click submit to get human readable json!</strong>
</div>
<form action="" method="POST">
    <textarea name="json_text" style="width: 900px; height: 500px;"></textarea>
    <input type="submit" />
</form>

</body>

</html>