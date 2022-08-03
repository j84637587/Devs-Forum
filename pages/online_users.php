<?php
global $web, $conn;
if (!$_SESSION['usern']) {
    $redir = $web['forum_url'] . "sign_in/";
    header("Location: $redir");
}

$p = (int)(!isset($_GET["p"]) ? 1 : $_GET["p"]);
$limit = 20;
$startpoint = ($p * $limit) - $limit;
$online_time = time() - 600;
$statement = "`users` where `online_time` > $online_time";
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">線上玩家</h3>
    </div>
    <div class="panel-body">

        <?php
        //顯示紀錄
        $query = mysqli_query($conn, "SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
        if (mysqli_num_rows($query) > 0) {
            $i = 0;
            while ($row = mysqli_fetch_array($query)) {
                $nums = mysqli_num_rows($query);
                ?>

                <table border="0" cellspacing="2" cellpadding="2" width="100%">
                    <tr>
                        <td>
                            <a href="<?php echo $web['forum_url']; ?>user/<?php echo $row['usern']; ?>/">
                                <span style="font-size:19px; font-weight:bold;"><?php echo $row['display_name']; ?></span>
                                <span style="font-size:15px;">
                                    <i>(<?php echo $row['usern']; ?>)</i>
                                </span>
                            </a>
                        </td>
                        <td align="right">
                            <?php
                            if ($row['level'] === "admin") {
                                echo '<span class="administrator">平台管理員</span>';
                            } elseif ($row['level'] === "mod") {
                                echo '<span class="moderator">板主</span>';
                            } else {
                                echo '<span class="member">會員</span>';
                            }
                            ?>
                        </td>
                    </tr>
                </table>

                <?php
                $i++;
                if (!$i !== 1) {
                    if ($i !== $nums) {
                        echo '<div class="line2"></div>';
                    }
                }
            }
        } else {
            echo '無線上會員. 目前只有你!';
        }

        if (pagination($statement, "burl", $limit, $p)) {
            echo '<br>';
            echo pagination($statement, "burl", $limit, $p);
        }
        ?>

    </div>
</div>
