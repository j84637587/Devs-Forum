<?php
include("includes/db_connect.php");

function load_header() {
global $web, $conn;
//get web settings
$web = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM settings ORDER BY id DESC LIMIT 1"));

$usern = isset($_SESSION['usern']) ? $_SESSION['usern'] : "";
if (!empty($usern)) {
    $check = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE usern='$usern'"));
    if ($check == 0) {
        unset($_SESSION['usern']);
        session_destroy();
        session_unset();
    }
}
?>
<html>
<head>
    <title><?php echo $web['forum_title']; ?> - <?php echo $web['forum_title']; ?></title>
    <meta name="author" content="slaweikoff">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link REL="SHORTCUT ICON" HREF="<?php echo $web['forum_url']; ?>assets/imgs/favicon.ico">
    <link type="text/css" rel="stylesheet"
          href="<?php echo $web['forum_url']; ?>assets/plugins/bootstrap/css/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet"
          href="<?php echo $web['forum_url']; ?>assets/plugins/font-awesome/css/font-awesome.min.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo $web['forum_url']; ?>assets/css/style.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo $web['forum_url']; ?>assets/css/main.css"/>
    <script type="text/javascript" src="<?php echo $web['forum_url']; ?>assets/js/field.js"></script>
    <script type="text/javascript" src="<?php echo $web['forum_url']; ?>assets/js/submit.js"></script>
</head>
<body>
<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top" style="margin-bottom:0;">
    <div class="container" style="width:90%;">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="<?php echo $web['forum_url']; ?>" class="navbar-brand"><?php echo $web['forum_title']; ?>
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?php echo $web['forum_url']; ?>index.php">主頁</a></li>
                <li><a href="<?php echo $web['forum_url']; ?>online_users">使用者</a></li>
                <li><a href="<?php echo $web['forum_url']; ?>shop">商店</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isset($_SESSION['usern']) && $_SESSION['usern']) {
                    ?>
                    <li><a>歡迎, <?php echo $_SESSION['usern']; ?>!</a></li>
                    <li><a href="<?php echo $web['forum_url']; ?>profile/">個人檔案</a></li>
                    <?php if (userinfo($_SESSION['usern'], "level") === "admin") { ?>
                        <li>    <a href="<?php echo $web['forum_url']; ?>adpanel/">管理面板</a><?php } ?> </li>
                    <li><a href="<?php echo $web['forum_url']; ?>logout/">登出</a></li>
                    <?php
                } else {
                    ?>
                    <li>
                        <a href="<?php echo $web['forum_url']; ?>sign_in/">
                            <i class="fa fa-key"></i> 登入
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $web['forum_url']; ?>sign_up/">
                            <i class="fa fa-plus"></i> 註冊
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</nav>
<div class="welcome_header">
    <h1>歡迎來到 <?php echo $web['forum_title']; ?>!</h1>
    <h3><?php echo $web['forum_description']; ?></h3>
</div>

<div class="bottom_header_navi">
    <div class="container" style="width:92%;">
        <div class="row">
            <div class="col-md-4 pull-left padding15">
                <ol class="breadcrumb" style="background:none;padding:0px;padding-top:7px;">
                    <li><a href="<?php echo $web['forum_url']; ?>">首頁</a></li>
                    <li class="active">論壇</li>
                </ol>
            </div>
            <div class="col-md-3 pull-right padding15">
                <form action="<?php echo $web['forum_url']; ?>search/" method="POST" class="">
                    <div class="input-group">
                        <input name="search_name" class="form-control" value="搜索..." onblur="addText(this);"
                               onfocus="clearText(this)">
                        <span class="input-group-btn"><button class="btn btn-default" type="submit">查詢</button></span>
                    </div><!-- /input-group -->
                </form>
            </div>

            <div class="col-md-4 pull-right padding15">

                <div class="form-group">
                    <label class="col-md-3 control-label" style="padding:8px">搜尋: </label>
                    <div class="col-md-9">
                        <select class="form-control">
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM categories ORDER BY id");
                            if (mysqli_num_rows($sql) > 0) {
                                while ($r = mysqli_fetch_array($sql)) {
                                    echo "<option>" . $r['value'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<br>
<div class="container" style="width:92%;">
    <div class="row">
        <div class="col-md-12" style="padding:0px;padding-top:20px">
            <!-- forum start -->
            <?php
            }

            function load_footer() {
            global $web, $conn;
            //get web settings
            $web = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM settings ORDER BY id DESC LIMIT 1"));
            ?>
        </div>
    </div>
</div>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 line-height">&copy; <?php echo date("Y"); ?> <?php echo $web['forum_title']; ?> | All
                rights reserved.
            </div>
            <div class="col-sm-4 sociconcent line-height">
                <ul class="socialicons">
                    <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                    <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                    <li><a href="#"><i class="fa fa-cloud"></i></a></li>
                    <li><a href="#"><i class="fa fa-rss"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

</body>
<html>
<?php
}
?>
