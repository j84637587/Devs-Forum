<?php
if (!$_SESSION['usern']) {
    $redir = $web['forum_url'] . "sign_in/";
    header("Location: $redir");
}

$id = protect($_GET['id']);
$sql = mysqli_query($conn, "SELECT * FROM users WHERE usern='$id'");
if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_array($sql);
    ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">使用者個人檔案</h3>
        </div>
        <div class="panel-body">

            <table border="0" cellspacing="2" cellpadding="2" width="100%">
                <tr>
                    <td valign="top" width="140px">
                        <?php
                        if ($row['avatar'] == NULL) {
                            ?>
                            <div class="thumbnail" style="width:130px;">
                                <img src="<?php echo $web['forum_url']; ?>assets/imgs/davatar.jpg" class="avatar" width="130px" height="130px">
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="thumbnail" style="width:130px;">
                                <img src="<?php echo $web['forum_url']; ?>image.php?width=130&height=130&image=<?php
                                echo $web['forum_url'];
                                echo $row['avatar']
                                ?>&cropratio=1:1" class="avatar" width="130px" height="130px">
                            </div>
                            <?php
                        }
                        ?>
                    </td>
                    <td width="60%" valign="top">
                        <table border="0" cellspacing="2" cellpadding="2">
                            <tr>
                                <td>
                                    <span style="font-size:19px; font-weight:bold;"><?php echo $row['display_name']; ?></span>
                                    <span style="font-size:15px;"><i>(<?php echo $row['usern']; ?>)</i></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    if ($row['online_time'] > time() - 600) {
                                        echo ' <span class="label label-success">線上</span>';
                                    } else {
                                        echo ' <span class="label label-danger">離線</span>';
                                    }

                                    if ($row['level'] === "admin") {
                                        echo ' <span class="label label-primary">管理員</span>';
                                    } elseif ($row['level'] === "mod") {
                                        echo ' <span class="label label-primary">版主</span>';
                                    } elseif ($row['level'] === "ban") {
                                        echo ' <span class="label label-warning">禁封</span>';
                                    } else {
                                        echo ' <span class="label label-default">會員</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <br>
                                    <span style="font-size:15px; font-weight:bold;">使用者統計:</span><br>
                                    <span style="font-size:12px;">
                                        總發表文章: <i><?php echo number_format(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM threads WHERE user_id='$row[id]'"))); ?></i><br/>
                                        總留言: <i><?php echo number_format(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM replies WHERE user_id='$row[id]'"))); ?></i><br/>
                                        最後活動: <i><?php echo date("d M Y H:i:s", $row['online_time']); ?></i><br/>
                                        註冊時間: <i><?php echo $row['reg_date']; ?></i>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top" align="right">
                        <?php
                        if (userinfo($_SESSION['usern'], "level") === "admin") {
                            ?>
                            <br><br>
                            <select name="" class="form-control" onchange="confirmm(this.options[this.selectedIndex].value);">
                                <option value="">管理員選單</option>
                                <option value="">- - -</option>
                                <option value="<?php echo $web['forum_url']; ?>panel/edit/user/<?php echo $row['id']; ?>/">編輯會員</option>
                                <option value="<?php echo $web['forum_url']; ?>panel/delete/user/<?php echo $row['id']; ?>/">刪除會員</option>
                            </select>
                            <?php
                        }
                        if (userinfo($_SESSION['usern'], "level") == "mod") {
                            ?>
                            <br><br>
                            <select name="" class="form-control">
                                <option value="">Moderator`s menu</option>
                                <option value="<?php echo $web['forum_url']; ?>panel/edit/user/<?php echo $row['id']; ?>/">編輯會員</option>
                                <option value="<?php echo $web['forum_url']; ?>panel/delete/user/<?php echo $row['id']; ?>/">刪除會員</option>
                            </select>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php
} else {
    header("Location: $web[forum_url]");
}
?>
