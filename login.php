<?php
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
    $hashed_password = hash('sha256', $_POST['password']);

    try {

        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare("SELECT password FROM users WHERE username = :username");
        $stmt->execute(array('username' => $username));

        if($stmt->rowCount() == 1 && $stmt->fetch()[0] === $hashed_password) {
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

    } catch(PDOException $e) {
        session_unset();
        session_destroy();
        sendPopup('Internal Error: ' . $e->getMessage(), 'index.php');
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
?>
