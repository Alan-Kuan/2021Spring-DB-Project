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
    <h2><?= $MSG['shop-info']; ?></h2>
    <div class="input-group w-75 mt-2">
        <span class="input-group-text"><?= $MSG['shop_name']; ?></span>
        <input class="form-control" type="text" value="<?= $shop_name; ?>" disabled />
    </div>
    <div class="input-group w-75 mt-2">
        <span class="input-group-text"><?= $MSG['city']; ?></span>
        <input class="form-control" type="text" value="<?= $CITY[$city_code]; ?>" disabled />
    </div>
    <form action="editShopInfo.php" method="post">
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $MSG['mask_price']; ?></span>
            <input class="form-control" name="value" type="number" min="0" value="<?= $mask_price; ?>" />
            <input name="item" type="hidden" value="mask_price" />
            <input class="btn btn-secondary" type="submit" value="<?= $MSG['edit']; ?>" />
        </div>
    </form>
    <form action="editShopInfo.php" method="post">
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $MSG['mask_amount']; ?></span>
            <input class="form-control" name="value" type="number" min="0" value="<?= $mask_amount; ?>" />
            <input name="item" type="hidden" value="mask_amount" />
            <input class="btn btn-secondary" type="submit" value="<?= $MSG['edit']; ?>" />
        </div>
    </form>
</div>
