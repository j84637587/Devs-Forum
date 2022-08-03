<?php
//error_reporting(0);
ob_start();
session_start();
include("includes/db_connect.php");
include("includes/utilities.php");
include("includes/loaders.php");

//取得網站設訂
global $web, $conn;
$web = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM settings ORDER BY id DESC LIMIT 1"));

//更新在線人數
if (isset($_SESSION['usern']) && $_SESSION['usern']) {
    $user_id = userinfo($_SESSION['usern'], "id");
    $online_time = time();
    mysqli_query($conn, "UPDATE users SET online_time='$online_time' WHERE id='$user_id'");
}

//更新論壇拜訪數
update_visits();
load_header();
$page = isset($_GET['page']) ? $_GET['page'] : null;
switch ($page) {
    case "tag":
        include("pages/tag.php");
        break;
    case "forum_sign_in":                   // 登入
        include("pages/sign_in.php");
        break;
    case "forum_sign_up":                   // 註冊
        include("pages/sign_up.php");
        break;
    case "forum_lost_password":
        include("pages/lost_password.php");
        break;
    case "forum_profile":                   // 個人檔案
        include("pages/profile.php");
        break;
    case "forum_messages":
        include("pages/messages.php");
        break;
    case "forum_online_users":
        include("pages/online_users.php");
        break;
    case "forum_shop":
        include("pages/shop.php");
        break;
    case "forum_adpanel":
        include("pages/adpanel.php");
        break;
    case "view_forum":
        include("pages/view_forum.php");
        break;
    case "view_thread":
        include("pages/view_thread.php");
        break;
    case "post_thread":
        include("pages/post_thread.php");
        break;
    case "post_replie":
        include("pages/post_replie.php");
        break;
    case "post_edit":                       // 文章編輯
        include("pages/post_edit.php");
        break;
    case "post_delete":                     // 文章刪除
        include("pages/post_delete.php");
        break;
    case "post_quote":
        include("pages/post_quote.php");
        break;
    case "userinfo":
        include("pages/userinfo.php");
        break;
    case "search":
        include("pages/search.php");
        break;
    case "panel":
        include("pages/panel.php");
        break;
    case "adpanel_func":
        include("pages/adpanel_func.php");
        break;
    case "forum_logout":
        unset($_SESSION['usern']);
        session_destroy();
        session_unset();
        header("Location: " . $web['forum_url'] . "sign_in/");
        break;
    case "shop":
        echo 't';
        break;
    default:
        include("pages/home.php");
}
load_footer();
