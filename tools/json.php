<?php
// works in php 5.4 only (JSON_PRETTY_PRINT for json_encode is a 5.4 feature)
$json = '';

if (isset($_POST['json_text']) && !empty($_POST['json_text'])) {
    $struct = json_decode($_POST['json_text'], true);
    $json = json_encode($struct, JSON_PRETTY_PRINT);
}
?>
<h2>JSON beautifier</h2>

<div class="row">
    <div class="twelve columns">
            <strong>Enter your shit json in here and click submit to get human readable json!</strong>
    </div>
</div>
<div class="row">
    <div class="one-half column">
        <form action="" method="POST">
            <textarea name="json_text" class="u-full-width" placeholder="Paste json here" style="height: 600px"><?php echo $_POST['json_text']; ?></textarea>
            <input type="submit" />
        </form>

    </div>
    <div class="one-half column"><pre><code><?php echo $json; ?></code></pre></div>
</div>