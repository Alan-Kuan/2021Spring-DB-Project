<?php
    include './global.php';

    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: index.php');
        exit();
    }

    if(!isset($_POST['employee_name'])) {
        header('Location: shop.php');
        exit();
    }

    $employee_name = $_POST['employee_name'];

    if($employee_name === $_SESSION['Username']) {
        sendPopupAndGoto($MSG['is-shopkeeper'], 'shop.php');
        exit();
    }

    try {
        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare('SELECT UID FROM users WHERE username = BINARY :employee_name');
        $stmt->execute(array('employee_name' => $employee_name));

        if($stmt->rowCount() == 0) {
            sendPopupAndGoto($MSG['not-exist'], 'shop.php');
            exit();
        }

        $employee_id = $stmt->fetch()['UID'];

        $SID = getOwnedShopID();

        $stmt = $conn->prepare('SELECT * FROM employee_shop WHERE employee_id = :employee_id AND shop_id = :shop_id');
        $stmt->execute(array('employee_id' => $employee_id, 'shop_id' => $SID));

        if($stmt->rowCount() == 1) {
            sendPopupAndGoto($MSG['already-been-employee'], 'shop.php');
            exit();
        }

        $stmt = $conn->prepare('INSERT INTO employee_shop VALUES (:employee_id, :shop_id)');
        $stmt->execute(array('employee_id' => $employee_id, 'shop_id' => $SID));

        sendPopupAndGoto($MSG['add-successfully'], 'shop.php');

    } catch(PDOException $e) {
        sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'shop.php');
    }
?>
