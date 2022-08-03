<?php
include("includes/db_connect.php");

function array_unique_compact($a){
  $tmparr = array_unique($a);
  $i=0;
  $newarr = [];
  foreach ($tmparr as $v) {
    $newarr[$i] = $v;
    $i++;
  }
  return $newarr;
}

// 移除不合法字元
function protect($string) {
    return htmlspecialchars(trim($string), ENT_QUOTES);
}

// 過濾不合法字元
function filterName($name, $filter = "[^a-zA-Z0-9\-\_\.]") {
    return !preg_match("~" . $filter . "~iU", $name);
}

// 藉由usern查詢使用者的value屬性
function userinfo($user, $value) {
    global $conn;
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE usern='$user'");
    $row = mysqli_fetch_array($sql);
    return $row[$value];
}

// 藉由id查詢使用者的value屬性
function idinfo($id, $value) {
    global $conn;
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
    $row = mysqli_fetch_array($sql);
    return $row[$value];
}

// 成功訊息
function success($text) {
    echo '<div class="alert alert-success">' . $text . '</div>';
}

function error($text) {
    echo '<div class="alert alert-danger">' . $text . '</div>';
}
function warning($text) {
    echo '<div class="alert alert-warning">' . $text . '</div>';
}

function info($text) {
    echo '<div class="alert alert-info">' . $text . '</div>';
}

function create_forum_link($id) {
    global $conn;
    $sql = mysqli_query($conn, "SELECT * FROM forums WHERE id='$id'");
    $row = mysqli_fetch_array($sql);
    $cyrilic = array('÷', 'ù', 'ø', 'þ', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ú', 'ü', 'ÿ', 'ó', '×', 'Ù', 'Ø', 'Þ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ú', 'Ü', 'ß', 'Ó', ' ', '.', ',', '&', '!');
    $latin = array('ch', 'sht', 'sh', 'iu', 'a', 'b', 'v', 'g', 'd', 'e', 'j', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'y', 'q', 'w', 'Ch', 'Sht', 'Sh', 'Iu', 'A', 'B', 'V', 'G', 'D', 'E', 'J', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Y', 'Y', 'Q', 'W', '-', '-', '-', '-', '-');
    return str_replace($cyrilic, $latin, $row['value']);
}

function create_thread_link($id) {
    global $conn;
    $sql = mysqli_query($conn, "SELECT * FROM threads WHERE id='$id'");
    $row = mysqli_fetch_array($sql);
    $cyrilic = array('÷', 'ù', 'ø', 'þ', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ú', 'ü', 'ÿ', 'ó', '×', 'Ù', 'Ø', 'Þ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ú', 'Ü', 'ß', 'Ó', ' ', '.', ',', '&', '!');
    $latin = array('ch', 'sht', 'sh', 'iu', 'a', 'b', 'v', 'g', 'd', 'e', 'j', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'y', 'q', 'w', 'Ch', 'Sht', 'Sh', 'Iu', 'A', 'B', 'V', 'G', 'D', 'E', 'J', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Y', 'Y', 'Q', 'W', '-', '-', '-', '-', '-');
    return str_replace($cyrilic, $latin, $row['title']);
}

function pagination($query, $burl, $per_page = 10, $page = 1, $url = '?') {
    global $conn;
    $query = "SELECT COUNT(*) as `num` FROM {$query}";
    $row = mysqli_fetch_array(mysqli_query($conn, $query));
    $total = $row['num'];
    $adjacents = "2";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;

    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total / $per_page);
    $lpm1 = $lastpage - 1;

    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<ul class='pagination'>";
        $pagination .= "<li class='details'>$page / $lastpage</li>";
        if ($lastpage < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter === $page) {
                    $pagination .= "<li><a class='current'>$counter</a></li>";
                }
                else {
                    $pagination .= "<li><a href='{$burl}$counter/'>$counter</a></li>";
                }
            }
        }
        elseif ($lastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter === $page) {
                        $pagination .= "<li><a class='current'>$counter</a></li>";
                    }
                    else {
                        $pagination .= "<li><a href='{$burl}$counter/'>$counter</a></li>";
                    }
                }
                $pagination.= "<li class='dot'>...</li>";
                $pagination.= "<li><a href='{$burl}$lpm1/'>$lpm1</a></li>";
                $pagination.= "<li><a href='{$burl}$lastpage/'>$lastpage</a></li>";
            }
            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination.= "<li><a href='{$burl}1/'>1</a></li>";
                $pagination.= "<li><a href='{$burl}2/'>2</a></li>";
                $pagination.= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$burl}$counter/'>$counter</a></li>";
                }
                $pagination.= "<li class='dot'>..</li>";
                $pagination.= "<li><a href='{$burl}$lpm1/'>$lpm1</a></li>";
                $pagination.= "<li><a href='{$burl}$lastpage/'>$lastpage</a></li>";
            }
            else {
                $pagination.= "<li><a href='{$burl}1/'>1</a></li>";
                $pagination.= "<li><a href='{$burl}2/'>2</a></li>";
                $pagination.= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$burl}$counter/'>$counter</a></li>";
                }
            }
        }

        if ($page < $counter - 1) {
            $pagination.= "<li><a href='{$burl}$next/'>下一頁</a></li>";
            $pagination.= "<li><a href='{$burl}$lastpage/'>最後一頁</a></li>";
        } else {
            $pagination.= "<li><a class='current'>下一頁</a></li>";
            $pagination.= "<li><a class='current'>最後一頁</a></li>";
        }
        $pagination.= "</ul>\n";
    }
    return $pagination;
}

