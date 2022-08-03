<?php
global $web, $conn;
if (!$_SESSION['usern']) {
    $redir = $web['forum_url'] . "sign_in/";
    header("Location: $redir");
}

if (userinfo($_SESSION['usern'], "level") === "mod" || userinfo($_SESSION['usern'], "level") === "admin") {
    if (userinfo($_SESSION['usern'], "level") === "admin") {
        ?>
        <table border="0" cellspacing="2" cellpadding="2">
            <tr>
                <td>
                    <a href="<?php echo $web['forum_url']; ?>adpanel/forum_categories/" class="btn btn-default">分類</a>&nbsp;
                </td>
                <td>
                    <a href="<?php echo $web['forum_url']; ?>adpanel/forum_sub_categories/" class="btn btn-default">論壇</a>&nbsp;
                </td>
                <td>
                    <a href="<?php echo $web['forum_url']; ?>adpanel/products/" class="btn btn-default">商品</a> &nbsp;
                </td>
                <td>
                    <a href="<?php echo $web['forum_url']; ?>adpanel/product_items/" class="btn btn-default">序號</a> &nbsp;
                </td>
            </tr>
        </table>
        <br>
    <?php }

    if ($_GET['action'] === "edit") {
        if ($_GET['type'] === "thread") {
            $id = protect($_GET['id']);
            global $web, $conn;
            $sql = mysqli_query($conn, "SELECT * FROM threads WHERE id='$id'");
            if (mysqli_num_rows($sql) > 0) {
                $row = mysqli_fetch_array($sql);
                ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?php
                            echo(userinfo($_SESSION['usern'], "level") === "admin" ? 'Admin' : 'Mod');
                            ?> 面板 &raquo; 編輯 &raquo; 文章
                        </h3>
                    </div>
                    <div class="panel-body">

                        <?php
                        if (isset($_POST['do_edit'])) {
                            $title = protect($_POST['title']);
                            $tags = protect($_POST['tags']);
                            $content = $_POST['content'];
                            $edited_by = userinfo($_SESSION['usern'], "id");

                            if (empty($title) or empty($tags) or empty($content)) {
                                error("請填寫所有欄位!");
                            } elseif (!explode(",", $tags)) {
                                error("Please separate the tags with a comma!");
                            } else {
                                $success = 1;
                                $update = mysqli_query($conn, "UPDATE threads SET title='$title', tags='$tags', content='$content', edited_by='$edited_by', edited_on=SYSDATE() WHERE id='$id'");
                                $thread_link = $web['forum_url'] . "t/" . create_thread_link($row['id']) . "/" . $row['id'] . "/#thread_" . $row['id'];
                            }
                        }

                        if ($success === 1) {
                            ?>
                            <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                                <tr>
                                    <td align="center" style="font-size:16px; font-weight:bold;">
                                        文章編輯成功!
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="<?php echo $thread_link; ?>">Click here to view and read edited
                                            thread.</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="<?php echo $web['forum_url']; ?>">點擊返回論壇.</a>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        } else {
                            ?>
                            <div class="clearfix"></div>
                            <form action="" method="POST">
                                <table border="0" cellspacing="2" cellpadding="2">
                                    <tr style="height:70px;">
                                        <td class="field_text" style="width:40px">Title:</td>
                                        <td>
                                            <input type="text" class="form-control" size="50" name="title" value="<?php echo $row['title']; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="field_text">Tags:</td>
                                        <td>
                                            <input type="text" class="form-control" size="50" name="tags" value="<?php echo $row['tags']; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <br/>
                                            <?php
                                            include("./ckeditor/ckeditor_php5.php");
                                            $CKEditor = new CKEditor();
                                            $CKEditor->editor("content", "$row[content]");
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <br/>
                                            <input type="submit" class="btn btn-success" name="do_edit" value="編輯">
                                            <input type="reset" class="btn btn-danger" value="清除">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            } else {
                header("Location: $web[forum_url]");
            }
        } elseif ($_GET['type'] === "reply") {
            $id = protect($_GET['id']);
            $sql = mysqli_query($conn, "SELECT * FROM replies WHERE id='$id'");
            if (mysqli_num_rows($sql) > 0) {
                $row = mysqli_fetch_array($sql);
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?php
                            if (userinfo($_SESSION['usern'], "level") === "admin") {
                                echo '管理員';
                            } else {
                                echo '版主';
                            }
                            ?> 面板 &raquo; 編輯 &raquo; 回覆
                        </h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        if ($_POST['do_edit']) {
                            $content = $_POST['content'];
                            $edited_by = userinfo($_SESSION['usern'], "id");
                            if (empty($content)) {
                                error("請填寫所有欄位!");
                            } else {
                                $success = 1;
                                $update = mysqli_query($conn, "UPDATE replies SET content='$content',edited_by='$edited_by',edited_on=SYSDATE() WHERE id='$id'");
                                $get_sql = mysqli_query($conn, "SELECT * FROM replies WHERE id='$id'");
                                $get = mysqli_fetch_array($get_sql);


                                $query1 = "SELECT COUNT(id) AS numrows FROM replies WHERE thread_id='$get[thread_id]' and forum_id='$get[forum_id]' and category_id='$get[category_id]'";
                                $result = mysqli_query($conn, $query1) or die('Error, query failed');
                                $rowz = mysqli_fetch_array($result, MYSQLI_ASSOC);

                                $num = $rowz['numrows'];

                                if ($num > 9) {
                                    $num1 = ceil($num / 10);
                                }

                                if (isset($num1) && $num1 > 1) {
                                    $replie_link = $web['forum_url'] . "t/" . create_thread_link($get['thread_id']) . "/" . $get['thread_id'] . "/" . $num1 . "/#replie_" . $get['id'];
                                } else {
                                    $replie_link = $web['forum_url'] . "t/" . create_thread_link($get['thread_id']) . "/" . $get['thread_id'] . "/#replie_" . $get['id'];
                                }
                            }
                        }

                        if ($success === 1) {
                            ?>

                            <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                                <tr>
                                    <td align="center" style="font-size:16px; font-weight:bold;">
                                        Reply has been edited successfully!
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="<?php echo $replie_link; ?>">Click here to view and read edited
                                            reply.</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="<?php echo $web['forum_url']; ?>">點擊返回論壇.</a>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        } else {
                            ?>
                            <div class="clearfix"></div>
                            <form action="" method="POST">
                                <table border="0" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td colspan="2">
                                            <?php
                                            include("./ckeditor/ckeditor_php5.php");
                                            $CKEditor = new CKEditor();
                                            $CKEditor->editor("content", "$row[content]");
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <br/>
                                            <input type="submit" class="btn btn-success" name="do_edit" value="編輯">
                                            <input type="reset" class="btn btn-danger" value="清除">
                                        </td>
                                    </tr>
                                </table>
                            </form>

                        <?php } ?>

                    </div>
                </div>
                <?php
            } else {
                header("Location: $web[forum_url]");
            }
        } elseif ($_GET['type'] == "user") {
            $id = protect($_GET['id']);
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
            if (mysqli_num_rows($sql) > 0) {
                $row = mysqli_fetch_array($sql);
                ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?php
                            if (userinfo($_SESSION['usern'], "level") === "admin") {
                                echo '管理員';
                            } else {
                                echo '版主';
                            }
                            ?> 面板 &raquo; 編輯 &raquo; 會員
                        </h3>
                    </div>
                    <div class="panel-body">

                        <?php
                        if ($row['level'] === "admin") {
                            if (userinfo($_SESSION['usern'], "level") == "mod") {
                                error("You dont have access to edit admin account!");
                            } else {
                                if (isset($_POST['do_edit'])) {
                                    $display_name = protect($_POST['display_name']);
                                    $usernn = protect($_POST['usernn']);
                                    $passwd = protect($_POST['passwd']);
                                    $email = protect($_POST['email']);
                                    if (userinfo($_SESSION['usern'], "level") === "admin") {
                                        $level = protect($_POST['level']);
                                    }

                                    $sql_check_1 = mysqli_query($conn, "SELECT * FROM users WHERE display_name='$display_name'");
                                    $sql_check_2 = mysqli_query($conn, "SELECT * FROM users WHERE usern='$usernn'");
                                    $sql_check_3 = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

                                    if (empty($display_name) or empty($usern) or empty($passwd) or empty($email)) {
                                        error("請填寫所有欄位!");
                                    } elseif (strlen($display_name) < 5) {
                                        error("名稱至少要有5個英文字長度(中文字為2).");
                                    } elseif (strlen($usernn) < 5) {
                                        error("使用者名稱至少要有5個英文字長度(中文字為2).");
                                    } elseif (!filterName($usernn)) {
                                        error("請輸入有效的使用者名稱!");
                                    } elseif ($row['display_name'] !== $display_name) {
                                        if (mysqli_num_rows($sql_check_1) > 0) {
                                            error("此名稱已被其他使用者取用!");
                                        }
                                    } elseif ($row['usern'] !== $usernn) {
                                        if (mysqli_num_rows($sql_check_2) > 0) {
                                            error("此名稱使用者已被其他使用者取用!");
                                        }
                                    } elseif ($row['email'] !== $email) {
                                        if (mysqli_num_rows($sql_check_3) > 0) {
                                            error("此電子信箱已被其他使用者註冊!");
                                        }
                                    } elseif (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
                                        error("請輸入有效的電子信箱!");
                                    } else {
                                        $success = 1;
                                        if (userinfo($_SESSION['usern'], "level") === "admin") {
                                            $update = mysqli_query($conn, "UPDATE users SET display_name='$display_name',usern='$usernn',passwd='$passwd',email='$email',level='$level' WHERE id='$id'");
                                        } else {
                                            $update = mysqli_query($conn, "UPDATE users SET display_name='$display_name',usern='$usernn',passwd='$passwd',email='$email' WHERE id='$id'");
                                        }
                                    }
                                }

                                if ($success === 1) {
                                    ?>
                                    <table border="0" cellspacing="2" cellpadding="2" align="center"
                                           style="padding:20px;">
                                        <tr>
                                            <td align="center" style="font-size:16px; font-weight:bold;">
                                                使用者編輯成功!
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <a href="<?php echo $web['forum_url']; ?>user/<?php echo $row['usern']; ?>">點擊返回使用者個人檔案.</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <a href="<?php echo $web['forum_url']; ?>">點擊返回論壇.</a>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php
                                } else {
                                    ?>
                                    <div class="clearfix"></div>
                                    <form action="" method="POST">
                                        <table border="0" cellspacing="2" cellpadding="2">
                                            <tr style="height:50px;">
                                                <td class="">Display name:</td>
                                                <td><input type="text" class="form-control" size="35"
                                                           name="display_name"
                                                           value="<?php echo $row['display_name']; ?>"></td>
                                            </tr>
                                            <tr style="height:50px;">
                                                <td class="form-control2">Username:</td>
                                                <td>
                                                    <input type="text" class="form-control" size="35" name="usernn"
                                                           value="<?php echo $row['usern']; ?>">
                                                </td>
                                            </tr>
                                            <tr style="height:50px;">
                                                <td class="form-control2">Password:</td>
                                                <td>
                                                    <input type="text" class="form-control" size="35" name="passwd"
                                                           value="<?php echo $row['passwd']; ?>">
                                                </td>
                                            </tr>
                                            <tr style="height:50px;">
                                                <td class="form-control2">電子信箱:</td>
                                                <td>
                                                    <input type="text" class="form-control" size="35" name="email"
                                                           value="<?php echo $row['email']; ?>">
                                                </td>
                                            </tr>
                                            <?php if (userinfo($_SESSION['usern'], "level") === "admin") { ?>
                                                <tr style="height:50px;">
                                                    <td class="form-control2">Level:</td>
                                                    <td>
                                                        <select class="form-control" name="level">
                                                            <option value="admin" <?php echo($row['level'] === "admin" ? "selected" : ""); ?>>
                                                                平台管理員
                                                            </option>
                                                            <option value="mod" <?php echo($row['level'] === "mod" ? "selected" : ""); ?>>
                                                                Moderator
                                                            </option>
                                                            <option value="member" <?php echo($row['level'] === "member" ? "selected" : ""); ?>>
                                                                會員
                                                            </option>
                                                            <option value="ban" <?php echo($row['level'] === "ban" ? "selected" : ""); ?>>
                                                                禁封
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2">
                                                    <input type="submit" class="btn btn-success" name="do_edit" value="編輯">
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                    <?php
                                }
                            }
                        } else {
                            if (isset($_POST['do_edit'])) {
                                $display_name = protect($_POST['display_name']);
                                $usernn = protect($_POST['usernn']);
                                $passwd = protect($_POST['passwd']);
                                $email = protect($_POST['email']);
                                if (userinfo($_SESSION['usern'], "level") === "admin") {
                                    $level = protect($_POST['level']);
                                }

                                $sql_check_1 = mysqli_query($conn, "SELECT * FROM users WHERE display_name='$display_name'");
                                $sql_check_2 = mysqli_query($conn, "SELECT * FROM users WHERE usern='$usernn'");
                                $sql_check_3 = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

                                if (empty($display_name) or empty($usern) or empty($passwd) or empty($email)) {
                                    error("請填寫所有欄位!");
                                } elseif (strlen($display_name) < 5) {
                                    error("名稱至少要有5個英文字長度(中文字為2).");
                                } elseif (strlen($usernn) < 5) {
                                    error("使用者名稱至少要有5個英文字長度(中文字為2).");
                                } elseif (!filterName($usernn)) {
                                    error("請輸入有效的使用者名稱!");
                                } elseif ($row['display_name'] !== $display_name) {
                                    if (mysqli_num_rows($sql_check_1) > 0) {
                                        error("此名稱已被其他使用者取用!");
                                    }
                                } elseif ($row['usern'] !== $usernn) {
                                    if (mysqli_num_rows($sql_check_2) > 0) {
                                        error("此名稱使用者已被其他使用者取用!");
                                    }
                                } elseif ($row['email'] !== $email) {
                                    if (mysqli_num_rows($sql_check_3) > 0) {
                                        error("此電子信箱已被其他使用者註冊!");
                                    }
                                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    error("請輸入有效的電子信箱!");
                                } else {
                                    $success = 1;
                                    if (userinfo($_SESSION['usern'], "level") === "admin") {
                                        $update = mysqli_query($conn, "UPDATE users SET display_name='$display_name',usern='$usernn',passwd='$passwd',email='$email',level='$level' WHERE id='$id'");
                                    } else {
                                        $update = mysqli_query($conn, "UPDATE users SET display_name='$display_name',usern='$usernn',passwd='$passwd',email='$email' WHERE id='$id'");
                                    }
                                }
                            }

                            if (isset($success) && $success === 1) {
                                ?>
                                <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                                    <tr>
                                        <td align="center" style="font-size:16px; font-weight:bold;">
                                            User has been edited successfully!
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <a href="<?php echo $web['forum_url']; ?>user/<?php echo $row['usern']; ?>">點擊返回個人檔案.</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <a href="<?php echo $web['forum_url']; ?>">點擊返回論壇首頁.</a>
                                        </td>
                                    </tr>
                                </table>
                                <?php
                            } else {
                                ?>
                                <form action="" method="POST">
                                    <table border="0" cellspacing="2" cellpadding="2">
                                        <tr style="height:60px;">
                                            <td class="field_text" style="width:100px;">Display name:</td>
                                            <td><input type="text" class="form-control" size="35" name="display_name"
                                                       value="<?php echo $row['display_name']; ?>"></td>
                                        </tr>
                                        <tr style="height:60px;">
                                            <td class="field_text">Username:</td>
                                            <td><input type="text" class="form-control" size="35" name="usernn"
                                                       value="<?php echo $row['usern']; ?>"></td>
                                        </tr>
                                        <tr style="height:60px;">
                                            <td class="field_text">Password:</td>
                                            <td><input type="text" class="form-control" size="35" name="passwd"
                                                       value="<?php echo $row['passwd']; ?>"></td>
                                        </tr>
                                        <tr style="height:60px;">
                                            <td class="field_text">電子信箱:</td>
                                            <td><input type="text" class="form-control" size="35" name="email"
                                                       value="<?php echo $row['email']; ?>"></td>
                                        </tr>
                                        <?php if (userinfo($_SESSION['usern'], "level") === "admin") { ?>
                                            <tr style="height:60px;">
                                                <td class="field_text">Level:</td>
                                                <td><select class="form-control" name="level">
                                                        <option value="admin" <?php
                                                        if ($row['level'] === "admin") {
                                                            echo 'selected';
                                                        }
                                                        ?>>Administrator
                                                        </option>
                                                        <option value="mod" <?php
                                                        if ($row['level'] == "mod") {
                                                            echo 'selected';
                                                        }
                                                        ?>>Moderator
                                                        </option>
                                                        <option value="member" <?php
                                                        if ($row['level'] == "member") {
                                                            echo 'selected';
                                                        }
                                                        ?>>Member
                                                        </option>
                                                        <option value="ban" <?php
                                                        if ($row['level'] == "ban") {
                                                            echo 'selected';
                                                        }
                                                        ?>>Banned
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="2">
                                                <input type="submit" class="btn btn-success" name="do_edit" value="編輯">
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                                <?php
                            }
                        }
                        ?>

                    </div>
                </div>
                <?php
            } else {
                header("Location: $web[forum_url]");
            }
        } else {
            header("Location: $web[forum_url]");
        }
    } elseif ($_GET['action'] === "delete") {
        if ($_GET['type'] === "thread") {
            $id = protect($_GET['id']);
            $sql = mysqli_query($conn, "SELECT * FROM threads WHERE id='$id'");
            if (mysqli_num_rows($sql) > 0) {
                mysqli_query($conn, "DELETE FROM threads WHERE id='$id'");
                mysqli_query($conn, "DELETE FROM replies WHERE thread_id='$id'");
                ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?php
                            if (userinfo($_SESSION['usern'], "level") === "admin") {
                                echo '管理員';
                            } else {
                                echo '版主';
                            }
                            ?> 面板 &raquo; 刪除 &raquo; 文章
                        </h3>
                    </div>
                    <div class="panel-body">


                        <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                            <tr>
                                <td align="center" style="font-size:16px; font-weight:bold;">
                                    Thread has been deleted successfully!
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $web['forum_url']; ?>">點擊返回論壇首頁.</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <?php
            } else {
                header("Location: $web[forum_url]");
            }
        } elseif ($_GET['type'] === "reply") {
            $id = protect($_GET['id']);
            $sql = mysqli_query($conn, "SELECT * FROM replies WHERE id='$id'");
            if (mysqli_num_rows($sql) > 0) {
                $row = mysqli_fetch_array($sql);
                $delete = mysqli_query($conn, "DELETE FROM replies WHERE id='$id'");
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?php
                            if (userinfo($_SESSION['usern'], "level") === "admin") {
                                echo '管理員';
                            } else {
                                echo '版主';
                            }
                            ?> 面板 &raquo; 刪除 &raquo; 留言
                        </h3>
                    </div>
                    <div class="panel-body">
                        <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                            <tr>
                                <td align="center" style="font-size:16px; font-weight:bold;">
                                    留言成功刪除!
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $web['forum_url']; ?>t/<?php echo create_thread_link($row['thread_id']); ?>/<?php echo $row['thread_id']; ?>/">
                                        點擊返回文章.
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $web['forum_url']; ?>">
                                        點擊返回論壇首頁.
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php
            } else {
                header("Location: $web[forum_url]");
            }
        } elseif ($_GET['type'] === "user") {
            $id = protect($_GET['id']);
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
            if (mysqli_num_rows($sql) > 0) {
                mysqli_query($conn, "DELETE FROM threads WHERE user_id='$id'");
                mysqli_query($conn, "DELETE FROM replies WHERE user_id='$id'");
                mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?php
                            if (userinfo($_SESSION['usern'], "level") === "admin") {
                                echo '管理員';
                            } else {
                                echo '版主';
                            }
                            ?> 面板 &raquo; 刪除 &raquo; 會員
                        </h3>
                    </div>
                    <div class="panel-body">

                        <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                            <tr>
                                <td align="center" style="font-size:16px; font-weight:bold;">
                                    會員成功被刪除!
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $web['forum_url']; ?>">點擊返回論壇首頁.</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php
            } else {
                header("Location: $web[forum_url]");
            }
        } else {
            header("Location: $web[forum_url]");
        }
    } elseif ($_GET['action'] === "lock") {
        $id = protect($_GET['id']);
        $sql = mysqli_query($conn, "SELECT * FROM threads WHERE id='$id'");
        if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_array($sql);
            $update = mysqli_query($conn, "UPDATE threads SET locked='yes' WHERE id='$id'");
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php
                        if (userinfo($_SESSION['usern'], "level") === "admin") {
                            echo '管理員';
                        } else {
                            echo '版主';
                        }
                        ?> 面板 &raquo; 鎖定 &raquo; 文章
                    </h3>
                </div>
                <div class="panel-body">
                    <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                        <tr>
                            <td align="center" style="font-size:16px; font-weight:bold;">
                                文章成功被鎖定!
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <a href="<?php echo $web['forum_url']; ?>t/<?php echo create_thread_link($row['id']); ?>/<?php echo $row['id']; ?>/">點擊返回文章.</a>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <a href="<?php echo $web['forum_url']; ?>">點擊返回論壇首頁.</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        } else {
            header("Location: $web[forum_url]");
        }
    } elseif ($_GET['action'] === "unlock") {
        $id = protect($_GET['id']);
        $sql = mysqli_query($conn, "SELECT * FROM threads WHERE id='$id'");
        if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_array($sql);
            $update = mysqli_query($conn, "UPDATE threads SET locked='no' WHERE id='$id'");
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php
                        if (userinfo($_SESSION['usern'], "level") === "admin") {
                            echo '管理員';
                        } else {
                            echo '版主';
                        }
                        ?> 面板 &raquo; 解鎖 &raquo; 文章
                    </h3>
                </div>
                <div class="panel-body">
                    <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                        <tr>
                            <td align="center" style="font-size:16px; font-weight:bold;">
                                文章成功被解鎖!
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <a href="<?php echo $web['forum_url']; ?>t/<?php echo create_thread_link($row['id']); ?>/<?php echo $row['id']; ?>/">點擊返回文章.</a>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <a href="<?php echo $web['forum_url']; ?>">點擊返回論壇首頁.</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        } else {
            header("Location: $web[forum_url]");
        }
    } else {
        header("Location: $web[forum_url]");
    }
} else {
    header("Location: $web[forum_url]");
}

