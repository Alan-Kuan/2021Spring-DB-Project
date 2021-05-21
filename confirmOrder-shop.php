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
        header('Location: shop_order.php');
        exit();
    }

    $OID = $_POST['OID'];

    try {
        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT o.shop_id
                                FROM orders AS o JOIN shops AS s ON (o.shop_id = s.SID)
                                    LEFT JOIN employee_shop AS e_s ON(s.SID = e_s.shop_id)
                                WHERE s.shopkeeper_id = :UID OR e_s.employee_id = :UID
                                    AND o.OID = :OID
                                    AND o.status = 'pending'");
        $stmt->execute(array(
            'UID' => $_SESSION['UID'],
            'OID' => $OID)
        );

        if($stmt->rowCount() == 0) {
            header('Location: shop_order.php');
            exit();
        }

        $row = $stmt->fetch();

        $completer_id = $_SESSION['UID'];
        $shop_id = $row['shop_id'];

        $stmt = $conn->prepare("UPDATE orders SET status = 'completed', completer_id = :completer_id, completed_time = :completed_time
                                WHERE OID = :OID");
        $stmt->execute(array(
            'OID' => $OID,
            'completer_id' => $completer_id,
            'completed_time' => date('Y-m-d H:i:s')
        ));

        header('Location: shop_order.php');

    } catch(PDOException $e) {
        sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'shop_order.php');
    }
?>
