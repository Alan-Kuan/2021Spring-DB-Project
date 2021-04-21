<?php
    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    try {
        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT shop_name, city, mask_price, mask_amount FROM shops JOIN users ON (shopkeeper_id = UID) WHERE username = BINARY :username");
        $stmt->execute(array('username' => $_SESSION['Username']));

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            $shop_name = $row['shop_name'];
            $city_code = $row['city'];
            $mask_price = $row['mask_price'];
            $mask_amount = $row['mask_amount'];
        }

    } catch(PDOException $e) {
        echo 'Internal Error: ' . $e;
        exit();
    }
?>

<div>
    <div>
        <?= $MSG['shop_name']; ?>
        <?= $shop_name; ?>
    </div>
    <div>
        <?= $MSG['city']; ?>
        <?= $CITY[$city_code]; ?>
    </div>
    <div>
        <?= $MSG['mask_price']; ?>
        <?= $mask_price; ?>
    </div>
    <div>
        <?= $MSG['mask_amount']; ?>
        <?= $mask_amount; ?>
    </div>
</div>
