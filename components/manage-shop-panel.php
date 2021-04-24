<?php
    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    try {
        $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $SID = getOwnedShopID();

        $stmt = $conn->prepare("SELECT shop_name, city, mask_price, mask_amount FROM shops WHERE SID = :SID");
        $stmt->execute(array('SID' => $SID));

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            $shop_name = $row['shop_name'];
            $city_code = $row['city'];
            $mask_price = $row['mask_price'];
            $mask_amount = $row['mask_amount'];
        }

    } catch(PDOException $e) {
        echo 'Internal Error: ' . $e;
        exit();
    }
?>

<div class="mt-3">
    <h2><?= $MSG['shop-info']; ?></h2>
    <div class="input-group w-75 mt-2">
        <span class="input-group-text"><?= $MSG['shop_name']; ?></span>
        <input class="form-control" type="text" value="<?= $shop_name; ?>" disabled />
    </div>
    <div class="input-group w-75 mt-2">
        <span class="input-group-text"><?= $MSG['city']; ?></span>
        <input class="form-control" type="text" value="<?= $CITY[$city_code]; ?>" disabled />
    </div>
    <form action="editShopInfo.php" method="post">
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $MSG['mask_price']; ?></span>
            <input class="form-control" name="value" type="number" min="0" value="<?= $mask_price; ?>" />
            <input name="item" type="hidden" value="mask_price" />
            <input class="btn btn-secondary" type="submit" value="<?= $MSG['edit']; ?>" />
        </div>
    </form>
    <form action="editShopInfo.php" method="post">
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $MSG['mask_amount']; ?></span>
            <input class="form-control" name="value" type="number" min="0" value="<?= $mask_amount; ?>" />
            <input name="item" type="hidden" value="mask_amount" />
            <input class="btn btn-secondary" type="submit" value="<?= $MSG['edit']; ?>" />
        </div>
    </form>
</div>

<div class="mt-3">
    <h2><?= $MSG['employee-list']; ?></h2>
    <form action="addEmployee.php" method="post">
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $MSG['add-employee']; ?></span>
            <input class="form-control" name="employee_name" type="text" />
            <input class="btn btn-secondary" type="submit" value="<?= $MSG['add']; ?>" />
        </div>
    </form>

    <?php
        $shop_id = getOwnedShopID();
        $employees = getEmployees($shop_id);

        if(count($employees)):
    ?>
    <table class="table table-striped table-hover w-75 mt-3">
        <thead class="table-dark">
            <tr>
                <th><?= $MSG['username']; ?></th>
                <th><?= $MSG['phone_num']; ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($employees as $employee_info):
        ?>
            <tr>
                <td class="align-middle"><?= $employee_info['username']; ?></td>
                <td class="align-middle"><?= $employee_info['phone_num']; ?></td>
                <td>
                    <form action="deleteEmployee.php" method="post">
                        <input type="hidden" name="employee_name" value="<?= $employee_info['username']; ?>" />
                        <input class="btn btn-danger" type="submit" value="<?= $MSG['delete']; ?>" />
                    </form>
                </td>
            </tr>
        <?php
            endforeach;
        ?>
        </tbody>
    </table>
    <?php
        else:
    ?>
    <div class="mt-2">
        <p><?= $MSG['no-employee']; ?></p>
    </div>
    <?php
        endif;
    ?>
</div>

<?php
    function getEmployees($shop_id) {

        $dbhostname = getenv('MYSQL_HOST');
        $dbport = '3306';
        $dbname = getenv('MYSQL_DATABASE');
        $dbusername = getenv('MYSQL_USER');
        $dbpassword = getenv('MYSQL_PASSWORD');

        $employee_info = array();

        try {
            $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
            # set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare('SELECT username, phone_num FROM employee_shop JOIN users ON (employee_shop.employee_id = users.UID) WHERE shop_id = :shop_id ORDER BY username');

            $stmt->execute(array('shop_id' => $shop_id));
            while($row = $stmt->fetch())
                array_push($employee_info, array('username' => $row['username'], 'phone_num' => $row['phone_num']));

        } catch(PDOException $e) {
            throw $e;
        }

        return $employee_info;

    }
?>
