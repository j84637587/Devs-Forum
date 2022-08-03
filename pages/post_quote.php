<?php
global $conn, $web;
if (!$_SESSION['usern']) {
    header("Location: " . $web['forum_url'] . "sign_in/");
}

$type = $_GET['type'];
$id = $_GET['id'];

if ($type === "thread") {
    $sql = mysqli_query($conn, "SELECT * FROM threads WHERE id='$id'");
} else {
    $sql = mysqli_query($conn, "SELECT * FROM replies WHERE id='$id'");
}
$row = mysqli_fetch_array($sql);
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">引用</h3>
    </div>
    <div class="panel-body">

        <?php
        if (isset($_POST['do_post'])) {
            $content = $_POST['content'];
            $user_id = userinfo($_SESSION['usern'], "id");
            if ($type === "thread") {
                $thread_id = $row['id'];
            } else {
                $thread_id = $row['thread_id'];
            }

            if (empty($content)) {
                error("請填寫所有欄位!");
            } else {
                $insert = mysqli_query($conn, "INSERT replies (thread_Id,category_id,forum_id,user_id,date,content) VALUES ('$thread_id','$row[category_id]','$row[forum_id]','$user_id',SYSDATE(),'$content')") or die(mysqli_error($conn));
                $success = 1;
                $get_sql = mysqli_query($conn, "SELECT * FROM replies ORDER BY id DESC LIMIT 1");
                $get = mysqli_fetch_array($get_sql);


                $query1 = "SELECT COUNT(id) AS numrows FROM replies WHERE thread_id='$thread_id' and forum_id='$row[forum_id]' and category_id='$row[category_id]'";
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
                        留言成功!
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <a href="<?php echo $replie_link; ?>">點擊查看留言.</a>
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
                    <tr>
                        <td colspan="2">
                            <?php
                            $newcontent = '[quote=' . idinfo($row['user_id'], "display_name") . ']<br/>' . $row['content'] . '<br/>[/quote]<br>';
                            include("./ckeditor/ckeditor_php5.php");
                            $CKEditor = new CKEditor();
                            $CKEditor->editor("content", $newcontent);
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
