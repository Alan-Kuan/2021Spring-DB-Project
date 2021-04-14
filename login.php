<?php
    session_start();
    $_SESSION['Authenticated'] = false;

    $dbhostname = getenv('DB_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    if(isset($_POST['username'])) {

        $username = $_POST['username'];

        try {

            $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
            # set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt=$conn->prepare("SELECT username FROM users WHERE username=:username");
            $stmt->execute(array('username' => $username));

            if($stmt->rowCount() == 1) {
                $row = $stmt->fetch();
                $hashvalue = hash('sha256', $username);
                $_SESSION['Authenticated'] = true;
                $_SESSION['Username'] = $row[0];
                header("Location: list.php?page=1");
                exit();
            } else {
                session_unset();
                session_destroy();
                echo <<<EOT
                    <!DOCTYPE html>
                    <html>
                        <body>
                            <script>alert("Login failed.");window.location.replace("index.php");</script>
                        </body>
                    </html>
EOT;
            }

        } catch(PDOException$e) {
            $msg = $e->getMessage();
            session_unset();
            session_destroy();
            echo <<<EOT
                <!DOCTYPE html>
                <html>
                    <body>
                        <script>alert("Internal Error.");window.location.replace("index.php");</script>
                    </body>
                </html>
EOT;
        }
    }
?>
