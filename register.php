<?php
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

    if(!validateUsername($username) || !validatePassword($password, $password2) || !validatePhonenum($phone_num)) {
        session_unset();
        session_destroy();
        header('Location: index.php');
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
            header('Location: index.php');
            exit();
        } else {
            $hashed_password = hash('sha256', $password);
            $stmt = $conn->prepare("INSERT INTO users (username, password, phone_num) VALUES(:username, :password, :phone_num)");
            $stmt->execute(array(
                'username' => $username,
                'password' => $hashed_password,
                'phone_num' => $phone_num
            ));
            $_SESSION['Authenticated'] = true;
            $_SESSION['Username'] = $username;
            sendPopupAndGoto('註冊成功', 'home.php');
            exit();
        }

    } catch(PDOException $e) {
        session_unset();
        session_destroy();
        sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'index.php');
    }

    function sendPopupAndGoto($msg, $page) {
        echo <<<EOT
            <!DOCTYPE html>
            <html>
                <body>
                    <script>
                        alert("$msg");
                        window.location.replace("$page");
                    </script>
                    <p>如果沒有被跳轉，請點擊此<a href="$page">連結</a></p>
                </body>
            </html>
EOT;
    }

    function validateUsername($username) {

        if(empty($username))
            return false;

        return preg_match('/^[0-9\.a-zA-Z]+$/', $username);

    }

    function validatePassword($password, $password2) {

        if(empty($password) || empty($password2))
            return false;
        if($password !== $password2)
            return false;
        if(strlen($password) < 8)
            return false;

        return preg_match('/^[\ -~]+$/', $password);

    }

    function validatePhonenum($phone_num) {

        if(empty($phone_num))
            return false;

        $len = strlen($phone_num);
        if($len < 7 || $len > 10)
            return false;

        return preg_match('/^[0-9]+$/', $phone_num);

    }
?>
