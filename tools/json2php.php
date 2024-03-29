<?php
$jsonTools = new JsonTools($_POST['json_text'] ?? '');
?>
<h2>JSON object 2 PHP array</h2>

<div class="row">
    <div class="one-half column">
        <form action="" method="POST" id="json_form">
            <div id="error"></div>
            <textarea name="json_text" id="json_text" class="u-full-width" placeholder="Paste json here"
                      style="height: 600px"><?php echo $jsonTools->getOriginalJson(); ?></textarea>
            <input type="submit"/>
        </form>

    </div>
    <div class="one-half column">
        <div class="row">
            <div class="twelve columns">
                <button class="button u-pull-right" onclick="copy2Clipboard()">Copy</button>
            </div>
        </div>
        <pre><code id="code"><?php echo $jsonTools->getPrintablePhpArray(); ?></code></pre>
    </div>
</div>