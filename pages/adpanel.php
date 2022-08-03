<?php
global $web, $conn;
if (!$_SESSION['usern']) {
    $redir = $web['forum_url'] . "sign_in/";
    header("Location: $redir");
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
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            管理面板 <?php
            if (isset($_GET['p'])) {
                switch ($_GET['p']) {
                    case "forum_categories":
                        echo '&raquo; 分類';
                        break;
                    case "forum_sub_categories":
                        echo '&raquo; 論壇';
                        break;
                    case "products":
                        echo '&raquo; 商品';
                        break;
                    case "product_items":
                        echo '&raquo; 序號';
                        break;
                    default:
                        break;
                }
            }
            ?>
        </h3>
    </div>
    <div class="panel-body">
        <?php
        if(isset($_GET['p'])){
        if ($_GET['p'] === "forum_categories") {
            if (isset($_POST['do_add'])) {
                $value = protect($_POST['value']);
                $sql = mysqli_query($conn, "SELECT * FROM categories WHERE value='$value'") or die(mysqli_error($conn));

                if (empty($value)) {
                    error("請填寫所有欄位!");
                } elseif (mysqli_num_rows($sql) > 0) {
                    error("此分類已存在!");
                } else {
                    $insert = mysqli_query($conn, "INSERT categories (value) VALUES ('$value')");
                    success("分類新增成功!");
                }
            }
        ?>
        <div class="clearfix"></div>
        <form action="" method="POST">
            <table border="0" cellspacing="2" cellpadding="2">
                <tr>
                    <td style="padding-right:10px;"><input type="text" name="value" size="30" class="form-control"></td>
                    <td>
                        <button type="submit" name="do_add" class="btn btn-success">
                            <i class="fa fa-plus"></i> 添加分類
                        </button>
                    </td>
                </tr>
            </table>

            <br>
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <?php
                $i = 0;
                $sql = mysqli_query($conn, "SELECT * FROM categories ORDER BY id");
                if (mysqli_num_rows($sql) > 0) {
                    while ($row = mysqli_fetch_array($sql)) {
                        $nums = mysqli_num_rows($sql);
                        ?>
                        <tr>
                            <td style="padding: 10px;"><span class="forum_link"><?php echo $row['value']; ?></td>
                            <td style="padding: 5px;">
                                <table border="0" cellspacing="2" cellpadding="2" align="right">
                                    <tr>
                                        <td><a href="javascript:void(0);"
                                               onclick="confirmm('<?php echo $web['forum_url']; ?>adpanel_func/edit/category/<?php echo $row['id']; ?>/');"
                                               class="btn btn-info">編輯</a> &nbsp;
                                        </td>
                                        <td><a href="javascript:void(0);"
                                               onclick="confirmm('<?php echo $web['forum_url']; ?>adpanel_func/delete/category/<?php echo $row['id']; ?>/');"
                                               class="btn btn-danger">刪除</a></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                        $i++;
                        if ($i !== $nums) {
                            echo '<tr><td colspan="2"><div class="line2"></div></td></tr>';
                        }
                    }
                } else {
                    echo '<tr><td>';
                    warning('資料庫無相關資料.');
                    echo '</td></tr>';
                }
                ?>
            </table>

            <?php
            }
        elseif ($_GET['p'] === "forum_sub_categories") {
            if (isset($_POST['do_add'])) {
                $icon = protect($_POST['icon']);
                $value = protect($_POST['value']);
                $category_id = protect($_POST['category_id']);
                $sql = mysqli_query($conn, "SELECT * FROM forums WHERE value='$value' and category_id='$category_id'") or die(mysqli_error($conn));
                if (empty($icon)) {
                    $icon = "uploads/document-icon.png";
                }
                if (empty($value) or empty($category_id)) {
                    error("請填寫所有欄位!");
                } elseif (mysqli_num_rows($sql) > 0) {
                    error("此論壇已存在於此分類中!");
                } else {
                    $insert = mysqli_query($conn, "INSERT forums (value,category_id,icon) VALUES ('$value','$category_id','$icon')") or die(mysqli_error($conn));
                    success("成功新增$value!");
                }
            }
            ?>
            <div class="clearfix"></div>
            <form action="" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        名稱: <input type="text" name="value" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        圖示: <input type="text" name="icon" class="form-control">
                    </div>
                    <div class="col-md-6">
                        分類:
                        <select name="category_id" class="form-control" required>
                            <option value="">選擇分類</option>
                            <option value="">- - - - - - - - - - -</option>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM categories ORDER BY id");
                            while ($row = mysqli_fetch_array($sql)) {
                                echo '<option value="' . $row[id] . '">' . $row[value] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <br/>
                        <button type="submit" name="do_add" class="btn btn-success" style="width:100%">
                            <i class="fa fa-plus"></i> 添加論壇
                        </button>
                    </div>
                </div>

                <hr/>
                <div class="row">
                    <div class="col-md-12" style="padding:10px;">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <?php
                            $i = 0;
                            $sql = mysqli_query($conn, "SELECT * FROM forums ORDER BY id");
                            if (mysqli_num_rows($sql) > 0) {
                                while ($row = mysqli_fetch_array($sql)) {
                                    $nums = mysqli_num_rows($sql);
                                    $cat = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM categories WHERE id='$row[category_id]'"));
                                    ?>
                                    <tr style="height:60px;">
                                        <td style="width:55px;">
                                            <img src="<?php echo $web['forum_url']; ?><?php echo $row['icon']; ?>"/>
                                        </td>
                                        <td style="padding-bottom: 10px;"><h5><?php echo $cat['value']; ?>
                                                &raquo; <?php echo $row['value']; ?></h5></td>
                                        <td>
                                            <table border="0" cellspacing="0" cellpadding="0" align="right">
                                                <tr>
                                                    <td><a href="javascript:void(0);"
                                                           onclick="confirmm('<?php echo $web['forum_url']; ?>adpanel_func/edit/forum/<?php echo $row['id']; ?>/');"
                                                           class="btn btn-info">編輯</a> &nbsp;
                                                    </td>
                                                    <td><a href="javascript:void(0);"
                                                           onclick="confirmm('<?php echo $web['forum_url']; ?>adpanel_func/delete/forum/<?php echo $row['id']; ?>/');"
                                                           class="btn btn-danger">刪除</a></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                    if ($i !== $nums) {
                                        echo '<tr><td colspan="2"><div class="line2"></div></td></tr>';
                                    }
                                }
                            } else {
                                echo '<tr><td>';
                                warning('資料庫無相關資料.');
                                echo '</td></tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <?php
                }
        elseif ($_GET['p'] === "products") {
                if (isset($_POST['do_add'])) {
                    $name = protect($_POST['name']);
                    $description = protect($_POST['description']);
                    $sql = mysqli_query($conn, "SELECT * FROM products WHERE name='$name'") or die(mysqli_error($conn));

                    if (empty($name)) {
                        error("請填寫所有欄位!");
                    } elseif (mysqli_num_rows($sql) > 0) {
                        error("此產品已存在!");
                    } else {
                        $insert = mysqli_query($conn, "INSERT products (name, description) VALUES ('$name', '$description')");
                        success("分類新增成功!");
                    }
                }
                ?>
                <div class="clearfix"></div>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-3">
                            名稱: <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-9">
                            介紹: <input type="text" name="description" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br/>
                        <button type="submit" name="do_add" class="btn btn-success" style="width:100%">
                            <i class="fa fa-plus"></i> 添加商品
                        </button>
                    </div>
                    <br/><br/><br/><br/>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <?php
                        $i = 0;
                        $sql = mysqli_query($conn, "SELECT * FROM products ORDER BY id");
                        if (mysqli_num_rows($sql) > 0) {
                            while ($row = mysqli_fetch_array($sql)) {
                                $nums = mysqli_num_rows($sql);
                                ?>
                                <tr>
                                    <td style="padding: 10px;"><span class="forum_link"><?php echo $row['name']; ?></td>
                                    <td style="padding: 10px;"><span class="forum_link"><?php echo $row['description']; ?></td>
                                    <td style="padding: 5px;">
                                        <table border="0" cellspacing="2" cellpadding="2" align="right">
                                            <tr>
                                                <td>
                                                    <a href="javascript:void(0);"
                                                       onclick="confirmm('<?php echo $web['forum_url']; ?>adpanel_func/edit/product/<?php echo $row['id']; ?>/');"
                                                       class="btn btn-info">編輯
                                                    </a>&nbsp;
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);"
                                                       onclick="confirmm('<?php echo $web['forum_url']; ?>adpanel_func/delete/product/<?php echo $row['id']; ?>/');"
                                                       class="btn btn-danger">刪除
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                                if ($i !== $nums) {
                                    echo '<tr><td colspan="2"><div class="line2"></div></td></tr>';
                                }
                            }
                        } else {
                            echo '<tr><td>';
                            warning('資料庫無相關資料.');
                            echo '</td></tr>';
                        }
                        ?>
                    </table>

                    <?php
                    }
        elseif ($_GET['p'] === "product_items") {
                if (isset($_POST['do_add'])) {
                    $name = protect($_POST['name']);
                    $description = protect($_POST['description']);
                    $sql = mysqli_query($conn, "SELECT * FROM products WHERE name='$name'") or die(mysqli_error($conn));

                    if (empty($name)) {
                        error("請填寫所有欄位!");
                    } elseif (mysqli_num_rows($sql) > 0) {
                        error("此產品已存在!");
                    } else {
                        $insert = mysqli_query($conn, "INSERT products (name, description) VALUES ('$name', '$description')");
                        success("分類新增成功!");
                    }
                }
                ?>
                <div class="clearfix"></div>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-3">
                            名稱: <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-9">
                            介紹: <input type="text" name="description" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br/>
                        <button type="submit" name="do_add" class="btn btn-success" style="width:100%">
                            <i class="fa fa-plus"></i> 添加商品
                        </button>
                    </div>
                    <br/><br/><br/><br/>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <?php
                        $i = 0;
                        $sql = mysqli_query($conn, "SELECT * FROM product_item_full_data");
                        if (mysqli_num_rows($sql) > 0) {
                            while ($row = mysqli_fetch_array($sql)) {
                                $nums = mysqli_num_rows($sql);
                                ?>
                                <tr>
                                    <td style="padding: 10px;"><span class="forum_link"><?php echo $row['name']; ?></td>
                                    <td style="padding: 10px;"><span class="forum_link"><?php echo $row['key']; ?></td>
                                    <td style="padding: 5px;">
                                        <table border="0" cellspacing="2" cellpadding="2" align="right">
                                            <tr>
                                                <?php
                                                if(isset($row['PUR_on'], $row['PUR_by']))
                                                {?>
                                                    <td colspan="2">
                                                        <a href="javascript:void(0);" disabled="disabled" style="width: 115px;"
                                                           class="btn btn-default">已售出給 <?php echo $row['display_name'];?>
                                                        </a>&nbsp;
                                                    </td>
                                                <?php
                                                } else{
                                                ?>
                                                <td>
                                                    <a href="javascript:void(0);"
                                                       onclick="confirmm('<?php echo $web['forum_url']; ?>adpanel_func/edit/product_item/<?php echo $row['id']; ?>/');"
                                                       class="btn btn-info">編輯
                                                    </a>&nbsp;
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);"
                                                       onclick="confirmm('<?php echo $web['forum_url']; ?>adpanel_func/delete/product_item/<?php echo $row['id']; ?>/');"
                                                       class="btn btn-danger">刪除
                                                    </a>
                                                </td>
                                                <?php
                                                }
                                                ?>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                                if ($i !== $nums) {
                                    echo '<tr><td colspan="2"><div class="line2"></div></td></tr>';
                                }
                            }
                        } else {
                            echo '<tr><td>';
                            warning('資料庫無相關資料.');
                            echo '</td></tr>';
                        }
                        ?>
                    </table>

                    <?php
                    }
        else {
                info("<b>歡迎來到論壇管理面板!</b><br/><br/>在這裡你可以輕鬆的控制整個論壇. 使用者的功能層級分別有： admin, banned, member.");
            }
        } else {
                    info("<b>歡迎來到論壇管理面板!</b><br/><br/>在這裡你可以輕鬆的控制整個論壇. 使用者的功能層級分別有： admin, banned, member.");
                }?>
    </div>
</div>
