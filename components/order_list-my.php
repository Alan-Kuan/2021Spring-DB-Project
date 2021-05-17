<?php
    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    $orders = array();

    try {
        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT o.OID, o.status, o.created_time, o.completed_time, o.order_amount, o.order_price,
                                    a.username, s.shop_name
                                FROM orders AS o JOIN users AS m ON (o.order_maker_id = m.UID)
                                    LEFT JOIN users AS a ON (o.completer_id = a.UID)
                                    JOIN shops AS s ON (o.shop_id = s.SID)
                                WHERE m.username = BINARY :username");
        $stmt->execute(array('username' => $_SESSION['Username']));

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            array_push($orders, array(
                'OID' => $row['OID'],
                'status' => $row['status'],
                'created_time' => $row['created_time'],
                'completed_time' => $row['completed_time'],
                'completer' => $row['username'],
                'shop_name' => $row['shop_name'],
                'order_amount' => $row['order_amount'],
                'order_price' => $row['order_price']
            ));
        }

    } catch(PDOException $e) {
        echo 'Internal Error: ' . $e;
        exit();
    }
?>

<div class="mt-5">
    <?php
        if(!empty($orders)):
    ?>
    <table class="table table-striped table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th id="OID"><?= $TEXT['OID']; ?></th>
                <th id="status"><?= $TEXT['status']; ?></th>
                <th id="created_time"><?= $TEXT['created-time']; ?></th>
                <th id="order_maker"><?= $TEXT['order-maker']; ?></th>
                <th id="completed_time"><?= $TEXT['completed-time']; ?></th>
                <th id="completer"><?= $TEXT['order-completer']; ?></th>
                <th id="shop_name"><?= $TEXT['shop_name']; ?></th>
                <th id="total_price"><?= $TEXT['total-price']; ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($orders as $order):
        ?>
            <tr>
                <td class="align-middle"><?= $order['OID']; ?></td>
                <td class="align-middle"><?= $TEXT[$order['status']]; ?></td>
                <td class="align-middle"><?= $order['created_time']; ?></td>
                <td class="align-middle"><?= $_SESSION['Username']; ?></td>
                <td class="align-middle"><?= $order['completed_time']; ?></td>
                <td class="align-middle"><?= $order['completer']; ?></td>
                <td class="align-middle"><?= $order['shop_name']; ?></td>
                <td class="align-middle">
                    $<?= $order['order_amount'] * $order['order_price']; ?>
                    (<?= $order['order_amount']; ?> * <?= $order['order_price']; ?>)
                </td>
                <td>
                    <form action="cancelOrder-my.php" method="post">
                        <input type="hidden" name="OID" value="<?= $order['OID']; ?>" />
                        <input class="btn btn-danger" type="submit" value="<?= $TEXT['cancel']; ?>"
                               <?= $order['status'] !== 'pending' ? 'disabled' : ''; ?> />
                    </form>
                </td>
            </tr>
        <?php
            endforeach;
        ?>
        </tbody>
    </table>
    <?php
        else:
    ?>
    <div class="mt-2">
        <p><?= $MSG['no-order']; ?></p>
    </div>
    <?php
        endif;
    ?>
</div>
