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

    if(!isset($_POST['shop_name']) || !isset($_POST['city']) || !isset($_POST['mask_price']) || !isset($_POST['mask_amount'])) {
        header('Location: shop.php');
        exit();
    }

    $shop_name = $_POST['shop_name'];
    $city = $_POST['city'];
    $mask_price = $_POST['mask_price'];
    $mask_amount = $_POST['mask_amount'];

    if(!validateShopname($shop_name)) {
        sendPopupAndGoto($MSG['invalid-shop-name'], 'shop.php');
        exit();
    }
    if($city === 'no-selection') {
        sendPopupAndGoto($MSG['city-no-selection'], 'shop.php');
        exit();
    }
    if(!validateCity($city)) {
        header('Location: shop.php');
        exit();
    }
    if(!validateNumber($mask_amount) || !validateNumber($mask_price)) {
        sendPopupAndGoto($MSG['is-not-number'], 'shop.php');
        exit();
    }

    try {
        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT shop_name FROM shops WHERE shop_name = BINARY :shop_name");
        $stmt->execute(array('shop_name' => $shop_name));

        // shop name already exists
        if($stmt->rowCount() == 1) {
            sendPopupAndGoto($MSG['shop-already-exist'], 'shop.php');
            exit();
        } else {
            $stmt = $conn->prepare("SELECT UID FROM users WHERE username = :username");
            $stmt->execute(array('username' => $_SESSION['Username']));
            $shopkeeper_id = $stmt->fetch()['UID'];

            $stmt = $conn->prepare("INSERT INTO shops (shopkeeper_id, shop_name, city, mask_price, mask_amount)
                                    VALUES(:shopkeeper_id, :shop_name, :city, :mask_price, :mask_amount)");
            $stmt->execute(array(
                'shopkeeper_id' => $shopkeeper_id,
                'shop_name' => $shop_name,
                'city' => $city,
                'mask_price' => $mask_price,
                'mask_amount' => $mask_amount
            ));
            sendPopupAndGoto('註冊成功', 'shop.php');
            exit();
        }

    } catch(PDOException $e) {
        sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'shop.php');
    }

    function validateShopname($shop_name) {

        if($shop_name === '')
            return false;

        // shop name should not start with or end with spaces
        return !preg_match('/^\ +.*$/', $shop_name) && !preg_match('/^.*\ +$/', $shop_name);

    }

    function validateCity($city) {

        $cities = array(
            'taipei-city',
            'new-taipei-city',
            'keelung-city',
            'taoyuan-city',
            'hsinchu-city',
            'hsinchu-county',
            'miaoli-county',
            'taichung-city',
            'changhua-county',
            'nantou-county',
            'yunlin-county',
            'chiayi-city',
            'chiayi-county',
            'tainan-city',
            'kaohsiung-city',
            'pingtung-county',
            'yilan-county',
            'hualien-county',
            'taitung-county',
            'penghu-county',
            'kinmen-county',
            'lienchiang-county'
        );

        return in_array($city, $cities);

    }

    function validateNumber($num) {

        if(!preg_match('/^[+-]?[0-9]+$/', $num))
            return false;

        return intval($num, 10) >= 0;

    }
?>
