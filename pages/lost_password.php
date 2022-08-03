<?php
global $web;
if (isset($_SESSION['usern']) && $_SESSION['usern']) {
    header("Location: $web[forum_url]");
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Lost password</h3>
    </div>
    <div class="panel-body">
        <?php
            info("Please wait to next update.");
        ?>
    </div>
</div>
