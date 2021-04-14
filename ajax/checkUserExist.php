<?php
    $username = $_POST['username'];

    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    try {

        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare('SELECT username FROM users WHERE username = BINARY :username');
        $stmt->execute(array('username' => $username));

        // return whether this username already exists
        echo json_encode(array('userExists' => $stmt->rowCount()));

    } catch(PDOException $e) {

        // error
        echo json_encode(array('userExists' => 2));

    }
?>
