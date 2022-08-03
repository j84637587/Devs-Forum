<?php
global $web, $conn;
if (!$_SESSION['usern']) {
    header("Location: " . $web['forum_url'] . "sign_in/");
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">訊息 </div>
    <div class="panel-body">

        <?php
        $p = (int) (!isset($_GET["p"]) ? 1 : $_GET["p"]);
        $limit = 15;
        $startpoint = ($p * $limit) - $limit;
        $user_id = userinfo($_SESSION['usern'], "id");
        //製作分頁
        $statement = "`messages` where `user_id` = $user_id";
        ?>
        <table border="0" cellspacing="2" cellpadding="2" width="100%">
            <?php
        //顯示紀錄
            $query = mysqli_query($conn, "SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
            if (mysqli_num_rows($query) > 0) {
                $i = 0;
                while ($row = mysqli_fetch_array($query)) {
                    $nums = mysqli_num_rows($query);
                    ?>
                    <tr>
                        <td width="10%" align="center"><?php if ($row['readed'] == "yes") { ?>
						<div class="readed" title="Readed"><i class="fa fa-envelope-o" style="font-size:30px;"></i></div>
						<?php } else { ?>
						<div class="unreaded" title="Unreaded"><i class="fa fa-envelope" style="font-size:30px;"></i></div>
						<?php } ?></td>
                        <td width="70%"><a href="<?php echo $web['forum_url']; ?>read_message/<?php echo $row['id']; ?>/" class="forum_link"><?php echo $row['title']; ?></a><br/>Posted on: <?php echo $row['date']; ?></td>
                        <td width="20%" align="center"><b>Posted by:</b><br/><a href="<?php echo $web['forum_url']; ?>user/<?php echo idinfo($row['author_id'], "usern"); ?>/"><?php echo idinfo($row['author_id'], "display_name"); ?></a></td>
                    </tr>
                    <?php
                    $i++;
                    if ($i !== $nums) {
                        echo '<tr><td colspan="3"><hr /></td></tr>';
                    }
                }
            } else {
                ?>
                <tr><td>
				 <?php warning("你尚未收到任何訊息.");?>
				</td></tr>
            <?php
        }
        ?>
        </table>
<?php
$burl = $web['forum_url'] . "messages/";
if (pagination($statement, $burl, $limit, $p)) {
    echo '<br>';
    echo pagination($statement, $burl, $limit, $p);
}
?>

    </div>
</div>
