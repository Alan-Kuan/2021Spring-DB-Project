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

        $query = 'SELECT o.OID, o.status, o.created_time, o.completed_time, o.order_amount, o.order_price,
                      m.username AS maker, c.username AS completer, s.shop_name, e_s.employee_id
                  FROM orders AS o JOIN users AS m ON (o.order_maker_id = m.UID)
                      LEFT JOIN users AS c ON (o.completer_id = c.UID)
                      JOIN shops AS s ON (o.shop_id = s.SID)
                      LEFT JOIN employee_shop AS e_s ON (s.SID = e_s.shop_id)
                  WHERE e_s.employee_id = :UID OR s.shopkeeper_id = :UID';
        $query_var = array('UID' => $_SESSION['UID']);

        if(isset($_GET['status'])) {

            $status = $_GET['status']; 

            if(!in_array($status, array('no-selection', 'pending', 'completed', 'canceled'))) {
                header('Location: shop_order.php');
                exit();
            }

            if($status !== 'no-selection') {
                $query .= ' AND o.status = :status';
                $query_var['status'] = $status;
            }

        }

        $stmt = $conn->prepare($query);
        $stmt->execute($query_var);

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            array_push($orders, array(
                'OID' => $row['OID'],
                'status' => $row['status'],
                'created_time' => $row['created_time'],
                'maker' => $row['maker'],
                'completed_time' => $row['completed_time'],
                'completer' => $row['completer'],
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

<div class="mt-5 w-25">
    <form method="get">
        <div class="input-group mt-2">
            <span class="input-group-text"><?= $TEXT['status']; ?></span>
            <?php includeWith('./components/status-select.php', array('default' => isset($_GET['status']) ? $_GET['status'] : '')); ?>
            <input class="btn btn-secondary" type="submit" value="<?= $TEXT['submit']; ?>" />
        </div>
    </form>
</div>

<div class="mt-3">
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
                <td class="align-middle"><?= $order['maker']; ?></td>
                <td class="align-middle"><?= $order['completed_time']; ?></td>
                <td class="align-middle"><?= $order['completer']; ?></td>
                <td class="align-middle"><?= $order['shop_name']; ?></td>
                <td class="align-middle">
                    $<?= $order['order_amount'] * $order['order_price']; ?>
                    (<?= $order['order_amount']; ?> * <?= $order['order_price']; ?>)
                </td>
                <td>
                    <form action="cancelOrder-shop.php" method="post">
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
