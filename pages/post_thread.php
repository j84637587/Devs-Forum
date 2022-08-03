<?php
global $conn, $web;
if (!$_SESSION['usern']) {
    header("Location: ") . $web['forum_url'] . "sign_in/";
}

$sql = mysqli_query($conn, "SELECT * FROM forums WHERE id='$_GET[forum_id]'");
if (mysqli_num_rows($sql) > 0) {
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">發布文章</h3>
        </div>
        <div class="panel-body">
            <?php
            if (isset($_POST['do_post'])) {
                $title = protect($_POST['title']);
                $tags = protect($_POST['tags']);
                $content = $_POST['content'];
                $user_id = userinfo($_SESSION['usern'], "id");

                if (empty($title) || empty($tags) || empty($content)) {
                    error("請填寫所有欄位!");
                } elseif (!explode(",", $tags)) {
                    error("標籤請用逗號`,`分隔!");
                } else {
                    $insert = mysqli_query($conn, "INSERT threads (title,tags,category_id,forum_id,user_id,date,content,locked) VALUES ('$title','$tags','$_GET[category_id]','$_GET[forum_id]','$user_id',SYSDATE(),'$content','no')") or die(mysqli_error($conn));
                    $success = 1;
                    $get_sql = mysqli_query($conn, "SELECT * FROM last_thread");
                    $get = mysqli_fetch_array($get_sql);
                    $thread_link = $web['forum_url'] . "t/" . create_thread_link($get['id']) . "/" . $get['id'] . "/#thread_" . $get['id'];
                }
            }

            if (isset($success) && $success === 1) {
                ?>
                <table border="0" cellspacing="2" cellpadding="2" align="center" style="padding:20px;">
                    <tr>
                        <td align="center" style="font-size:16px; font-weight:bold;">你的文章成功發佈!</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="<?php echo $thread_link; ?>">點擊查看發佈的文章.</a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <a href="<?php echo $web['forum_url']; ?>">點擊返回主頁.</a>
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
                            <td style="width:40px;">標題:</td>
                            <td width="padding-left:10px;">
                                <input type="text" class="form-control" size="50" name="title">
                            </td>
                        </tr>
                        <tr style="height:50px;">
                            <td class="field_text">標籤:</td>
                            <td>
                                <input type="text" class="form-control" size="50" name="tags">
                            </td>
                        </tr>
                        <tr style="height:50px;">
                            <td class="field_text">收費:<br/><span>(0 表示不收費)</span></td>
                            <td>
                                <input type="number" class="form-control" size="50" name="charge">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br/>
                                <?php
                                include("./ckeditor/ckeditor_php5.php");
                                $CKEditor = new CKEditor();
                                $CKEditor->editor("content", "");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br/>
                                <input type="submit" class="btn btn-success" name="do_post" value="發佈">
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
?>
