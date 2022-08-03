<div class="row">
    <div class="col-md-8">
        <?php
        global $web, $conn;
        $sql = mysqli_query($conn, "SELECT * FROM categories ORDER BY id");
        if (mysqli_num_rows($sql) > 0) {
            while ($r = mysqli_fetch_array($sql)) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?php echo $r['value']; ?>
                            <h2></h2>
                    </div>
                    <div class="panel-body">
                        <table border="0" cellspacing="2" cellpadding="2" width="100%">
                            <?php
                            $i = 0;
                            $sql_2 = mysqli_query($conn, "SELECT * FROM forums WHERE category_id='$r[id]' ORDER BY id");
                            if (mysqli_num_rows($sql_2) > 0) {
                                while ($row = mysqli_fetch_array($sql_2)) {
                                    $nums = mysqli_num_rows($sql_2);
                                    ?>
                                    <tr>
                                        <td width="8%">
                                            <img src="<?php echo $row['icon']; ?>"/>
                                        </td>
                                        <td width="80%">
                                            <h5>
                                                <a href="<?php echo $web['forum_url']; ?>f/<?php echo create_forum_link($row['id']); ?>/<?php echo $row['id']; ?>/"
                                                   class="forum_link">
                                                    <?php echo $row['value']; ?>
                                                </a>
                                            </h5>
                                            最後發佈:
                                            <?php
                                            $get_sql = mysqli_query($conn, "SELECT * FROM threads WHERE forum_id='$row[id]' and category_id='$r[id]' ORDER BY id DESC LIMIT 1");
                                            if (mysqli_num_rows($get_sql) > 0) {
                                                $get = mysqli_fetch_array($get_sql);
                                                ?>
                                                <a href="<?php echo $web['forum_url']; ?>t/<?php echo create_thread_link($get['id']); ?>/<?php echo $get['id']; ?>/"
                                                   class="forum_link2">
                                                    <?php echo $get['title']; ?>
                                                </a>
                                                by
                                                <a href="<?php echo $web['forum_url']; ?>user/<?php echo idinfo($get['user_id'], "usern"); ?>/"><?php echo idinfo($get['user_id'], "display_name"); ?>
                                                </a>
                                                <?php
                                            } else {
                                                echo '無';
                                            }
                                            ?>
                                        </td>
                                        <td width="8%" align="center">
                                            <b>文章</b>
                                            <br/><br/>
                                            <span class="label label-success">
                                        <?php
                                        echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM threads WHERE forum_id='$row[id]' and category_id='$r[id]'"));
                                        ?>
                                    </span>
                                        </td>
                                        <td width="8%" align="center">
                                            <b>回覆</b>
                                            <br/><br/>
                                            <span class="label label-success">
                                        <?php
                                        echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM replies WHERE forum_id='$row[id]' and category_id='$r[id]'"));
                                        ?>
                                    </span>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                    if ($i !== $nums) {
                                        echo '<tr><td colspan="4"><hr /></td></tr>';
                                    }
                                }
                            } else {
                                echo '<tr><td>No forums in this category.</td></tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <br>
                <?php
            }
        } else {
            info("No added categories in database.");
        }
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    統計
                </h3>
            </div>
            <div class="panel-body">
                <table border="0" cellspacing="2" cellpadding="2" width="100%">
                    <tr>
                        <td align="center">
                            <?php $statistics = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM STATISTICS")); ?>
                            <span class="statistics_text">
                                網站瀏覽次數: <?php echo $statistics['FORUM_VISITS']; ?>
                            </span>
                        </td>
                        <td align="center">
                            <span class="statistics_text">
                                總文章數: <?php echo $statistics['THREAD_COUNT']; ?></span>
                        </td>
                        <td align="center">
                            <span class="statistics_text">
                                總會員數: <?php echo $statistics['USER_COUNT']; ?></span></span>
                        </td>
                        <td align="center">
                            <span class="statistics_text">
                                線上會員:
                                <b>
                                    <a href="<?php echo $web['fourm_url']; ?>online_users/">
                                        <?php $online_time = time() - 600;echo number_format(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE online_time > $online_time"))); ?>
                                    </a>
                                </b>
                            </span>
                        </td>
                        <td align="center">
                            <span class="statistics_text">最新會員: <b>
                    <?php
                    $last_user = mysqli_query($conn, "SELECT * FROM last_user");
                    if (mysqli_num_rows($last_user) > 0) {
                        $get = mysqli_fetch_array($last_user);
                        ?>
                        <a href="<?php echo $web['forum_url']; ?>user/<?php echo $get['usern']; ?>/"><?php echo $get['display_name']; ?></a>
                        <?php
                    } else {
                        echo '無';
                    }
                    ?>
                </b>
            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">最新文章</h3>
            </div>
            <div class="panel-body">
                <ul class="ipsList_withminiphoto">
                    <?php
                    $last_six_threads = mysqli_query($conn, "SELECT * FROM last_six_threads");
                    if (mysqli_num_rows($last_six_threads) > 0) {
                        while ($get = mysqli_fetch_array($last_six_threads)) {
                            ?>
                            <li class="clearfix">

                                <div class="left">
                                    <?php
                                    if (idinfo($get['user_id'], "avatar") == NULL) {
                                        ?>
                                        <img src="<?php echo $web['forum_url']; ?>assets/imgs/davatar.jpg" width="130px"
                                             height="130px" class="ipsUserPhoto ipsUserPhoto_mini">
                                        <?php
                                    } else {
                                        ?>
                                        <img src="<?php echo $web['forum_url']; ?>image.php?width=130&height=130&image=<?php echo $web['forum_url'];
                                        echo idinfo($get['user_id'], "avatar"); ?>&cropratio=1:1"
                                             class="ipsUserPhoto ipsUserPhoto_mini">
                                        <?php
                                    }
                                    ?>
                                </div>

                                <div class="list_content">
                                    <a href="<?php echo $web['forum_url']; ?>t/<?php echo create_thread_link($get['id']); ?>/<?php echo $get['id']; ?>/"
                                       rel="bookmark" class="ipsType_small"
                                       title="<?php echo $get['title']; ?> Виж темата, започната на Вчера, 20:46">
                                        <?php echo $get['title']; ?>
                                    </a>
                                    <p class="desc ipsType_smaller">
                                        <a href="<?php echo $web['forum_url']; ?>user/<?php echo idinfo($get['user_id'], "usern"); ?>/">
                                            <?php echo idinfo($get['user_id'], "display_name"); ?>
                                        </a>
                                        - <?php echo $get['date']; ?>
                                    </p>
                                </div>
                            </li>
                            <?php
                        }
                    } else {
                        echo '無';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">熱門標籤</h3>
            </div>
            <div class="panel-body">
                <?php
                $types = [];

                $rs = mysqli_query($conn, "SELECT tags FROM threads");
                if (mysqli_num_rows($rs) > 0) {
                    while ($ftypes = mysqli_fetch_array($rs)) {
                        $types = array_merge($types, explode(",", $ftypes[0]));
                    }

                    $types = array_unique_compact($types);

                    for ($i = 0, $iMax = count($types); $i < $iMax; $i++) {
                        $tag = str_replace(" ", "", $types[$i]);
                        echo '<a href="' . $web['forum_url'] . 'tag/' . $tag . '">' . $types[$i] . '</a>';

                        if ($i < count($types) - 1) {
                            echo ", \n";
                        }
                    }
                } else {
                    echo '無任何標籤.';
                }
                ?>
            </div>
        </div>
    </div>
</div>
