<?php
global $web, $conn;
if (!$_SESSION['usern']) {
    header("Location: ") . $web['forum_url'] . "sign_in/";
}

$p = (int)(!isset($_GET["p"]) ? 1 : $_GET["p"]);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">商店</h3>
    </div>
    <div class="panel-body">
        <?php
        if (isset($_POST["do_purchase"])) {
            $product = protect($_POST['product']);
            $points = userinfo($_SESSION['usern'], "points");
            $result = mysqli_query($conn, "SELECT price, name FROM products WHERE id = $product") or die(mysqli_error($conn));
            $result = mysqli_fetch_array($result);
            $price = $result['price'];
            $pn = $result['name'];
            if ($price > $points) {
                error("您的點數($points) 不足已兌換商品 $pn !");
            } else {
                $user_id = userinfo($_SESSION['usern'], "id");
                $points -= $price;
                mysqli_query($conn, "UPDATE users SET points = '$points' WHERE id='$user_id'") or die(mysqli_error($conn));
                mysqli_query($conn, "UPDATE product_items 
                                           SET PUR_by = '$user_id', PUR_on = SYSDATE()
                                           WHERE id = (
                                                SELECT max(id) FROM product_items
                                                WHERE PUR_on IS NULL AND PUR_by IS NULL AND product_id = '$product'
                                           );"
                ) or die(mysqli_error($conn));
                $get_sql = mysqli_query($conn, "SELECT * FROM product_items WHERE id = ( SELECT max(id) FROM product_items WHERE PUR_by = '$user_id')");
                $get = mysqli_fetch_array($get_sql);

                ?>

                <table border="0" cellspacing="2" cellpadding="2" width="100%">
                    <tr>
                        <td align="center" style="font-size:16px; font-weight:bold;">商品成功購買!</td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size:16px; font-weight:bold;">商品序號： <?php echo $get['key'] ?>!
                        </td>

                    </tr>
                </table><br/><br/><br/><br/>

                <?php
            }
        }

        //顯示紀錄
        $query = mysqli_query($conn, "SELECT * FROM `available_product_items` ORDER BY id DESC") or die(mysqli_error($conn));
        if (mysqli_num_rows($query) > 0) { ?>
            <table border="0" cellspacing="2" cellpadding="2" width="100%">
                <thead>
                <tr>
                    <th>產品名稱</th>
                    <th>產品說明</th>
                    <th>價格/個</th>
                    <th>剩餘數量</th>
                    <th>操作</th>
                </tr>
                </thead>
                <?php
                while ($row = mysqli_fetch_array($query)) {
                    $nums = mysqli_num_rows($query);
                    ?>
                    <tr style="height: 45px">
                        <td>
                            <span style="font-size:19px; font-weight:bold;"><?php echo $row['name']; ?></span>
                        </td>
                        <td>
                            <span style="font-size:15px;"><i> <?php echo $row['description']; ?> </i></span>
                        </td>
                        <td style="width: 15%;">
                            <span style="font-size:15px;"><i> <?php echo $row['price']; ?> </i></span>
                        </td>
                        <td style="width: 15%;">
                            <span style="font-size:15px;"><i> <?php echo $row['avail']; ?> </i></span>
                        </td>
                        <td>
                            <?php
                            if ($row['avail'] > 0) {
                                ?>
                                <form action="" method="POST">
                                    <input type="number" name="product" value="<?php echo $row['id']; ?>" hidden>
                                    <button type="submit" class="btn btn-success" name="do_purchase" value="購買單件">
                                        <i class="fa fa-plus"></i> 購買單件
                                    </button>
                                </form>
                                <?php
                            } else {
                                ?>
                                <button type="submit" class="btn btn-default" style="width: 100px" disabled>
                                    <i class="fa fa-exclamation"></i> 缺貨中
                                </button>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                } ?>
            </table>
            <?php
        } else {
            echo '商店尚無商品上架!';
        }
        ?>

    </div>
</div>
