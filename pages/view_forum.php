<?php
global $conn, $web;
if (!$_SESSION['usern']) {
    $redir = $web['forum_url'] . "sign_in/";
    header("Location: $redir");
}
$id = $_GET['id'];
$sql = mysqli_query($conn, "SELECT * FROM forums WHERE id='$id'");
if (mysqli_num_rows($sql) > 0) {
    $r = mysqli_fetch_array($sql);
    $cat_sql = mysqli_query($conn, "SELECT * FROM categories WHERE id='$r[category_id]'");
    $cat = mysqli_fetch_array($cat_sql);
    $burl = $web['forum_url'] . "f/" . create_forum_link($r['id']) . "/" . $r['id'] . "/";
    $p = (int)(!isset($_GET["p"]) ? 1 : $_GET["p"]);
    $limit = 20;
    $startpoint = ($p * $limit) - $limit;

    //製作分頁
    $statement = "`threads` where `forum_id` = $r[id] and `category_id` = $cat[id]";
    ?>

    <?php if (userinfo($_SESSION['usern'], "level") !== "ban") { ?>
        <table border="0" cellspacing="2" cellpadding="2">
            <tr>
                <td>
                    <form action="<?php echo $web['forum_url']; ?>post/thread/<?php echo $cat['id']; ?>/<?php echo $r['id']; ?>/"
                          method="POST">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-plus"></i> 發布新的文章
                        </button>
                    </form>
                </td>
            </tr>
        </table>
        <br>
    <?php } ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $cat['value']; ?> &raquo; <?php echo $r['value']; ?></h3></div>
        <div class="panel-body">
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
                            <td width="70%">
                                <a href="<?php echo $web['forum_url']; ?>t/<?php echo create_thread_link($row['id']); ?>/<?php echo $row['id']; ?>/" class="forum_link"><?php echo $row['title']; ?></a><br/>發佈於: <?php echo $row['date']; ?>
                            </td>
                            <td width="20%" align="center">
                                <b>作者</b><br/>
                                <a href="<?php echo $web['forum_url']; ?>user/<?php echo idinfo($row['user_id'], "usern"); ?>/"><?php echo idinfo($row['user_id'], "display_name"); ?></a>
                            </td>
                            <td width="10%" align="center">
                                <b>回覆</b><br/><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM replies WHERE forum_id='$r[id]' and thread_id='$row[id]' and category_id='$cat[id]'")); ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                        if ($i !== $nums) {
                            echo '<tr><td colspan="3"><hr /></td></tr>';
                        }
                    }
                } else {
                    echo '<tr><td>此區尚無文章.</td></tr>';
                }
                ?>
            </table>

            <?php
            if (pagination($statement, $burl, $limit, $p)) {
                echo '<br>';
                echo pagination($statement, $burl, $limit, $p);
            }
            ?>
        </div>
    </div>
    <?php
} else {
    header("Location: $web[forum_url]");
}
?>
