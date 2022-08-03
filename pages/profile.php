<?php
global $web, $conn;
if (!$_SESSION['usern']) {
    $redir = $web['forum_url'] . "sign_in/";
    header("Location: $redir");
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">個人檔案</h3>
    </div>
    <div class="panel-body">
        <?php
        $user_id = userinfo($_SESSION['usern'], "id");
        $user_points = userinfo($_SESSION['usern'], "points");
        if (isset($_POST['do_upload']) && $_POST['do_upload']) {
            list($width, $height, $type, $attr) = getimagesize($_FILES['uploadfile']['tmp_name']);

            if ($_FILES['uploadfile']['size'] > 153600) {
                error("頭像圖片最大為 150KB.");
            } else {
                $ext = array('jpg', 'gif', 'JPG', 'JPEG', 'jpeg', 'png');
                $extnafaila = end(explode('.', $_FILES['uploadfile']['name']));
                $extnafaila = strtolower($extnafaila);
                if (in_array($extnafaila, $ext)) {
                    $putq = 'uploads/' . randStr(12) . '.' . $extnafaila;
                    if (@move_uploaded_file($_FILES['uploadfile']['tmp_name'], $putq)) {
                        $user_id = userinfo($_SESSION['usern'], "id");
                        $update = mysqli_query($conn, "UPDATE users SET avatar='$putq' WHERE id='$user_id'");
                        success("頭像上傳成功!");
                    } else {
                        error("Problem uploading a file! Please try again.");
                    }
                } else {
                    error("不允許的檔案類型!");
                }
            }
        }

        if (isset($_POST['do_change_display_name']) && $_POST['do_change_display_name']) {
            $display_name = protect($_POST['display_name']);

            $sql = mysqli_query($conn, "SELECT * FROM users WHERE display_name='$display_name'");
            if (empty($display_name)) {
                error("請輸入名稱!");
            } elseif (strlen($display_name) < 5) {
                error("名稱至少要有5個英文字長度(中文字為2).");
            } else if (userinfo($_SESSION['usern'], "display_name") !== $display_name) {
                if (mysqli_num_rows($sql) > 0) {
                    error("名稱已被其他會員取過了!");
                } else {
                    mysqli_query($conn, "UPDATE users SET display_name='$display_name' WHERE id='$user_id'");
                    success("名稱更改成功!");
                }
            }
        }

        if (isset($_POST['do_change_signature']) && $_POST['do_change_signature']) {
            $signature = $_POST['signature'];
            mysqli_query($conn, "UPDATE users SET signature='$signature' WHERE id='$user_id'");
            success("個人簡介更改成功!");
        }

        if (isset($_POST['do_change_password']) && $_POST['do_change_password']) {
            $cpasswd = protect($_POST['cpasswd']);
            $npasswd = protect($_POST['npasswd']);
            $cnpasswd = protect($_POST['cnpasswd']);

            if (userinfo($_SESSION['usern'], "passwd") !== $cpasswd) {
                error("當前密碼錯誤!");
            } elseif (empty($npasswd)) {
                error("請輸入新密碼!");
            } elseif ($npasswd !== $cnpasswd) {
                error("新密碼不匹配!");
            } else {
                mysqli_query($conn, "UPDATE users SET passwd='$npasswd' WHERE id='$user_id'");
                success("密碼成功更改!");
            }
        }
        ?>

        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td width="33%" valign="top" style="padding:10px;">
                    <h3>統計</h3>
                    <form action="" method="POST">
                        <table border="0" cellspacing="2" cellpadding="2">
                            <tr style="height:50px;">
                                <td class="field_text">點數:&nbsp;</td>
                                <td>
                                    <span><?php echo $user_points; ?></span>
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
                <td width="33%" valign="top" style="padding:10px;">
                    <h3>更改密碼</h3>
                    <form action="" method="POST">
                        <table border="0" cellspacing="2" cellpadding="2">
                            <tr style="height:50px;">
                                <td class="field_text">當前密碼:&nbsp;</td>
                                <td>
                                    <input type="password" class="form-control" size="25" name="cpasswd">
                                </td>
                            </tr>
                            <tr style="height:50px;">
                                <td class="field_text">新密碼:&nbsp;</td>
                                <td>
                                    <input type="password" class="form-control" size="25" name="npasswd">
                                </td>
                            </tr>
                            <tr style="height:50px;">
                                <td class="field_text">確認密碼:&nbsp;</td>
                                <td>
                                    <input type="password" class="form-control" size="25" name="cnpasswd">
                                </td>
                            </tr>
                            <tr style="height:50px;">
                                <td colspan="2">
                                    <input type="submit" name="do_change_password" class="btn btn-success" value="更改">
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
                <td width="33%" valign="top" style="padding:10px;">
                    <h3>更改你的名稱</h3>
                    <form action="" method="POST">
                        <table border="0" cellspacing="2" cellpadding="2">
                            <tr style="height:50px;">
                                <td class="field_text">名稱:&nbsp;</td>
                                <td style="padding-left:10px;">
                                    <input type="text" class="form-control" size="25" name="display_name"
                                           value="<?php echo userinfo($_SESSION['usern'], "display_name"); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="submit" name="do_change_display_name" class="btn btn-success"
                                           value="更改">
                                </td>
                            </tr>
                        </table>
                    </form>

                </td>
            </tr>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td width="20%" valign="top" style="padding:10px;">
                    <h3>更改頭像</h3>
                    <?php
                    if (userinfo($_SESSION['usern'], "avatar") == NULL) {
                        ?>
                        <img src="<?php echo $web['forum_url']; ?>assets/imgs/davatar.jpg" width="130px" height="130px"
                             class="avatar">
                        <?php
                    } else {
                        ?>
                        <img src="<?php echo $web['forum_url']; ?>image.php?width=130&height=130&image=<?php echo $web['forum_url'];
                        echo userinfo($_SESSION['usern'], "avatar"); ?>&cropratio=1:1" class="avatar">
                        <?php
                    }
                    ?>
                    <br><br>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <input type='file' id='file_browse' name='uploadfile'/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <br>
                                    <input type="submit" class="btn btn-success" name="do_upload" value="上傳">
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
                <td width="80%" valign="top" style="padding:10px;">
                    <h3>個人簡介</h3>
                    <form action="" method="POST">
                        <?php
                        $signature = userinfo($_SESSION['usern'], "signature");
                        include("./ckeditor/ckeditor_php5.php");
                        $CKEditor = new CKEditor();
                        $CKEditor->editor("signature", "$signature");
                        ?><br/>
                        <input type="submit" class="btn btn-success" value="更改" name="do_change_signature">
                    </form>
                </td>
            </tr>
            <tr>
                <td colspan="2"><br/><br/><br/></td>
            </tr>
            <tr>
                <td colspan="2">
                    <h3>已購買的商品</h3>
                </td>
            </tr>
            <tr>
                <td>商品名稱</td>
                <td>商品代號</td>
            </tr>
            <?php
            $result = mysqli_query($conn, "SELECT product_items.*, products.name FROM product_items
                                           INNER JOIN products ON product_items.product_id = products.id AND product_items.PUR_by = '$user_id'
                                           ORDER BY PUR_on DESC "
            ) or die(mysqli_error($conn));
            if ($result->num_rows > 0) {
                while ($r = $result->fetch_assoc()) {
                    echo "            
            <tr>
                <td>$r[name]</td>
                <td>$r[key]</td>
            </tr>";
                }
            }
            ?>
        </table>
    </div>
</div>
