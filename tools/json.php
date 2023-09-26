<?php
$jsonTools = new JsonTools($_POST['json_text'] ?? '');
?>
<h2>JSON beautifier</h2>

<div class="row">
    <div class="one-half column">
        <form action="" method="POST">
            <textarea name="json_text" class="u-full-width" placeholder="Paste json here" style="height: 600px"><?php echo $jsonTools->getOriginalJson(); ?></textarea>
            <input type="submit" />
        </form>

    </div>
    <div class="one-half column"><pre><code><?php echo $jsonTools->getBeautifiedJson(); ?></code></pre></div>
</div>