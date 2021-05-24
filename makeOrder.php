<?php
    include './global.php';

    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location home.php');
        exit();
    }


    if(!isset($_POST['shop_id'])) {
        header('Location: home.php');
        exit();
    }

    try {
        $amount = $_POST['order_amount'];

        // transaction
        // search amount
        // if enough
        // then make order(may happen some error)
        // else show error msg

        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        $query_get_amount = "SELECT mask_amount, mask_price FROM shops WHERE SID = :shop_id;";
        $query_update = "";
        $query_insert_order = "";
        $query_var = array();
        $shop_id = $_POST['shop_id'];
        $query_var['shop_id'] = $_POST['shop_id'];

        $stmt = $conn->prepare($query_get_amount);
        $stmt->execute($query_var);

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stock = $row['mask_amount'];
            $price = $row['mask_price'];
        }
        else {
            sendPopupAndGoto($MSG['order-fail'], 'home.php');
        }

        if($stock >= $amount) {

            $conn->beginTransaction();

            $query_update = "UPDATE shops SET mask_amount = mask_amount - :order_amount WHERE SID = :shop_id;";
            $query_insert_order = "INSERT INTO orders(status, created_time, order_maker_id, shop_id, order_amount, order_price)
                                    VALUES('pending', :created_date, :orderer, :shop_id, :order_amount, :price);";
            
            $query_var['orderer'] = $_SESSION['UID'];
            $query_var['price'] = $price;
            $query_var['order_amount'] = $amount;
            $query_var['created_date'] = date('Y-m-d H:i:s');

            var_dump($query_var);

            $stmt = $conn->prepare($query_insert_order);
            $stmt->execute($query_var);
            echo 'insert ';

            $stmt = $conn->prepare($query_update);
            $stmt->execute(array( // token number needs to be the same as parameters =A=
                'shop_id' => $shop_id,
                'order_amount' => $amount
            ));

            $conn->commit();

            sendPopupAndGoto($MSG['order-success'], 'home.php');

        }
        else {
            $conn->rollBack();
            sendPopupAndGoto($MSG['order-fail'], 'home.php');
        }

    } catch(PDOException $e) {
        echo $e;
    }

    
?>
