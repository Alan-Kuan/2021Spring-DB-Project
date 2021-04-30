<?php
    $shop_name = $_POST['shop_name'];

    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    try {

        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare('SELECT shop_name FROM shops WHERE shop_name = BINARY :shop_name');
        $stmt->execute(array('shop_name' => $shop_name));

        // return whether this shop name already exists
        echo json_encode(array('shopExists' => $stmt->rowCount()));

    } catch(PDOException $e) {

        // error
        echo json_encode(array('shopExists' => 2));

    }
?>
