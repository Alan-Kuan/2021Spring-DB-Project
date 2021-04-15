<?php
    session_start();
    $_SESSION['Authenticated'] = false;

    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password-retype']) && isset($_POST['phone_num'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password-retype'];
        $phone_num = $_POST['phone_num'];

        if($password !== $password2) {
            session_unset();
            session_destroy();
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
                exit();
            } else {
                createAccount($username, $password, $phone_num);
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
                </body>
            </html>
EOT;
    }

    function createAccount($username, $password, $phone_num) {

        $hashed_password = hash('sha256', $password);

        $dbhostname = getenv('MYSQL_HOST');
        $dbport = '3306';
        $dbname = getenv('MYSQL_DATABASE');
        $dbusername = getenv('MYSQL_USER');
        $dbpassword = getenv('MYSQL_PASSWORD');

        try {
            $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
            # set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("INSERT INTO users (username, password, phone_num) VALUES(:username, :password, :phone_num)");
            $stmt->execute(array(
                'username' => $username,
                'password' => $hashed_password,
                'phone_num' => $phone_num
            ));

        } catch(PDOException $e) {
            session_unset();
            session_destroy();
            sendPopupAndGoto('Internal Error: ' . $e->getMessage(), 'index.php');
        }

    }
?>
