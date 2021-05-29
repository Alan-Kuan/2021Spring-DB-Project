<?php
    include './global.php';

    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: home.php');
        exit();
    }

    if(!isset($_POST['order_amount']) || !isset($_POST['shop_id'])) {
        header('Location: home.php');
        exit();
    }

    $amount = $_POST['order_amount'];
    $shop_id = $_POST['shop_id'];

    if(!validateAmount($amount) || !validateShopID($shop_id)) {
        header('Location: home.php');
        exit();
    }

    try {
        // transaction
        // search amount
        // if enough
        // then make order(may happen some error)
        // else show error msg

        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        $query_get_amount = "SELECT mask_amount, mask_price FROM shops WHERE SID = :shop_id";

        $stmt = $conn->prepare($query_get_amount);
        $stmt->execute(array('shop_id' => $shop_id));

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stock = $row['mask_amount'];
            $price = $row['mask_price'];
        } else {
            header('Location: home.php');
            exit();
        }

        try {

            $conn->beginTransaction();

            $query_update = "UPDATE shops SET mask_amount = mask_amount - :order_amount WHERE SID = :shop_id";
            $query_insert_order = "INSERT INTO orders(status, created_time, order_maker_id, shop_id, order_amount, order_price)
                                   VALUES('pending', :created_date, :orderer, :shop_id, :order_amount, :price)";

            $stmt = $conn->prepare($query_update);
            $stmt->execute(array( // token number needs to be the same as parameters =A=
                'order_amount' => $amount,
                'shop_id' => $shop_id
            ));

            $stmt = $conn->prepare($query_insert_order);
            $stmt->execute(array(
                'created_date' => date('Y-m-d H:i:s'),
                'orderer' => $_SESSION['UID'],
                'shop_id' => $shop_id,
                'order_amount' => $amount,
                'price' => $price
            ));

            $conn->commit();

            sendPopupAndGoto($MSG['order-success'], 'home.php');

        } catch(PDOException $e) {
            $conn->rollBack();
            sendPopupAndGoto($MSG['order-fail'], 'home.php');
        }

    } catch(PDOException $e) {
        sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'home.php');
    }

    function validateShopID($id) {

        return preg_match('/^[0-9]+$/', $id);
    
    }

    function validateAmount($amount) {

        return preg_match('/^[+]?[0-9]+$/', $amount);
    
    }
?>
