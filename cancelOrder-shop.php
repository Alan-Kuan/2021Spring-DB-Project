<?php
    include './global.php';

    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: index.php');
        exit();
    }

    if(!isset($_POST['OIDs'])) {
        header('Location: shop_order.php');
        exit();
    }

    $warning = false;

    $OIDs = $_POST['OIDs'];

    foreach($OIDs as $OID) {

        try {
            $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
            # set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT o.shop_id, o.order_amount
                                    FROM orders AS o JOIN shops AS s ON (o.shop_id = s.SID)
                                        LEFT JOIN employee_shop AS e_s ON(s.SID = e_s.shop_id)
                                    WHERE (s.shopkeeper_id = :UID OR e_s.employee_id = :UID)
                                        AND o.OID = :OID
                                        AND o.status = 'pending'");
            $stmt->execute(array(
                'UID' => $_SESSION['UID'],
                'OID' => $OID
            ));

            if($stmt->rowCount() == 0) {
                $warning = true;
                continue;
            }

            $row = $stmt->fetch();

            $completer_id = $_SESSION['UID'];
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
            sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'shop_order.php');
            exit();
        }

    }

    if($warning) {
        sendPopupAndGoto($MSG['invalid-OID'], 'shop_order.php');
    } else {
        header('Location: shop_order.php');
    }
?>
