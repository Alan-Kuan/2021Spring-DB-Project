<?php
    include './global.php';

    session_start();
    $_SESSION['Authenticated'] = false;

    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    if(!isset($_POST['username']) || !isset($_POST['password'])) {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    try {

        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare("SELECT password, salt FROM users WHERE username = :username");
        $stmt->execute(array('username' => $username));

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            $hashed_password = hash('sha256', $row['salt'] . $password);
            if($hashed_password === $row['password']) {
                $_SESSION['Authenticated'] = true;
                $_SESSION['Username'] = $username;
                header("Location: home.php");
                exit();
            } else {
                session_unset();
                session_destroy();
                sendPopupAndGoto('登入失敗', 'index.php');
                exit();
            }
        } else {
            session_unset();
            session_destroy();
            sendPopupAndGoto('登入失敗', 'index.php');
            exit();
        }

    } catch(PDOException $e) {
        session_unset();
        session_destroy();
        sendPopup('Internal Error: ' . $e->getMessage(), 'index.php');
    }
?>
