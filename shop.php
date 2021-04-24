<?php
    session_start();
    if(!isset($_SESSION['Authenticated']) || !$_SESSION['Authenticated']) {
        header('Location: index.php');
        exit();
    }
?>

<?php include './header.php'; ?>

<?php includeWith('./components/navbar.php', array('page' => 'shop')); ?>

<div class="w-75 my-5 mx-auto">

    <h1><?= $MSG['shop']; ?></h1>

    <?php
        if(isShopkeeper($_SESSION['Username'])) {
            include './components/manage-shop-panel.php';
        } else {
            include './components/register-shop-form.php';
        }
    ?>

</div>  <!-- container -->

<?php include './footer.php'; ?>

<?php
    function isShopkeeper($username) {

        $dbhostname = getenv('MYSQL_HOST');
        $dbport = '3306';
        $dbname = getenv('MYSQL_DATABASE');
        $dbusername = getenv('MYSQL_USER');
        $dbpassword = getenv('MYSQL_PASSWORD');

        try {
            $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
            # set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT SID FROM users JOIN shops ON (users.UID = shops.shopkeeper_id) WHERE username = BINARY :username");
            $stmt->execute(array('username' => $username));
            return $stmt->rowCount() == 1;
        } catch(PDOException $e) {
            echo 'Internal Error: ' . $e;
            exit();
        }

    }
?>
