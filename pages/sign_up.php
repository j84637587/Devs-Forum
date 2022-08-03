<?php
if (isset($_SESSION['usern']) && $_SESSION['usern']) {
    header("Location: $web[forum_url]");
}
?>
<div class="col-md-2" style="padding: 15px 0 0;"></div>
<div class="col-md-8" style="padding:0px;padding-top:15px;">
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">註冊</h3>
    </div>
    <div class="panel-body">
        <?php
        if (isset($_POST['do_register'])) {
            $display_name = protect($_POST['display_name']);
            $usern = protect($_POST['usern']);
            $passwd = protect($_POST['passwd']);
            $cpasswd = protect($_POST['cpasswd']);
            $email = protect($_POST['email']);

            global $web, $conn;

            $sql_check_1 = mysqli_query($conn, "SELECT * FROM users WHERE display_name='$display_name'");
            $sql_check_2 = mysqli_query($conn, "SELECT * FROM users WHERE usern='$usern'");
            $sql_check_3 = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

            if (empty($display_name) || empty($usern) || empty($passwd) || empty($cpasswd) || empty($email)) {
                error("請填寫所有欄位!");
            }
			elseif (strlen($display_name) < 5) {
                error("名稱至少要有5個英文字長度(中文字為2).");
            } elseif (strlen($usern) < 5) {
                error("使用者名稱至少要有5個英文字長度(中文字為2).");
            } elseif (!filterName($usern)) {
                error("請輸入有效的使用者名稱!");
            } elseif (mysqli_num_rows($sql_check_1) > 0) {
                error("此名稱已被其他使用者取用!");
            } elseif (mysqli_num_rows($sql_check_2) > 0) {
                error("此名稱使用者已被其他使用者取用!");
            } elseif (mysqli_num_rows($sql_check_3) > 0) {
                error("此電子信箱已被其他使用者註冊!");
            } elseif ($passwd !== $cpasswd) {
                error("密碼不匹配!");
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                error("請輸入有效的電子信箱!");
            } else {
                $date = date("d M Y  H:i");
                $insert = mysqli_query($conn, "INSERT users (usern,passwd,email,display_name,level,reg_date) VALUES ('$usern','$passwd','$email','$display_name','member','$date')") or die(mysqli_error($conn));
                success("註冊成功! 現在可以登入你的帳號囉.");
            }
        }
        ?>
        <form class="form-horizontal" role="form" method="POST">
            <div class="form-group">
                <label for="" class="col-md-3 control-label">顯示名稱</label>
                <div class="col-md-7">
                    <div class="input-icon">
                        <i class="fa fa-eye"></i>
                        <input class="form-control" name="display_name" type="text">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-3 control-label">使用者名稱</label>
                <div class="col-md-7">
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control" name="usern" type="text">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-3 control-label">密碼</label>
                <div class="col-md-7">
                    <div class="input-icon">
                        <i class="fa fa-key"></i>
                        <input class="form-control" name="passwd" type="password">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-3 control-label">確認密碼</label>
                <div class="col-md-7">
                    <div class="input-icon">
                        <i class="fa fa-key"></i>
                        <input class="form-control" name="cpasswd" type="password">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-md-3 control-label">電子信箱</label>
                <div class="col-md-7">
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control" name="email" type="text">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-md-3 control-label"></label>
                <div class="col-md-7">
                    <button type="submit" class="btn btn-success" name="do_register" style="width:100%">註冊</button>
                    <br /><br />
                    <b>擁有帳號?</b> <a href="<?php echo $web['forum_url']; ?>sign_in/"> 立即登入吧!</a>
                    <br />
                    <a href="<?php echo $web['forum_url']; ?>lost_password/">忘記密碼?</a>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
