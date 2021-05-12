<?php
    include './global.php';

    session_start();
    $_SESSION['Authenticated'] = false;

    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    if(!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['password-retype']) || !isset($_POST['phone_num'])) {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password-retype'];
    $phone_num = $_POST['phone_num'];

    if(!validateUsername($username)) {
        session_unset();
        session_destroy();
        sendPopupAndGoto($MSG['invalid-username'], 'index.php');
        exit();
    }
    if(!validatePassword($password, $password2)) {
        session_unset();
        session_destroy();
        sendPopupAndGoto($MSG['invalid-password'], 'index.php');
        exit();
    }
    if(!validatePhonenum($phone_num)) {
        session_unset();
        session_destroy();
        sendPopupAndGoto($MSG['invalid-phone_num'], 'index.php');
        exit();
    }

    try {
        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = BINARY :username");
        $stmt->execute(array('username' => $username));

        // username already exists
        if($stmt->rowCount() == 1) {
            session_unset();
            session_destroy();
            sendPopupAndGoto($MSG['user-already-exist'], 'index.php');
            exit();
        } else {
            $salt = str_pad(strval(rand(0000, 9999)), 4, '0', STR_PAD_LEFT);
            $hashed_password = hash('sha256', $salt . $password);
            $stmt = $conn->prepare("INSERT INTO users (username, password, salt, phone_num) VALUES(:username, :password, :salt, :phone_num)");
            $stmt->execute(array(
                'username' => $username,
                'password' => $hashed_password,
                'salt' => $salt,
                'phone_num' => $phone_num
            ));
            $_SESSION['Authenticated'] = true;
            $_SESSION['Username'] = $username;
            sendPopupAndGoto($MSG['register-success'], 'home.php');
            exit();
        }

    } catch(PDOException $e) {
        session_unset();
        session_destroy();
        sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'index.php');
    }

    function validateUsername($username) {

        return preg_match('/^[0-9\.a-zA-Z]+$/', $username);

    }

    function validatePassword($password, $password2) {

        if($password !== $password2)
            return false;

        return preg_match('/^[\ -~]+$/', $password);

    }

    function validatePhonenum($phone_num) {

        $len = strlen($phone_num);
        if($len < 7 || $len > 10)
            return false;

        return preg_match('/^[0-9]+$/', $phone_num);

    }
?>
