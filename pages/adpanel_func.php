<?php
global $conn, $web;
if (!$_SESSION['usern']) {
    header("Location: " . $web['forum_url'] . "sign_in/");
}
if (userinfo($_SESSION['usern'], "level") !== "admin") {
    header("Location: $web[forum_url]");
}
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

<?php
if ($_GET['action'] === "edit") {
    if ($_GET['type'] === "category") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    管理面板 &raquo; 編輯分類
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $id = protect($_GET['id']);
                $sql = mysqli_query($conn, "SELECT * FROM categories WHERE id='$id'");
                if (mysqli_num_rows($sql) > 0) {
                $row = mysqli_fetch_array($sql);

                if (isset($_POST['do_edit'])) {
                    $value = protect($_POST['value']);
                    if (empty($value)) {
                        error("請填寫所有欄位!");
                    } else {
                        $success = 1;
                        $update = mysqli_query($conn, "UPDATE categories SET value='$value' WHERE id='$id'");
                    }
                }

                if (isset($success) && $success === 1) {
                    ?>

                    <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                        <tr>
                            <td align="center" style="font-size:16px; font-weight:bold;">
                                Category is edited successfully!
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <a href="<?php echo $web['forum_url']; ?>adpanel/forum_categories/">點擊返回分類管理介面.</a>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <a href="<?php echo $web['forum_url']; ?>adpanel/">點擊返回管理面板.</a>
                            </td>
                        </tr>
                    </table>
                    <?php
                } else {
                ?>
                <form action="" method="POST">
                    <table border="0" cellspacing="2" cellpadding="2">
                        <tr>
                            <td><input type="text" class="form-control" size="30" name="value"
                                       value="<?php echo $row['value']; ?>"></td>
                            <td style="padding-left:10px;">
                                <button type="submit" name="do_edit" class="btn btn-success"><i
                                            class="fa fa-pencil"></i> Edit
                                </button>
                            </td>
                        </tr>
                    </table>
                    <?php
                    }
                    } else {
                        $redir = $web['forum_url'] . "adpanel/";
                        header("Location: $redir");
                    }
                    ?>
            </div>
        </div>
        <?php
    }
    elseif ($_GET['type'] === "forum") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    管理面板 &raquo; 編輯論壇
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $id = protect($_GET['id']);
                $sql = mysqli_query($conn, "SELECT * FROM forums WHERE id='$id'");
                if (mysqli_num_rows($sql) > 0) {
                    $row = mysqli_fetch_array($sql);

                    if (isset($_POST['do_edit'])) {
                        $value = protect($_POST['value']);
                        $category_id = protect($_POST['category_id']);
                        $icon = protect($_POST['icon']);

                        if (empty($icon)) {
                            $icon = "uploads/document-icon.png";
                        }

                        if (empty($value) or empty($category_id)) {
                            error("請填寫所有欄位!");
                        } else {
                            $success = 1;
                            $update = mysqli_query($conn, "UPDATE forums SET value='$value',category_id='$category_id',icon='$icon' WHERE id='$id'");
                        }
                    }

                    if (isset($success) && $success === 1) {
                        ?>
                        <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                            <tr>
                                <td align="center" style="font-size:16px; font-weight:bold;">
                                    Forum is edited successfully!
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $web['forum_url']; ?>adpanel/forum_sub_categories/">Click here
                                        to back to forums manager.</a>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $web['forum_url']; ?>adpanel/">點擊返回管理面板.</a>
                                </td>
                            </tr>
                        </table>
                        <?php
                    } else {
                        ?>
                        <form action="" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    名稱: <input type="text" name="value" value="<?php echo $row['value']; ?>"
                                                 class="form-control" required>
                                </div>
                                <div class="col-md-12">
                                    圖示: <input type="text" name="icon" value="<?php echo $row['icon']; ?>"
                                                 class="form-control">
                                </div>
                                <div class="col-md-12">
                                    分類:
                                    <select name="category_id" class="form-control" required>
                                        <?php
                                        $get_cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY id");
                                        while ($get = mysqli_fetch_array($get_cats)) {
                                            ?>
                                            <option
                                            value="<?php echo $get['id']; ?>" <?php if ($row['category_id'] == $get['id']) {
                                                echo 'selected';
                                            } ?>><?php echo $get['value']; ?></option><?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <br/>
                                    <button type="submit" name="do_edit" class="btn btn-success" style="width:100%"><i
                                                class="fa fa-pencil"></i> Edit
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                } else {
                    $redir = $web['forum_url'] . "adpanel/";
                    header("Location: $redir");
                }
                ?>

            </div>
        </div>
        <?php
    }
    elseif ($_GET['type'] === "product") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    管理面板 &raquo; 編輯論壇
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $id = protect($_GET['id']);
                $sql = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
                if (mysqli_num_rows($sql) > 0) {
                    $row = mysqli_fetch_array($sql);

                    if (isset($_POST['do_edit'])) {
                        $name = protect($_POST['name']);
                        $description = protect($_POST['description']);

                        if (empty($name) || empty($description)) {
                            error("請填寫所有欄位!");
                        } else {
                            $success = 1;
                            $update = mysqli_query($conn, "UPDATE products SET name='$name', description='$description' WHERE id='$id'");
                        }
                    }

                    if (isset($success) && $success === 1) {
                        ?>
                        <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                            <tr>
                                <td align="center" style="font-size:16px; font-weight:bold;">商品成功編輯!</td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $web['forum_url']; ?>adpanel/products/">點擊返回商品管理介面.</a>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $web['forum_url']; ?>adpanel/">點擊返回管理面板.</a>
                                </td>
                            </tr>
                        </table>
                        <?php
                    }
                    else {
                        ?>
                        <form action="" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    名稱: <input type="text" name="name" value="<?php echo $row['name']; ?>"
                                               class="form-control" required>
                                </div>
                                <div class="col-md-12">
                                    說明: <input type="text" name="description" value="<?php echo $row['description']; ?>"
                                               class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <br/>
                                    <button type="submit" name="do_edit" class="btn btn-success" style="width:100%">
                                        <i class="fa fa-pencil"></i> 編輯
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                } else {
                    header("Location: " . $web['forum_url'] . "adpanel/");
                }
                ?>

            </div>
        </div>
        <?php
    }
    elseif ($_GET['type'] === "product_item") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    管理面板 &raquo; 編輯論壇
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $id = protect($_GET['id']);
                $sql = mysqli_query($conn, "SELECT * FROM forums WHERE id='$id'");
                if (mysqli_num_rows($sql) > 0) {
                    $row = mysqli_fetch_array($sql);

                    if (isset($_POST['do_edit'])) {
                        $value = protect($_POST['value']);
                        $category_id = protect($_POST['category_id']);
                        $icon = protect($_POST['icon']);

                        if (empty($icon)) {
                            $icon = "uploads/document-icon.png";
                        }

                        if (empty($value) or empty($category_id)) {
                            error("請填寫所有欄位!");
                        } else {
                            $success = 1;
                            $update = mysqli_query($conn, "UPDATE forums SET value='$value',category_id='$category_id',icon='$icon' WHERE id='$id'");
                        }
                    }

                    if (isset($success) && $success === 1) {
                        ?>
                        <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                            <tr>
                                <td align="center" style="font-size:16px; font-weight:bold;">
                                    Forum is edited successfully!
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $web['forum_url']; ?>adpanel/forum_sub_categories/">Click here
                                        to back to forums manager.</a>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <a href="<?php echo $web['forum_url']; ?>adpanel/">點擊返回管理面板.</a>
                                </td>
                            </tr>
                        </table>
                        <?php
                    } else {
                        ?>
                        <form action="" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    名稱: <input type="text" name="value" value="<?php echo $row['value']; ?>"
                                               class="form-control" required>
                                </div>
                                <div class="col-md-12">
                                    圖示: <input type="text" name="icon" value="<?php echo $row['icon']; ?>"
                                               class="form-control">
                                </div>
                                <div class="col-md-12">
                                    分類:
                                    <select name="category_id" class="form-control" required>
                                        <?php
                                        $get_cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY id");
                                        while ($get = mysqli_fetch_array($get_cats)) {
                                            ?>
                                            <option
                                            value="<?php echo $get['id']; ?>" <?php if ($row['category_id'] == $get['id']) {
                                                echo 'selected';
                                            } ?>><?php echo $get['value']; ?></option><?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <br/>
                                    <button type="submit" name="do_edit" class="btn btn-success" style="width:100%"><i
                                                class="fa fa-pencil"></i> Edit
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                } else {
                    $redir = $web['forum_url'] . "adpanel/";
                    header("Location: $redir");
                }
                ?>

            </div>
        </div>
        <?php
    }
    else {
        header("Location: " . $web['forum_url'] . "adpanel/");
    }
} elseif ($_GET['action'] === "delete") {
    if ($_GET['type'] === "category") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    管理面板 &raquo; 刪除分類
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $id = protect($_GET['id']);
                $sql = mysqli_query($conn, "SELECT * FROM categories WHERE id='$id'");
                if (mysqli_num_rows($sql) > 0) {
                    mysqli_query($conn, "DELETE FROM categories WHERE id='$id'");
                    mysqli_query($conn, "DELETE FROM forums WHERE category_id='$id'");
                    mysqli_query($conn, "DELETE FROM threads WHERE category_id='$id'");
                    mysqli_query($conn, "DELETE FROM replies WHERE category_id='$id'");
                } else {
                    header("Location: " . $web['forum_url'] . "adpanel/");
                }
                ?>

                <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                    <tr>
                        <td align="center" style="font-size:16px; font-weight:bold;"> 分類刪除成功!</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="<?php echo $web['forum_url']; ?>adpanel/forum_categories/">點擊返回分類管理介面.</a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="<?php echo $web['forum_url']; ?>adpanel/">點擊返回管理面板.</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
    elseif ($_GET['type'] === "forum") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    管理介面 &raquo; 刪除論壇
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $id = protect($_GET['id']);
                $sql = mysqli_query($conn, "SELECT * FROM forums WHERE id='$id'");
                if (mysqli_num_rows($sql) > 0) {
                    mysqli_query($conn, "DELETE FROM forums WHERE id='$id'");
                    mysqli_query($conn, "DELETE FROM threads WHERE forum_id='$id'");
                    mysqli_query($conn, "DELETE FROM replies WHERE forum_id='$id'");
                } else {
                    $redir = $web['forum_url'] . "adpanel/";
                    header("Location: $redir");
                }
                ?>
                <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                    <tr>
                        <td align="center" style="font-size:16px; font-weight:bold;"> 論壇成功刪除! </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="<?php echo $web['forum_url']; ?>adpanel/forum_sub_categories/">點擊返回論壇管理介面.</a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="<?php echo $web['forum_url']; ?>adpanel/">點擊返回管理面板.</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
    elseif ($_GET['type'] === "product") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    管理介面 &raquo; 刪除商品
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $id = protect($_GET['id']);
                $sql = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
                if (mysqli_num_rows($sql) > 0) {
                    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
                } else {
                    header("Location: ") . $web['forum_url'] . "adpanel/";
                }
                ?>
                <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                    <tr>
                        <td align="center" style="font-size:16px; font-weight:bold;"> 商品成功刪除! </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="<?php echo $web['forum_url']; ?>adpanel/products/">點擊返回商品管理介面.</a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="<?php echo $web['forum_url']; ?>adpanel/">點擊返回管理面板.</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
    elseif ($_GET['type'] === "product_item") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    管理介面 &raquo; 刪除論壇
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $id = protect($_GET['id']);
                $sql = mysqli_query($conn, "SELECT * FROM forums WHERE id='$id'");
                if (mysqli_num_rows($sql) > 0) {
                    mysqli_query($conn, "DELETE FROM forums WHERE id='$id'");
                    mysqli_query($conn, "DELETE FROM threads WHERE forum_id='$id'");
                    mysqli_query($conn, "DELETE FROM replies WHERE forum_id='$id'");
                } else {
                    header("Location: ") . $web['forum_url'] . "adpanel/";
                }
                ?>
                <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                    <tr>
                        <td align="center" style="font-size:16px; font-weight:bold;"> 論壇成功刪除! </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="<?php echo $web['forum_url']; ?>adpanel/forum_sub_categories/">點擊返回論壇管理介面.</a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="<?php echo $web['forum_url']; ?>adpanel/">點擊返回管理面板.</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
    else {
        header("Location: ") . $web['forum_url'] . "adpanel/";
    }
} elseif ($_GET['action'] === "view") {
    if ($_GET['type'] === "report") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    管理介面 &raquo; View Report
                </h3>
            </div>
            <div class="panel-body">

                <?php
                $id = protect($_GET['id']);
                $sql = mysqli_query($conn, "SELECT * FROM reports WHERE id='$id'");
                if (mysqli_num_rows($sql) > 0) {
                    $get = mysqli_fetch_array($sql);

                    if (isset($_POST['do_mark'])) {
                        $update = mysqli_query($conn, "UPDATE reports SET readed='yes' WHERE id='$id'");
                        header("Location: " .  $web['forum_url'] . "adpanel/reports/");
                    }
                    ?>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%"
                           id="replie_<?php echo $get['id']; ?>">
                        <tr>
                            <td valign="top" width="20%" align="center">
                                <br>
                                <a href="<?php echo $web['forum_url']; ?>user/<?php echo idinfo($get['user_id'], "usern"); ?>/"><span
                                            style="font-size:14px; font-weight:bold;"><?php echo idinfo($get['user_id'], "display_name"); ?></span></a><br/><br/>
                                <?php
                                if (idinfo($get['user_id'], "avatar") == NULL) {
                                    ?>
                                    <img src="<?php echo $web['forum_url']; ?>assets/imgs/davatar.jpg" class="avatar"
                                         width="130px" height="130px">
                                    <?php
                                } else {
                                    ?>
                                    <img src="<?php echo $web['forum_url']; ?>image.php?width=130&height=130&image=<?php echo $web['forum_url'];
                                    echo idinfo($get['user_id'], "avatar"); ?>&cropratio=1:1" class="avatar"
                                         width="130px" height="130px">
                                    <?php
                                }
                                ?>
                                <br>
                                <br>
                                <?php
                                $user_time = idinfo($get['user_id'], "online_time");
                                $timeon = time() - 600;
                                if ($user_time > $timeon) {
                                    ?>
                                    <span class="online">is ONLINE</span>
                                    <?php
                                } else {
                                    ?>
                                    <span class="offline">is OFFLINE</span>
                                    <?php
                                }
                                ?>
                            </td>
                            <td valign="top" class="line3"></td>
                            <td valign="top" width="80%" style="padding:5px;">
                                <table border="0" cellspacing="2" cellpadding="2" width="100%">
                                    <tr>
                                        <td valign="top"><span
                                                    style="font-size:9px;">This report was published on <b><?php echo $get['date']; ?></b> and it says:</span>
                                        </td>
                                        <td align="right">
                                            <form action="" method="POST"><input type="submit" class="btn btn-info"
                                                                                 value="Mark as readed" name="do_mark">
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                                <?php
                                echo bbcode(protect_post($get['content']));

                                echo '<br><br>';
                                if ($get['type'] == "thread") {
                                    $thread_link = $web['forum_url'] . "t/" . create_thread_link($get['post_id']) . "/" . $get['post_id'] . "/";
                                    echo '<b>Thread link:</b> <a href="' . $thread_link . '">' . $thread_link . '</a>';
                                } else {

                                    $select = mysqli_query($conn, "SELECT * FROM replies WHERE id='$get[post_id]'");
                                    $row = mysqli_fetch_array($select);
                                    $query1 = "SELECT COUNT(id) AS numrows FROM replies WHERE thread_id='$row[thread_id]' and forum_id='$row[forum_id]' and category_id='$row[category_id]'";
                                    $result = mysqli_query($conn, $query1) or die('Error, query failed');
                                    $rowz = mysqli_fetch_array($result, MYSQLI_ASSOC);

                                    $num = $rowz['numrows'];

                                    if ($num > 9) {
                                        $num1 = ceil($num / 10);
                                    }

                                    if (isset($num1) && $num1 > 1) {
                                        $replie_link = $web['forum_url'] . "t/" . create_thread_link($row['thread_id']) . "/" . $row['thread_id'] . "/" . $num1 . "/#replie_" . $row['id'];
                                    } else {
                                        $replie_link = $web['forum_url'] . "t/" . create_thread_link($row['thread_id']) . "/" . $row['thread_id'] . "/#replie_" . $row['id'];
                                    }

                                    echo '<b>Reply link:</b> <a href="' . $replie_link . '">' . $replie_link . '</a>';

                                }

                                if ($get['edited_by'] && $get['edited_on']) {
                                    echo '<br>';
                                    $editor = '<a href="' . $web[forum_url] . 'user/' . idinfo($get[edited_by], "usern") . '/">' . idinfo($get[edited_by], "display_name") . '</a>';
                                    error("This post has been edited by $editor on $get[edited_on]!");
                                }

                                $signature = idinfo($get['user_id'], "signature");
                                if (!empty($signature)) {
                                    echo '<hr/>' . protect_post($signature);
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <?php
                } else {

                }
                ?>
            </div>
        </div>
        <?php
    }
    else {
        header("Location: " . $redir = $web['forum_url'] . "adpanel/");
    }
} else {
    header("Location: " . $web['forum_url'] . "adpanel/");
}
?>
