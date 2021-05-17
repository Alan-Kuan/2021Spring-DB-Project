<?php
    include './global.php';

    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: index.php');
        exit();
    }

    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    if(!isset($_POST['OID'])) {
        header('Location: my_order.php');
        exit();
    }

    $OID = $_POST['OID'];

    try {
        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT o.order_maker_id, o.shop_id, o.order_amount
                                FROM orders AS o JOIN users AS u ON (o.order_maker_id = u.UID)
                                WHERE u.username = BINARY :username
                                AND o.OID = :OID
                                AND o.status = 'pending'");
        $stmt->execute(array(
            'username' => $_SESSION['Username'],
            'OID' => $OID)
        );

        if($stmt->rowCount() == 0) {
            header('Location: my_order.php');
            exit();
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

        header('Location: my_order.php');

    } catch(PDOException $e) {
        sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'my_order.php');
    }
?>
