<?php
global $conn, $web;
if (!$_SESSION['usern']) {
    header("Location: " . $web['forum_url'] . "sign_in/");
}

if (empty($_SESSION['search_text'])) {
    $_SESSION['search_text'] = isset($_POST['search_name']) ? protect($_POST['search_name']) : "";
}

if (isset($_POST['search_name'])) {
    $_SESSION['search_text'] = isset($_POST['search_name']) ? protect($_POST['search_name']) : "";
}

$search_text = $_SESSION['search_text'];

$p = (int)(!isset($_GET["p"]) ? 1 : $_GET["p"]);
$limit = 20;
$start_point = ($p * $limit) - $limit;

//製作分頁
$statement = "`threads` where `title` LIKE '%$search_text%'";
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"> 搜尋 &raquo; <?php echo $_SESSION['search_text']; ?></h3>
    </div>
    <div class="panel-body">
        <table border="0" cellspacing="2" cellpadding="2" width="100%">
            <?php
            //顯示紀錄
            $query = mysqli_query($conn, "SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$start_point} , {$limit}") or die(mysqli_error($conn));
            if (mysqli_num_rows($query) > 0) {
                $i = 0;
                while ($row = mysqli_fetch_array($query)) {
                    $nums = mysqli_num_rows($query);
                    ?>
                    <tr>
                        <td width="70%">
                            <a href="<?php echo $web['forum_url']; ?>t/<?php echo create_thread_link($row['id']); ?>/<?php echo $row['id']; ?>/" class="forum_link">
                                <?php echo $row['title']; ?>
                            </a>
                            <br/>發布於: <?php echo $row['date']; ?></td>
                        <td width="20%" align="center">
                            <b>作者</b><br/>
                            <a href="<?php echo $web['forum_url']; ?>user/<?php echo idinfo($row['user_id'], "usern"); ?>/"><?php echo idinfo($row['user_id'], "display_name"); ?></a>
                        </td>
                        <td width="10%" align="center">
                            <b>回覆</b>
                            <br/>
                            <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM replies WHERE forum_id='$row[id]' and thread_id='$row[id]' and category_id='$row[category_id]'")); ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                    if ($i !== $nums) {
                        echo '<tr><td colspan="3"><div class="line2"></div></td></tr>';
                    }
                }
            } else {
                ?>
                <tr>
                    <td>找不到結果。</td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
        if (pagination($statement, "burl", $limit, $p)) {
            echo '<br>';
            echo pagination($statement, "burl", $limit, $p);
        }
        ?>
    </div>
</div>
