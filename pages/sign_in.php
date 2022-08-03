<?php
if (isset($_SESSION['usern']) && $_SESSION['usern']) {
    header("Location: $web[forum_url]");
}
?>
<div class="col-md-2" style="padding:0px;padding-top:15px;">
</div>
<div class="col-md-8" style="padding:0px;padding-top:15px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">登入</h3>
        </div>
        <div class="panel-body">
            <?php
            if (isset($_POST['do_login'])) {
                $usern = protect($_POST['usern']);
                $passwd = protect($_POST['passwd']);
                global $web, $conn;
                $sql = mysqli_query($conn, "SELECT * FROM users WHERE usern='$usern' and passwd='$passwd'");
                if (mysqli_num_rows($sql) > 0) {
                    $_SESSION['usern'] = $usern;
                    header("Location: $web[forum_url]");
                } else {
                    error("錯誤的使用者名稱或密碼!");
                }
            }
            ?>
            <div class="row">
                <div class="col-md-8">
                    <form class="form-horizontal" role="form" method="POST">
                        <div class="form-group">
                            <label for="Username" class="col-md-2 control-label">使用者名稱</label>
                            <div class="col-md-7">
                                <div class="input-icon">
                                    <i class="fa fa-user"></i>
                                    <input class="form-control" id="Username" placeholder="使用者名稱" name="usern" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword1" class="col-md-2 control-label">密碼</label>
                            <div class="col-md-7">
                                <div class="input-icon">
                                    <i class="fa fa-key"></i>
                                    <input class="form-control" id="inputPassword1" placeholder="密碼" name="passwd"
                                           type="password">
                                </div>
                                <div class="help-block">

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-10">
                                <b>沒有帳號?</b> <a href="<?php echo $web['forum_url']; ?>sign_up/">現在註冊一個.</a>
                                <br/>
                                <a href="<?php echo $web['forum_url']; ?>lost_password/">忘記密碼?</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-10">
                                <button type="submit" name="do_login" class="btn btn-success"><i class="fa fa-key"></i>
                                    登入
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