// 用來防止攻擊
function bbcode($text) {
    $text = preg_replace("/\[quote\=(.+?)](.+?)\[\/quote\]/s", '<fieldset class="post_quote"><legend><span class="post_quote_title">$1 says:</span></legend>$2</fieldset>', $text);
    $text = preg_replace("/\[quote\=(.+?)](.+?)\[\/quote\]/s", '<fieldset class="post_quote"><legend><span class="post_quote_title">$1 says:</span></legend>$2</fieldset>', $text);
    $text = preg_replace("/\[quote\=(.+?)](.+?)\[\/quote\]/s", '<fieldset class="post_quote"><legend><span class="post_quote_title">$1 says:</span></legend>$2</fieldset>', $text);
    $text = preg_replace("/\[quote\=(.+?)](.+?)\[\/quote\]/s", '<fieldset class="post_quote"><legend><span class="post_quote_title">$1 says:</span></legend>$2</fieldset>', $text);
    $text = preg_replace("/\[quote\=(.+?)](.+?)\[\/quote\]/s", '<fieldset class="post_quote"><legend><span class="post_quote_title">$1 says:</span></legend>$2</fieldset>', $text);
    $text = preg_replace("/\[quote\=(.+?)](.+?)\[\/quote\]/s", '<fieldset class="post_quote"><legend><span class="post_quote_title">$1 says:</span></legend>$2</fieldset>', $text);
    $text = preg_replace("/\[quote\=(.+?)](.+?)\[\/quote\]/s", '<fieldset class="post_quote"><legend><span class="post_quote_title">$1 says:</span></legend>$2</fieldset>', $text);
    $text = preg_replace("/\[quote\=(.+?)](.+?)\[\/quote\]/s", '<fieldset class="post_quote"><legend><span class="post_quote_title">$1 says:</span></legend>$2</fieldset>', $text);
    $text = preg_replace("/\[quote\=(.+?)](.+?)\[\/quote\]/s", '<fieldset class="post_quote"><legend><span class="post_quote_title">$1 says:</span></legend>$2</fieldset>', $text);
    $text = preg_replace("/\[quote\=(.+?)](.+?)\[\/quote\]/s", '<fieldset class="post_quote"><legend><span class="post_quote_title">$1 says:</span></legend>$2</fieldset>', $text);
    return $text;
}

//
function protect_post($text) {
    $str = str_replace('<script type="text/javascript">', '&#60;script type="text/javascript"&#62;', $text);
    $str = str_replace('<script>', '&#60;script&#62;', $text);
    $str = str_replace('</script>', '&#60;/script&#62;', $text);
    $str = str_replace('<style>', '&#60;style&#62;', $text);
    $str = str_replace('<style type="text/css" rel="stylesheet">', '&#60;style type="text/css" rel="stylesheet"&#62;', $text);
    $str = str_replace('<style type="text/css">', '&#60;style type="text/css"&#62;', $text);
    $str = str_replace('</style>', '&#60;/style&#62;', $text);
    return($text);
}

// 更新拜訪人數
function update_visits() {
    global $conn;
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $last_update = date("d/m/Y");
    $sql = mysqli_query($conn, "SELECT * FROM visits WHERE user_ip='$user_ip'");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_array($sql);
        if ($row['last_update'] !== $last_update) {
            mysqli_query($conn, "UPDATE settings SET forum_visits=forum_visits+1 WHERE id='1'");
            mysqli_query($conn, "UPDATE visits SET last_update='$last_update' WHERE user_ip='$user_ip'");
        }
    } else {
        mysqli_query($conn, "INSERT visits (user_ip,last_update) VALUES ('$user_ip','$last_update')");
        mysqli_query($conn, "UPDATE settings SET forum_visits=forum_visits+1 WHERE id='1'");
    }
}

// 亂碼產生
function randStr($length) {
    $key = "";
    $pattern = "1234567890ABCDEFGHIKLLMNOPQRSTUVWXYZ";
    for ($i = 0; $i < $length; $i++) {
        $key .= $pattern{mt_rand(0, 35)};
    }
    return $key;
}

