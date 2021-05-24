<?php
    include './global.php';

    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: index.php');
        exit();
    }

    if(!isset($_POST['OIDs'])) {
        header('Location: my_order.php');
        exit();
    }

    $warning = false;

    $OIDs = $_POST['OIDs'];

    foreach($OIDs as $OID) {

        try {
            $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
            # set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT order_maker_id, shop_id, order_amount
                                    FROM orders
                                    WHERE order_maker_id = :UID
                                        AND OID = :OID
                                        AND status = 'pending'");
            $stmt->execute(array(
                'UID' => $_SESSION['UID'],
                'OID' => $OID)
            );

            // invalid OID
            if($stmt->rowCount() == 0) {
                $warning = true;
                continue;
            }

            $row = $stmt->fetch();

            $completer_id = $row['order_maker_id'];
            $shop_id = $row['shop_id'];
            $amount = $row['order_amount'];

            $stmt = $conn->prepare("UPDATE orders SET status = 'canceled', completer_id = :completer_id, completed_time = :completed_time
                                    WHERE OID = :OID");
            $stmt->execute(array(
                'OID' => $OID,
                'completer_id' => $completer_id,
                'completed_time' => date('Y-m-d H:i:s')
            ));

            $stmt = $conn->prepare('UPDATE shops SET mask_amount = mask_amount + :amount WHERE SID = :shop_id');
            $stmt->execute(array(
                'amount' => $amount,
                'shop_id' => $shop_id
            ));

        } catch(PDOException $e) {
            sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'my_order.php');
            exit();
        }

    }

    if($warning) {
        sendPopupAndGoto($MSG['invalid-OID'], 'my_order.php');
    } else {
        header('Location: my_order.php');
    }

?>
