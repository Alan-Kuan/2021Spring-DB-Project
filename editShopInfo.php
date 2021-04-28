<?php
    include './global.php';

    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: index.php');
        exit();
    }

    if(!isset($_POST['item']) || !isset($_POST['value'])) {
        header('Location: shop.php');
        exit();
    }

    $item = $_POST['item'];
    $value = $_POST['value'];

    if(($item !== 'mask_price' && $item !== 'mask_amount') || !validateNumber($value)) {
        header('Location: shop.php');
        exit();
    }

    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    try {

        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("UPDATE shops JOIN users ON (shops.shopkeeper_id = users.UID) SET $item = :value WHERE username = :username");
        $stmt->execute(array('value' => $value, 'username' => $_SESSION['Username']));

        header('Location: shop.php');

    } catch(PDOException $e) {

        sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'shop.php');

    }

    function validateNumber($num) {

        if(!preg_match('/^[+-]?[0-9]+$/', $num))
            return false;

        return intval($num, 10) >= 0;

    }
?>
