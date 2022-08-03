<?php
global $conn, $web;
$id = protect($_GET['id']);
$sql = mysqli_query($conn, "SELECT * FROM threads WHERE id='$id'");
if (mysqli_num_rows($sql) > 0) {
    // 文章
    $row = mysqli_fetch_array($sql);
    // 分類
    $cat = mysqli_fetch_array(mysqli_query($conn, "SELECT value FROM categories WHERE id='$row[category_id]'")) or die(mysqli_error($conn));
    // 論壇區
    $forum = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM forums WHERE id='$row[forum_id]'")) or die(mysqli_error($conn));
    ?>
    <?php if (userinfo($_SESSION['usern'], "level") !== "ban") { ?>
        <?php if (isset($_SESSION['usern']) && $_SESSION['usern']) { ?>
            <table border="0" cellspacing="2" cellpadding="2">
                <tr>
                    <td>
                        <?php
                        if ($row['locked'] === "yes") {
                            ?>
                            <input type="submit" class="btn btn-danger" value="文章鎖定中!">
                            <?php
                        } else {
                            ?>
                            <form action="<?php echo $web['forum_url']; ?>post/replie/<?php echo $row['category_id']; ?>/<?php echo $row['forum_id']; ?>/<?php echo $row['id']; ?>/"
                                  method="POST">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-plus"></i> 發表留言
                                </button>
                            </form>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <br>
        <?php }
    } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?php echo $cat['value']; ?> &raquo; <a href="<?php echo $web['forum_url']; ?>f/
					<?php echo create_forum_link($forum['id']); ?>/<?php echo $forum['id']; ?>/">
                    <span><?php echo $forum['value']; ?></span></a> &raquo; <?php echo $row['title']; ?>
            </h3>
        </div>
        <div class="panel-body">
            <?php
            if (isset($_POST['do_charge'])) {
                $thread_id = $_POST['thread_id'];
                $user_id = userinfo($_SESSION['usern'], "id");
                $points = userinfo($_SESSION['usern'], "points");

                if ($row['charge'] > $points) {
                    error("您只有$points 不足以解鎖此文章!");
                } else {
                    $points -= $row['charge'];
                    mysqli_query($conn, "UPDATE users SET points = '$points' WHERE id='$user_id'") or die(mysqli_error($conn));
                    mysqli_query($conn, "INSERT thread_events (thread_id, event_name, event_value, executed_by) VALUES ('$thread_id', 'charge', '$row[charge]', '$user_id');") or die(mysqli_error($conn));
                }
            }

            if (!isset($_GET['p']) || $_GET['p'] === 1) {
                ?>
                <table border="0" cellspacing="0" cellpadding="0" width="100%" id="thread_<?php echo $row['id']; ?>">
                    <tr>
                        <td valign="top" width="20%" align="center">
                            <a href="<?php echo $web['forum_url']; ?>user/<?php echo idinfo($row['user_id'], "usern"); ?>/">
                                <span style="font-size:14px; font-weight:bold;">
                                    <?php echo idinfo($row['user_id'], "display_name"); ?>
                                </span>
                            </a>
                            <br/><br/>
                            <div style="width:130px;" class="thumbnail">
                                <?php
                                $avatar = idinfo($row['user_id'], "avatar");
                                if ($avatar === NULL || $avatar === '') {
                                    ?>
                                    <img src="<?php echo $web['forum_url']; ?>assets/imgs/davatar.jpg" class="avatar"
                                         width="130px" height="130px">
                                    <?php
                                } else {
                                    ?>
                                    <img src="<?php echo $web['forum_url']; ?>image.php?width=130&height=130&image=<?php echo $web['forum_url'];
                                    echo idinfo($row['user_id'], "avatar");
                                    ?>&cropratio=1:1" class="avatar" width="130px" height="130px">
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                            $user_time = idinfo($row['user_id'], "online_time");
                            $timeon = time() - 600;
                            if ($user_time > $timeon) {
                                echo '<span class="label label-success">上線中</span>';
                            } else {
                                echo '<span class="label label-danger">離線中</span>';
                            }
                            ?>
                        </td>
                        <td valign="top" class="line3"></td>
                        <td valign="top" width="80%" style="padding:5px;">
                            <table border="0" cellspacing="2" cellpadding="2" width="100%">
                                <tr>
                                    <td valign="top">
                                        <span style="font-size:9px;">文章發佈於： <b><?php echo $row['date']; ?></b></span>
                                        <?php
                                        // 分類
                                        if ($row['charge'] > 0) {
                                            $charged = mysqli_query($conn, "SELECT * FROM thread_events WHERE thread_id='$row[id]' AND executed_by='$user_id' AND event_name='charge'") or die(mysqli_error($conn));
                                            $charged = mysqli_num_rows($charged) > 0;
                                            ?>
                                            <br/>
                                            <span class="text-danger"
                                                  style="font-size:9px;">文章解鎖費用： <b><?php echo $row['charge']; ?></b></span>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td align="right">
                                        <?php if (userinfo($_SESSION['usern'], "level") !== "ban") { ?><?php if (isset($_SESSION['usern']) && $_SESSION['usern']) {
                                            if (userinfo($_SESSION['usern'], "id") == $row['user_id']) {
                                                ?>
                                                <input type="submit" class="btn"
                                                       onclick="javascript:window.location.href = '<?php echo $web['forum_url']; ?>post/edit/thread/<?php echo $row['id']; ?>/';"
                                                       value="編輯">
                                                <input type="submit" class="btn"
                                                       onclick="javascript:window.location.href = '<?php echo $web['forum_url']; ?>post/delete/thread/<?php echo $row['id']; ?>/';"
                                                       value="刪除">
                                            <?php } ?><input type="submit" class="btn"
                                                             onclick="javascript:window.location.href = '<?php echo $web['forum_url']; ?>post/quote/thread/<?php echo $row['id']; ?>/';"
                                                             value="引用">
                                            <?php
                                        }
                                        }
                                        // 管理員
                                        if (userinfo($_SESSION['usern'], "level") === "admin") {
                                            ?>
                                            <br/><br/>
                                            <select class="form-control" style="width: 90px;"
                                                    onchange="confirmm(this.options[this.selectedIndex].value);">
                                                <option value="">管理員選單</option>
                                                <option value="">- - -</option>
                                                <option value="<?php echo $web['forum_url']; ?>panel/edit/thread/<?php echo $row['id']; ?>/">
                                                    編輯文章
                                                </option>
                                                <option value="<?php echo $web['forum_url']; ?>panel/delete/thread/<?php echo $row['id']; ?>/">
                                                    刪除文章
                                                </option>
                                                <?php
                                                if ($row['locked'] === "no") {
                                                    ?>
                                                    <option value="<?php echo $web['forum_url']; ?>panel/lock/<?php echo $row['id']; ?>/">
                                                        鎖定文章
                                                    </option>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <option value="<?php echo $web['forum_url']; ?>panel/unlock/<?php echo $row['id']; ?>/">
                                                        解鎖文章
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        }
                                        if (userinfo($_SESSION['usern'], "level") === "mod") {
                                            ?>
                                            <br/><br/>
                                            <select name="" class="form-control"
                                                    onchange="confirmm(this.options[this.selectedIndex].value);">
                                                <option value="">Moderator`s menu</option>
                                                <option value="<?php echo $web['forum_url']; ?>panel/edit/thread/<?php echo $row['id']; ?>/">
                                                    編輯文章
                                                </option>
                                                <option value="<?php echo $web['forum_url']; ?>panel/delete/thread/<?php echo $row['id']; ?>/">
                                                    刪除文章
                                                </option>
                                                <?php
                                                if ($row['locked'] === "no") {
                                                    ?>
                                                    <option value="<?php echo $web['forum_url']; ?>panel/lock/<?php echo $row['id']; ?>/">
                                                        鎖定文章
                                                    </option>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <option value="<?php echo $web['forum_url']; ?>panel/unlock/<?php echo $row['id']; ?>/">
                                                        解鎖文章
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                            <?php
                            if ($row['charge'] === "0" || (isset($charged) && $charged)) {
                                echo bbcode(protect_post($row['content']));
                            } else {
                                ?>
                                <form action="" method="POST">
                                    <br/>
                                    <input name="thread_id" value="<?php echo $row['id'];?>" hidden>
                                    <input name="do_charge" value="do_charge" hidden>
                                    <span style="font-size:15px;"><i>文章尚未解鎖！</i></span>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-lock"></i> 解鎖文章
                                    </button>
                                </form>
                                <?php
                            }

                            if ($row['edited_by'] && $row['edited_on']) {
                                echo '<br>';
                                $editor = '<a href="' . $web['forum_url'] . 'user/' . idinfo($row['edited_by'], "usern") . '/">' . idinfo($row['edited_by'], "display_name") . '</a>';
                                warning("此文章於 $row[edited_on] 由 $editor 編輯!");
                            }

                            $signature = idinfo($row['user_id'], "signature");
                            if (!empty($signature)) {
                                echo '<hr/>' . protect_post($signature);
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="line2"><br/></div>
                        </td>
                    </tr>
                </table>
                <?php
            }

            $burl = $web['forum_url'] . "t/" . create_thread_link($row['id']) . "/" . $row['id'] . "/";
            $p = (int)(!isset($_GET["p"]) ? 1 : $_GET["p"]);
            if (!isset($_GET['p']) || $_GET['p'] === 1) {
                $limit = 9;
            } else {
                $limit = 10;
            }
            $startpoint = ($p * $limit) - $limit;

            //製作分頁
            $statement = "`replies` where `thread_id` = $row[id] and `forum_id` = $row[forum_id] and `category_id` = $row[category_id]";

            //顯示紀錄
            $query = mysqli_query($conn, "SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}");
            if (mysqli_num_rows($query) > 0) {
                $i = 0;
                while ($get = mysqli_fetch_array($query)) {
                    $nums = mysqli_num_rows($query);
                    ?>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%"
                           id="replie_<?php echo $get['id']; ?>">
                        <tr>
                            <td valign="top" width="20%" align="center">
                                <br>
                                <a href="<?php echo $web['forum_url']; ?>user/<?php echo idinfo($get['user_id'], "usern"); ?>/"><span
                                            style="font-size:14px; font-weight:bold;"><?php echo idinfo($get['user_id'], "display_name"); ?></span></a><br/><br/>
                                <?php
                                if (idinfo($get['user_id'], "avatar") === NULL) {
                                    ?>
                                    <img src="<?php echo $web['forum_url']; ?>assets/imgs/davatar.jpg" class="avatar"
                                         width="130px" height="130px">
                                    <?php
                                } else {
                                    ?>
                                    <img src="<?php echo $web['forum_url']; ?>image.php?width=130&height=130&image=<?php echo $web['forum_url'];
                                    echo idinfo($get['user_id'], "avatar");
                                    ?>&cropratio=1:1" class="avatar" width="130px" height="130px">
                                    <?php
                                }
                                ?>
                                <br><br>
                                <?php
                                $user_time = idinfo($get['user_id'], "online_time");
                                $timeon = time() - 600;
                                if ($user_time > $timeon) {
                                    echo '<span class="label label-success">上線中</span>';
                                } else {
                                    echo '<span class="label label-danger">下線</span>';
                                }
                                ?>
                                <br>
                                <br>
                            </td>
                            <td valign="top" class="line3"></td>
                            <td valign="top" width="80%" style="padding:5px;">
                                <table border="0" cellspacing="2" cellpadding="2" width="100%">
                                    <tr>
                                        <td valign="top">
                                            <span style="font-size:9px;">
                                                留言發佈於<b><?php echo $get['date']; ?></b>
                                            </span>
                                        </td>
                                        <td align="right"><?php if (userinfo($_SESSION['usern'], "level") !== "ban") { ?><?php if (isset($_SESSION['usern']) && $_SESSION['usern']) {
                                                if (userinfo($_SESSION['usern'], "id") === $get['user_id']) {
                                                    ?>
                                                    <input type="submit" class="btn"
                                                           onclick="javascript:window.location.href = '<?php echo $web['forum_url']; ?>post/edit/replie/<?php echo $get['id']; ?>/';"
                                                           value="編輯">
                                                    <input type="submit" class="btn"
                                                           onclick="javascript:window.location.href = '<?php echo $web['forum_url']; ?>post/delete/replie/<?php echo $get['id']; ?>/';"
                                                           value="刪除">
                                                <?php } ?>
                                                <input type="submit" class="btn"
                                                       onclick="javascript:window.location.href = '<?php echo $web['forum_url']; ?>post/quote/replie/<?php echo $get['id']; ?>/';"
                                                       value="引用">
                                            <?php }
                                            }
                                            if (userinfo($_SESSION['usern'], "level") === "admin") {
                                                ?>
                                                <br><br>
                                                <select name="" class="form-control"
                                                        onchange="confirmm(this.options[this.selectedIndex].value);">
                                                    <option value="">管理員的選單</option>
                                                    <option value="">- - -</option>
                                                    <option value="<?php echo $web['forum_url']; ?>panel/edit/reply/<?php echo $get['id']; ?>/">
                                                        編輯留言
                                                    </option>
                                                    <option value="<?php echo $web['forum_url']; ?>panel/delete/reply/<?php echo $get['id']; ?>/">
                                                        刪除留言
                                                    </option>
                                                </select>
                                                <?php
                                            }
                                            if (userinfo($_SESSION['usern'], "level") === "mod") {
                                                ?>
                                                <br><br>
                                                <select name="" class="form-control"
                                                        onchange="confirmm(this.options[this.selectedIndex].value);">
                                                    <option value="">版主的選單</option>
                                                    <option value="<?php echo $web['forum_url']; ?>panel/edit/reply/<?php echo $get['id']; ?>/">
                                                        編輯留言
                                                    </option>
                                                    <option value="<?php echo $web['forum_url']; ?>panel/delete/reply/<?php echo $get['id']; ?>/">
                                                        刪除留言
                                                    </option>
                                                </select>
                                                <?php
                                            }
                                            ?></td>
                                    </tr>
                                </table>
                                <?php
                                echo bbcode(protect_post($get['content']));

                                if ($get['edited_by'] && $get['edited_on']) {
                                    echo '<br>';
                                    $editor = '<a href="' . $web['forum_url'] . 'user/' . idinfo($get['edited_by'], "usern") . '/">' . idinfo($get['edited_by'], "display_name") . '</a>';
                                    warning("此留言於 $get[edited_on] 由 $editor 編輯!");
                                }

                                $signature = idinfo($get['user_id'], "signature");
                                if (!empty($signature)) {
                                    echo '<hr/>' . protect_post($signature);
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                        if ($i !== $nums) {
                            echo '<tr><td colspan="3"><div class="line2"></div></td></tr>';
                        } ?>
                    </table>
                    <?php
                }
            } else {
                echo ' <span style="font-size:9px; padding:5px; font-weight:bold;">尚未有人留言，現在留言成為第一位吧！</span>';
            }
            if (pagination($statement, $burl, $limit, $p)) {
                echo '<br>';
                echo pagination($statement, $burl, $limit, $p);
            }
            ?>
        </div>
    </div>
    </div>
    <?php
} else {
    header("Location: $web[forum_url]");
}
