<?php
    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    $username = $_SESSION['Username'];

    $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);    
    $stmt = $conn->prepare("SELECT phone_num FROM users WHERE username = :username");
    $stmt->execute(array('username' => $username));

    $phone_num = "";

    if($stmt->rowCount() === 1)
        $phone_num = $stmt->fetch()['phone_num'];
?>

<div class="mt-3">
    <h2><?= $TEXT['profile']; ?></h2>
    <div class="input-group w-75 mt-2">
        <span class="input-group-text"><?= $TEXT['username']; ?></span>
        <input class="form-control" type="text" value="<?= $username; ?>" disabled />
    </div>
    <div class="input-group w-75 mt-2">
        <span class="input-group-text"><?= $TEXT['phone_num']; ?></span>
        <input class="form-control" type="text" value="<?= $phone_num; ?>" disabled />
    </div>
</div>
