<?php
global $conn, $web;
if (!$_SESSION['usern']) {
    $redir = $web['forum_url'] . "sign_in/";
    header("Location: $redir");
}

$type = $_GET['type'];
$id = $_GET['id'];
$user_id = userinfo($_SESSION['usern'], "id");

if ($type === "thread") {
    $sql = mysqli_query($conn, "SELECT * FROM threads WHERE id='$id' and user_id='$user_id'");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_array($sql);
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Edit Thread
                </h3>
            </div>
            <div class="panel-body">

                <?php
                if ($_POST['do_post']) {
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

                if (isset($success) && $success === 1) {
                    ?>
                    <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                        <tr>
                            <td align="center" style="font-size:16px; font-weight:bold;">
                                Your thread has been edited successfully!
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <a href="<?php echo $thread_link; ?>">Click here to view and read your edited
                                    thread.</a>
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
                                <td style="width:40px;">標題:</td>
                                <td><input type="text" class="form-control" size="50" name="title"
                                           value="<?php echo $row['title']; ?>"></td>
                            </tr>
                            <tr>
                                <td class="field_text">Tags:</td>
                                <td><input type="text" class="form-control" size="50" name="tags"
                                           value="<?php echo $row['tags']; ?>"></td>
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
                                    <input type="submit" class="btn btn-success" name="do_post" value="發布">
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

} else {
    $sql = mysqli_query($conn, "SELECT * FROM replies WHERE id='$id' and user_id='$user_id'");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_array($sql);
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">編輯留言</h3>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST['do_post']) && $_POST['do_post']) {
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

                if (isset($success) && $success === 1) {
                    ?>
                    <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                        <tr>
                            <td align="center" style="font-size:16px; font-weight:bold;">
                                留言編輯成功!
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <a href="<?php echo $replie_link; ?>">點擊查看編輯的留言.</a>
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
                                <td colspan="2"><div class="line3"><br/></div></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="submit" class="btn" name="do_post" value="發佈">
                                    <input type="reset" class="btn" value="清除">
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
}
?>
