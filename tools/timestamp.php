<h2>EPOCH time converter</h2>
<div class="row">
    <div class="one-half column">
        <form action="" method="POST">
            <label for="timestamp">Unix timestamp:</label>
            <input type="number" name="timestamp" id="timestamp">
            <input type="submit"/>
        </form>
    </div>
<div class="one-half column">
    <div class="row">
        Current timestamp: <strong><?php echo time(); ?></strong>
        <h5>Result:</h5>
        <pre><code><?php echo (new Timestamp($_POST['timestamp'] ?? 0))->getDateTime(); ?></code></pre>
    </div>
</div>
